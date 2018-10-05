<?php

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Pimple\Container;
use Symfony\Component\Yaml\Yaml;

require __DIR__ . '/../vendor/autoload.php';

class App
{
    public static $routes;
    public static $config;
    public static $container;
    public static $requestContent;

    /** @var Monolog\Logger $log */
    public static $log;

    public static function init()
    {
        self::$requestContent = file_get_contents('php://input');

        self::$routes = Yaml::parseFile(__DIR__ . '/config/routes.yml');
        self::$config = Yaml::parseFile(__DIR__ . '/config/config.yml');

        self::initLog(self::$config['logger']['file']);
    }

    public static function initLog($file=false, $silent=false)
    {
        self::$log = new Logger('api-log');

        try {

            if ($silent) {
                $logHandler = new \Monolog\Handler\NullHandler();
            } else {
                $logsLevel = [
                    'NOTICE'  => Logger::NOTICE,
                    'WARNING' => Logger::WARNING,
                    'ERROR'   => Logger::ERROR,
                    'ALERT'   => Logger::ALERT
                ];
                $logLevel   = $logsLevel[self::$config['logger']['level']];
                $logHandler = new StreamHandler(__DIR__ . $file, $logLevel);
            }

            self::$log->pushHandler($logHandler);
        } Catch (Exception $e) {
            error_log('[ERROR] Error log monolog: ' . $e->getMessage());
            exit('[ERROR] Can not init Application');
        }
    }

    public static function getDiContainer($config)
    {
        $container = new Container();

        ORM::configure('mysql:host='.$config['db']['host'].';dbname='.$config['db']['database-name']);
        ORM::configure('username', $config['db']['user']);
        ORM::configure('password', $config['db']['password']);

        $container['order.repository']       = new MysqlOrderRepository();
        $container['order.validate.service'] = new ValidateOrderService($container['order.repository']);
        $container['order.create.service']   = new CreateOrderService(
            $container['order.repository'],
            $container['order.validate.service']
        );
        $container['order.create.use-case']  = new CreateOrderUseCase($container['order.create.service']);
        $container['order.controller']       = new OrderController();
        $container['index.controller']       = new IndexController();

        return $container;
    }

    public static function addRoutes($routes, $diContainer)
    {
        $router = new \Bramus\Router\Router();

        foreach ($routes as $route) {
            $router->match($route['method'], $route['pattern'], function () use ($diContainer, $route) {
                $controllerObj = $diContainer[$route['controller']];
                $action        = $route['action'];
                $controllerObj->$action();
            });
        }

        $router->set404(function() {
            BaseController::notFound();
        });

        $router->run();
    }

    public static function run()
    {
        $diContainer = self::getDiContainer(self::$config);
        self::$container = $diContainer;
        self::addRoutes(self::$routes, $diContainer);
    }
}
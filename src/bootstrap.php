<?php

spl_autoload_register('__autoload');
function __autoload($className)
{
    if (preg_match("/[A-Z][a-z]+Controller/", $className)) {
        include __DIR__ . '/Controller/' . $className . '.php';
    }

    if (preg_match("/[A-Z][a-z]+(UseCase|Request|Response)/", $className)) {
        include __DIR__ . '/Application/' . $className . '.php';
    }

    if (preg_match("/[A-Z][a-z]+(Service|Entity|Exception)/", $className)) {
        include __DIR__ . '/Domain/' . $className . '.php';
    }

    if (preg_match("/Mysql([A-Z][a-z]+Repository)/", $className, $matches)) {
        include __DIR__ . '/Domain/' . $matches[1] . '.php';
        include __DIR__ . '/Infrastructure/' . $className . '.php';
    }

    if ($className == 'OrderFactory') {
        include __DIR__ . '/Domain/OrderFactory.php';
    }
}

// For debug purposes
// error_reporting(E_ALL);
// ini_set('display_errors', 1);





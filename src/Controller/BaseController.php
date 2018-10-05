<?php


class BaseController
{
    const SUCCESS_STATUS         = 200;
    const ACCEPTED_STATUS        = 202;
    const NO_CONTENT_STATUS      = 204;
    const PARTIAL_CONTENT_STATUS = 206;
    const BAD_REQUEST_STATUS     = 400;
    const UNAUTHORIZED_STATUS    = 401;
    const NOT_FOUND_STATUS       = 404;
    const CONFLICT_STATUS        = 409;
    const ERROR_STATUS           = 500;

    public $request;

    public function getRequest()
    {
        $this->request = file_get_contents('php://input');
        return $this->request;
    }

    public static function putResponse($code, $info)
    {
        header('Content-Type: application/json');
        http_response_code($code);
        $response = json_encode($info);
        echo $response;
        exit;
    }

    public static function notFound($msg='')
    {
        $msg = $msg . 'Not Found';
        self::putResponse(self::NOT_FOUND_STATUS, ['msg' => $msg, 'code' => self::NOT_FOUND_STATUS]);
    }

    public static function badRequest($errors=[])
    {
        self::putResponse(self::BAD_REQUEST_STATUS, [
            'msg' => 'Bad Request',
            'errors' => $errors,
            'code' => self::BAD_REQUEST_STATUS]
        );
    }

    public static function error($error)
    {
        self::putResponse(self::ERROR_STATUS, ['msg' => $error, 'code' => self::ERROR_STATUS]);
    }
}
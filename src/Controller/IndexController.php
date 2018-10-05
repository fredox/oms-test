<?php

class IndexController extends BaseController
{
    public function info()
    {
        $info = [
            'author' => 'Alfredo Galiana Mora',
            'description' => 'Little Api in Json to manage orders'
        ];

        BaseController::putResponse(self::SUCCESS_STATUS, $info);
    }
}
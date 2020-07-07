<?php

namespace Controller;

use Model\Services\IndexService;

class IndexController
{
    public function error($code)
    {
        $result = [
            'success' => false,
            'errorCode' => $code,
            'msg' => 'method or class not found'
        ];

        echo json_encode($result, JSON_PRETTY_PRINT);
        return $result;
    }

    public function home()
    {
        var_dump('IndexController');
        $service = new IndexService();
        $service->home();
    }
}
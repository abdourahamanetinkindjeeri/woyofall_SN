<?php
namespace App\core\abstract;
use App\core\Session;
use App\core\App;

abstract class AbstractController
{
    protected function __construct(){}

    protected function renderJson($data, $statusCode = 200) 
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        // return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        echo(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    }
}
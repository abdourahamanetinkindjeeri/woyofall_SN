<?php

namespace App\config;

class Helpers
{
    public static function dd( $data) : void {
        echo '<pre>';
        var_dump($data);
        echo '</pre>';
        die();
    }

    public static function redirect(string $url): void {
        header("Location: $url");
        exit();
    }
    
}
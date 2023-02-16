<?php

namespace Frame\Routing;

class Route {

    private static array $map = [];

    public static function get($url,$callbackClass,$callbackMethod)
    {   
        self::$map[$url]['GET'] = [
            'CLASS' => $callbackClass ,
            'METHOD' => $callbackMethod
        ];
    }

    public static function post($url,$callbackClass,$callbackMethod)
    {
        self::$map[$url]['POST'] = [
            'CLASS' => $callbackClass ,
            'METHOD' => $callbackMethod
        ];
    }

    public static function map() {
        return self::$map;
    }
}
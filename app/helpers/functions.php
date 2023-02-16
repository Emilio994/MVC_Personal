<?php

require_once(__DIR__ . '/strings.php');

/**
 * @param mixed
 * 
 * Debug args 
 * 
 */

function debug(mixed $args) :void {
    echo '<pre>';
    is_array($args) ? print_r($args) : var_dump($args);
    echo '</pre>';
}

/**
 * @param mixed
 * 
 * Debug args and die
 * 
 */

function dd(mixed $args) :void {
    echo '<pre>';
    is_array($args) ? print_r($args) : var_dump($args);
    echo '</pre>';
    die;
}

/**
 * @param array $middlewares
 * @param string $url
 * @return array $middlewareStack
 */

function buildMiddlewareStack(array $middlewares,string $url) {
    
    if($exceptions = \getRouteExceptions($middlewares,$url)) {

        return \array_filter($middlewares['classes'], function($middleware) use ($exceptions) {
            if(\in_array($middleware,$exceptions)) return false;
            return true;
        });

    } 
    
    return $middlewares['classes'];
}

function getRouteExceptions(array $middlewares, string $url) :mixed {

    $urlSegments = \explode('/',$url);
    
    $lastSegment = $urlSegments[\count($urlSegments) - 1];

    if(\array_key_exists($lastSegment,$middlewares['exceptions'])) return $middlewares['exceptions'][$lastSegment];

    return false;
}

function refreshSession() :void {
    \session_destroy();
    \session_start();
}





<?php

namespace Frame\Routing;
use Frame\Http\Request;

class Router {

    private static $instance;
    private $routes;

    public static function instance() {

        if(!isset(self::$instance)) {
            self::$instance = new self;
        }

        return self::$instance;
    }   

    private function __construct()
    {
        $this->routes = require_once(WEB_ROUTES);
    }

    public function resolve(Request $req)
    {   

        if(isset($this->routes[$req->url])) {
            if(isset($this->routes[$req->url][$req->verb])) {
                return $this->routes[$req->url][$req->verb];
            }
            header("HTTP/1.1 404 Not Found");
            echo "404 No route available for $_SERVER[REQUEST_METHOD] method";
            die;
        }
        header("HTTP/1.1 404 Not Found");
        echo '404 Missing Path';
        die;
    }

}
<?php

namespace Frame;

use Frame\Container\ServiceContainer;
use Frame\Http\Request;
use Frame\Routing\Router;
use Frame\Middleware\AuthMiddleware;
use Frame\Middleware\LoggedUserMiddleware;

class App {

    protected static $instance;

    private Request $req;

    private Router $router;
    
    private array $callback;

    private mixed $solution;


    private $middlewares = [
        'classes' => [
            AuthMiddleWare::class,
            LoggedUserMiddleware::class
        ],
        'exceptions' => [
            'login' => [
                AuthMiddleWare::class,
            ]
        ]
        
    ];
    
    public static function singleton() {
        if (is_null(static::$instance)) {
            static::$instance = new static;
        }

        return static::$instance;
    }

    private function __construct() {   
        $this->router = Router::instance();
    }
    
    public function listen() {   
        $this->req = new Request();
        $this->dispatch();
    }

    private function dispatch() {   

        $this->callback = $this->router->resolve($this->req);
        
        $this->resolve();
    }
    

    // Risoluzione azione controller
    private function resolve() {

        if(!$middlewareStack = \buildMiddlewareStack($this->middlewares,$this->req->url)) {
            $this->solution = ServiceContainer::invokeAction(
                $this->callback['CLASS'],
                $this->callback['METHOD'],
            );
        } 
        
        else {
            $controllerClass = ServiceContainer::make($this->callback['CLASS']);
            $controllerAction = new \ReflectionMethod($controllerClass,$this->callback['METHOD']);
            $controllerAction = $this->applyMiddlewares($middlewareStack,$controllerAction->getClosure($controllerClass),$this->req);
            $this->solution = $controllerAction($this->req);
            
        }
        
    }

    private function applyMiddlewares($middlwareStack, $controllerAction,$request) {

        foreach($middlwareStack as $middleware) {

            $middlwareClass = ServiceContainer::make($middleware);
            $middlewareAction = new \ReflectionMethod($middlwareClass,'handle');
            $controllerAction = fn() => $middlewareAction->getClosure($middlwareClass)($controllerAction,$request);
        }

        return $controllerAction;

    }

    public function response() {
        echo $this->solution;
    }

}
<?php
namespace Frame\Middleware;
use Frame\Http\Request;

class AuthMiddleware extends BaseMiddleware {

    public function handle($next,Request $request){
        
        if(isset($_SESSION['user'])) return $next($request);

        return header('Location: /login', true, 302);

    }
}
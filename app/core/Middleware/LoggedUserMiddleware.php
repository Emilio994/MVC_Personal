<?php
namespace Frame\Middleware;
use Frame\Http\Request;
use Frame\Models\User;

class LoggedUserMiddleware extends BaseMiddleware {

    public function handle($next,Request $request){
        
        if(isset($_SESSION['user']) && \str_contains($request->url,'login')) {
            header('Location: /', true, 302); 
            die;
        }

        return $next($request,new User);

    }
}
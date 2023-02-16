<?php

namespace Frame\Controllers;
use Frame\Html\View;
use Frame\Http\Request;
use Frame\Models\User;
use Frame\Container\ServiceContainer;

class LoginController extends BaseController{

    public function main() {   

        $content =  View::render('login');

        return View::render('layout.body',[
            'title' => 'Login',
            'content' => $content,
            'viewHasMenu' => false,
            'viewHasFooter' => true
        ]);

    }

    public function validate(Request $request, User $user) {

        $user->login(
            $request->params['username'],
            $request->params['password'],
        );

        header('Location: /', true , 302);
        die;
    }

}
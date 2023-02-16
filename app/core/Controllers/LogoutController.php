<?php

namespace Frame\Controllers;
use Frame\Html\View;

class LogoutController extends BaseController{

    public function main() {   

        $content =  View::render('logout');

        return View::render('layout.body',[
            'title' => 'Logout',
            'content' => $content,
            'viewHasMenu' => true,
            'viewHasFooter' => false
        ]);

    }

    public function execute() {
        \session_destroy();
        \header('Location: /login', true, 302);
    }

}
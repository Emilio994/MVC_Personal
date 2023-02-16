<?php

namespace Frame\Controllers;
use Frame\Html\View;
use Frame\Http\Request;

class HelpController extends BaseController{

    public function index(Request $request) {   

        $content =  View::render('help');

        return View::render('layout.body',[
            'title' => 'Help',
            'content' => $content,
            'viewHasMenu' => true,
            'viewHasFooter' => false
        ]);

    }

}
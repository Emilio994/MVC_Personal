<?php

namespace Frame\Controllers;
use Frame\Html\View;
use Frame\Http\Request;
use Frame\Models\Parkings;

class ParkingController extends BaseController{

    public function index(Request $request) {   

        $content =  View::render('parking-list');

        return View::render('layout.body',[
            'title' => 'Parking List',
            'content' => $content,
            'viewHasMenu' => true,
            'viewHasFooter' => false
        ]);

    }

    public function list(Request $request) {

        $parkings = new Parkings();
        $list = $parkings->all();
        return json_encode([
            'parkings' => $list
        ]);
        
    }

    public function show(Request $request) {   

        if(!isset($request->params['id'])) header('Location: /');

        $content =  View::render('single-parking');

        return View::render('layout.body',[
            'title' => $request->params['id'],
            'content' => $content,
            'viewHasMenu' => true,
            'viewHasFooter' => false
        ]);

    }

}
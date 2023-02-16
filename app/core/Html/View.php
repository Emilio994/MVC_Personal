<?php

namespace Frame\Html;

class View {

    
    public static function render($templatePath,$data=[])
    {   
       $realPath = \sanitizeViewPath($templatePath) . '.php';

       return self::load($realPath,$data);
    }

    private static function load($templatePath,$data) {
        
        // Start buffering and passa data
        \ob_start();
        \extract($data);
        require_once(VIEWS . $templatePath);

        // Get rendered page with data
        return \ob_get_clean();

    }

    

}
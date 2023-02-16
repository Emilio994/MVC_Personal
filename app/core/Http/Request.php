<?php

namespace Frame\Http;

class Request {

    public $url;
    public $verb;
    public $params;

    public function __construct()
    {
        $this->verb = $_SERVER['REQUEST_METHOD'];
        $this->params = $GLOBALS["_{$this->verb}"];
        $this->url = \trim(strtok($_SERVER['REQUEST_URI'], '?'));
    }

}
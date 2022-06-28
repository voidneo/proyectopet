<?php

class Api extends Controller {

    public function __construct() {
        header('Content-type: application/json; charset=utf-8');
    }

    public function index($data = []) {
        $this->load_controller("api/api-object");
        $this->load_controller("router");
        $router = new Router;
        $router->route($data, "api/");
    }
}

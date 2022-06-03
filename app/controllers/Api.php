<?php

class Api extends Controller {

    public function __construct() {
        header('Content-type: application/json; charset=utf-8');
    }

    public function index($data = []) {
        $this->load_controller("api/ApiObject");
        $this->load_controller("Router");
        $router = new Router;
        $router->route($data, "api/");
    }
}

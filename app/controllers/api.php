<?php

class Api extends Controller {

    public function index($data = []) {
        $this->load_controller("api/api-object");
        $this->load_controller("router");
        $router = new Router;
        $router->route($data, "api/");
    }
}

<?php

class Router extends Controller {

    // TODO: create controller to show error pages
    public function route($data = [], $include_folder = "", $controller = "Home", $method = "index") {
        if(isset($data[0]) && file_exists("../app/controllers/" . $include_folder . $data[0] . ".php")) {
            $controller = $data[0];
            unset($data[0]);
        } else {
            $include_folder = "";
        }

        $this->load_controller($include_folder . $controller);
        $controller = new $controller;

        if(isset($data[1]) && method_exists($controller, $data[1])) {
            $method = $data[1];
            unset($data[1]);
        }

        $arguments = $data ? array_values($data) : [];

        call_user_func_array([$controller, $method], [$arguments]);
    }
}

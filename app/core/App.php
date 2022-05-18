<?php

class App {

    protected $controller = "home";
    protected $method = "index";
    protected $arguments = [];

    public function __construct() {
        $url = $this->parse_url();

        if(isset($url[0]) && file_exists("../app/controllers/" . $url[0] . ".php")) {
            $this->controller = $url[0];
            unset($url[0]);
        }

        require_once("../app/controllers/" . $this->controller . ".php");
        $this->controller = new $this->controller;

        if(isset($url[1]) && method_exists($this->controller, $url[1])) {
            $this->method = $url[1];
            unset($url[1]);
        }

        $this->arguments = $url ? array_values($url) : [];

        call_user_func_array([$this->controller, $this->method], [$this->arguments]);

        /*try {
            call_user_func_array([$this->controller, $this->method], [$this->arguments]);
        } catch(TypeError $err) {
            $this->controller->home([$method, $arguments]);
        }*/
    }

    public function parse_url() {
        if(isset($_GET["url"])) {
            return explode("/", filter_var(rtrim($_GET["url"], "/"), FILTER_SANITIZE_URL));
        } else {
            return [];
        }
    }

}

?>
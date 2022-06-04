<?php

class App extends Controller {

    public function __construct() {
        $this->load_controller("Router");
        $router = new Router;
        $router->route($this->parse_url());
    }

    public function parse_url() {
        if(isset($_GET["url"])) {
            return explode("/", filter_var(rtrim($_GET["url"], "/"), FILTER_SANITIZE_URL));
        }
        return [];
    }

}

?>
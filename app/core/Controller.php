<?php

class Controller {

    public function load_model($model) {
        require_once("../app/models/" . $model . ".php");
        return new $model;
    }

    public function load_view($view, $data = []) {
        require_once("../app/views/" . $view . ".php");
    }

}

?>
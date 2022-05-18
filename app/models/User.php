<?php

class User {
    protected $name;
    protected $ci;

    public function load($ci) {
        $this->name = "Nombre";
        $this->ci = "12345678";
    }

    public function get_name() {
        return $this->name;
    }

    public function get_ci() {
        return $this->ci;
    }
}

?>
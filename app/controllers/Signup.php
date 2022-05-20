<?php

class Signup extends Controller {
    protected $model;
    protected $view;

    public function index($data = []) {
        if(isset($_SESSION['token'])) {
            header("Location: ./");
        }

        $_SESSION['security_hash'] = hash('sha1', random_bytes(8));

        $data["title"] = "Registrarse";
        $data["security_hash"] = $_SESSION['security_hash'];

        $this->load_view("SignUpPage", $data);
    }
}

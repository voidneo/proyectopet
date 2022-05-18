<?php

class Login extends Controller {
    protected $model;
    protected $view;

    public function index($data = []) {
        if(!isset($_SESSION['token'])) {

            $_SESSION['security_hash'] = hash('sha1', random_bytes(8));

            $data["title"] = "Polo tecnologico - Iniciar sesion";
            $data["hash"] = $_SESSION['security_hash'];

            $this->load_view("LoginPage", $data);
        } else {
            header("Location: ./");
        }
    }
}

?>
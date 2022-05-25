<?php

class Api extends Controller {

    private const STATUS_OK             = 200;
    private const STATUS_CREATED        = 201;
    private const STATUS_ACCEPTED       = 202;
    private const STATUS_NO_CONTENT     = 204;
    private const STATUS_BAD_REQUEST    = 400;
    private const STATUS_UNAUTHORIZED   = 401;
    private const STATUS_FORBIDDEN      = 403;
    private const STATUS_NOT_FOUND      = 404;
    private const INTERNAL_SERVER_ERROR = 500;
    
        public function __construct() {
            header('Content-type: application/json; charset=utf-8');
        }

    private static function exist($keys = [], $method = "GET") {
        if($method == "GET") {
            $method = $_GET;
        }
        else if($method == "POST") {
            $method = $_POST;
        }

        foreach($keys as $key) {
            if(!isset($method[$key]))
                return false;
        }

        return true;
    }

    private static function send($status, $error, $content = []) {
        echo json_encode([
            "status"  => $status,
            "error"   => $error,
            "content" => $content
        ]);
    }

    public function index($data = []) {}

    public function authenticate($data = []) {
        if(!self::exist(["cedula", "contrasena", "security_hash"], "POST")) {
            self::send(self::STATUS_BAD_REQUEST, "Missing essential information");
            return;
        }

        if($_POST["security_hash"] != $_SESSION["security_hash"]) {
            self::send(self::STATUS_BAD_REQUEST, "Missing security key");
        }

        $this->load_model("Usuario");
        $user = new Usuario;
        $user = $user->findByCI($_POST["cedula"]);

        if($user) {
            if(password_verify($_POST["contrasena"], $user->getContrasena())) {
                $_SESSION["token"] = hash("sha1", random_bytes(8));
                $_SESSION["cedula"] = $user->getCI();
                
                self::send(self::STATUS_OK, "", ["token" => $_SESSION["token"]]);
                return;
            }

            self::send(self::STATUS_NOT_FOUND, "Contrasena invalida");
            return;
        }
        
        self::send(self::STATUS_NOT_FOUND, "Usuario invalido");
    }

    private function createUser() {
        if(!self::exist(["cedula", "correo", "nombre", "apellido", "contrasena", "security_hash"], "POST")) {
            self::send(self::STATUS_BAD_REQUEST, "Missing essential information");
            return;
        }

        // TODO: validate data
        
        $this->load_model("Usuario");
        $user = new Usuario;

        if($user->create(
            $_POST["cedula"],
            $_POST["nombre"],
            $_POST["apellido"],
            $_POST["correo"],
            password_hash($_POST["contrasena"], PASSWORD_DEFAULT)
        )) {
            $this->authenticate();
            return;
        }

        self::send(self::INTERNAL_SERVER_ERROR, "Internal server error");
    }

    public function user($data = []) {
        if(!isset($data[0])) {
            return;
        }

        switch($data[0]) {
            case "create":
                $this->createUser();
                break;
        }
    }
}

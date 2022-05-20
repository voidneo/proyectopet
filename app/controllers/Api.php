<?php

class Api extends Controller {

    private const STATUS_OK           = 200;
    private const STATUS_CREATED      = 201;
    private const STATUS_ACCEPTED     = 202;
    private const STATUS_NO_CONTENT   = 204;
    private const STATUS_BAD_REQUEST  = 400;
    private const STATUS_UNAUTHORIZED = 401;
    private const STATUS_FORBIDDEN    = 403;
    private const STATUS_NOT_FOUND    = 404;
    
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
        if(!self::exist(["user", "pass"], "POST")) {
            self::send(self::STATUS_BAD_REQUEST, "Missing essential information");
            return;
        }

        $this->load_model("Usuario");
        $user = new Usuario;
        $user = $user->findByCI($_POST["user"]);

        if($user) {
            $pwd_hash = password_hash($_POST["pass"], PASSWORD_DEFAULT);

            if($pwd_hash == $user->getContrasena()) {
                self::send(self::STATUS_OK, "", ["token" => hash("sha1", random_bytes(8))]);
                return;
            } else {
                self::send(self::STATUS_NOT_FOUND, "Contrasena invalida");
                return;
            }

        }
        
        self::send(self::STATUS_NOT_FOUND, "Usuario invalido");
    }

    public function user($data = []) {
        if(!isset($data[0])) {
            return;
        }

        switch($data[0]) {
            case "create":
                echo "yup";
                break;
        }
    }
}

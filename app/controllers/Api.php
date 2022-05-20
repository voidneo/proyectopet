<?php

class Api extends Controller {

    private const STATUS_OK = 200;
    private const STATUS_ACCEPTED = 202;

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

    private static function send($status, $message, $content = []) {
        echo json_encode([
            "status" => $status,
            "message" => $message,
            "content" => $content
        ]);
    }

    public function __construct() {
        header('Content-type: application/json; charset=utf-8');
    }

    public function index($data = []) {}

    public function authenticate($data = []) {
        if(!self::exist(["user", "pass"], "POST")) {
            self::send(self::STATUS_ACCEPTED, "Missing essential information");
            return;
        }


        // TODO: sanitize values
        // TODO: check for usernames with these credentials
        if($_POST["user"] == "admin" && $_POST["pass"] == "1234") {
            self::send(self::STATUS_OK, "success", ["token" => hash("sha1", random_bytes(8))]);
            return;
        }
        
        self::send(self::STATUS_ACCEPTED, "Credentials are invalid");
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

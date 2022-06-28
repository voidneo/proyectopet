<?php

class ApiObject extends Controller {

    protected const STATUS_OK             = 200;
    protected const STATUS_CREATED        = 201;
    protected const STATUS_ACCEPTED       = 202;
    protected const STATUS_NO_CONTENT     = 204;
    protected const STATUS_BAD_REQUEST    = 400;
    protected const STATUS_UNAUTHORIZED   = 401;
    protected const STATUS_FORBIDDEN      = 403;
    protected const STATUS_NOT_FOUND      = 404;
    protected const INTERNAL_SERVER_ERROR = 500;

    protected static function exist($keys = [], $method = "GET") {
        if ($method == "GET") {
            $method = $_GET;
        } else if ($method == "POST") {
            $method = $_POST;
        }

        foreach ($keys as $key) {
            if (!isset($method[$key]))
                return false;
        }

        return true;
    }

    protected static function send($status, $error, $content = []) {
        echo json_encode([
            "status"  => $status,
            "error"   => $error,
            "content" => $content
        ]);
    }

    protected static function isSecurityHashValid($method = "POST") {
        if ($method == "GET") {
            $method = $_GET;
        } else if ($method == "POST") {
            $method = $_POST;
        }

        $matches = $method["security_hash"] == $_SESSION["security_hash"];
        if (!$matches) {
            self::send(self::STATUS_BAD_REQUEST, "Security key mismatch");
        }

        return $matches;
    }
}

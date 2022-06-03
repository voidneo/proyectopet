<?php

class Authenticate extends ApiObject {

    public function index($data = [], $sendResponseOnSuccess = true) {
        if (!self::exist(["cedula", "contrasena", "security_hash"], "POST")) {
            self::send(self::STATUS_BAD_REQUEST, "Missing essential information");
            return;
        }

        if (!self::isSecurityHashValid()) return;

        $this->load_model("Usuario");
        $user = new Usuario;
        $user = $user->findByCI($_POST["cedula"]);

        if ($user) {
            if (password_verify($_POST["contrasena"], $user->getContrasena())) {
                $_SESSION["token"] = hash("sha1", random_bytes(8));
                $_SESSION["cedula"] = $user->getCI();

                if ($sendResponseOnSuccess)
                    self::send(self::STATUS_OK, "", ["token" => $_SESSION["token"]]);
                return;
            }

            self::send(self::STATUS_NOT_FOUND, "Contrasena invalida");
            return;
        }

        self::send(self::STATUS_NOT_FOUND, "Usuario invalido");
    }
}

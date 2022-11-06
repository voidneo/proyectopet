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

                if($user->getValido() == "0") {
                    self::send(self::STATUS_UNAUTHORIZED, "Aun no has sido validado");
                    return;
                }

                $_SESSION["token"]    = hash("sha1", random_bytes(8));
                $_SESSION["id"]       = $user->getId();
                $_SESSION["cedula"]   = $user->getCI();
                $_SESSION["nombre"]   = $user->getNombre();
                $_SESSION["apellido"] = $user->getApellido();
                $_SESSION["correo"]   = $user->getCorreo();
                $_SESSION["rol"]      = $user->getRol();

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

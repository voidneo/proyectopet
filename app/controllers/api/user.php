<?php

// TODO: code read user methods as needed
class User extends ApiObject {

    public function create($data = []) {
        if (!self::exist(["cedula", "correo", "nombre", "apellido", "contrasena", "security_hash"], "POST")) {
            self::send(self::STATUS_BAD_REQUEST, "Missing essential information");
            return;
        }

        // TODO: check for security hash

        // TODO: validate data

        $this->load_model("Usuario");
        $user = new Usuario;

        if ($user->create(
            $_POST["cedula"],
            $_POST["nombre"],
            $_POST["apellido"],
            $_POST["correo"],
            password_hash($_POST["contrasena"], PASSWORD_DEFAULT)
        )) {
            $this->load_controller("api/authenticate");
            $auth = new Authenticate;
            $auth->index([], false);
            self::send(self::STATUS_CREATED, "");
            return;
        }

        self::send(self::INTERNAL_SERVER_ERROR, "Internal server error");
    }

    public function update() {
        if (!self::exist(["id", "cedula", "correo", "nombre", "apellido", "contrasena", "security_hash"], "POST")) {
            self::send(self::STATUS_BAD_REQUEST, "Missing essential information");
            return;
        }

        if (!self::isSecurityHashValid()) return;
        
        // TODO: validate data

        $this->load_model("Usuario");
        $user = new Usuario;
        $user = $user->findById($_POST["id"]);
        $user->setCI($_POST["cedula"]);
        $user->setNombre($_POST["nombre"]);
        $user->setApellido($_POST["apellido"]);
        $user->setCorreo($_POST["correo"]);
        $user->setContrasena(password_hash($_POST["contrasena"], PASSWORD_DEFAULT));
        $user->update();
    }

    public function delete() {
        if (!self::exist(["cedula"], "POST")) {
            self::send(self::STATUS_BAD_REQUEST, "Missing essential information");
            return;
        }

        if (!self::isSecurityHashValid()) return;

        // TODO: validate CI

        $this->load_model("Usuario");
        $user = new Usuario;

        if ($user->delete($_POST["cedula"])) {
            self::send(self::STATUS_NO_CONTENT, "");
            return;
        }

        self::send(self::INTERNAL_SERVER_ERROR, "Internal server error");
    }
}

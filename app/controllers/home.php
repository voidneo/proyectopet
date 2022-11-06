<?php

class Home extends Controller {

    public function index($data = []) {
        //$security_hash = hash("sha1", random_bytes(8));

        // Estas 2 lineas casi me dan un ataque al corazon
        //$_SESSION["security_hash"] = $security_hash;
        //$data["security_hash"]     = $security_hash;

        if(isset($_SESSION["token"])) {
            $this->load_model("Usuario");
            $user = new Usuario;
            $user = $user->findByCI($_SESSION["cedula"]);

            $data["token"] = $_SESSION["token"];
            $data["cedula"] = $user->getCI();
            $data["nombre"] = $user->getNombre();
            $data["apellido"] = $user->getApellido();
        }

        $this->load_view("HomePage", $data);
    }

}

?>
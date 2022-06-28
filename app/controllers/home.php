<?php

class Home extends Controller {

    public function index($data = []) {
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
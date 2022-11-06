<?php

class SendMail extends Controller {

    public function index($data = []) {
        $this->load_controller("api/api-object");

        if(ApiObject::exist(["name", "surname", "email", "subject", "message"], "POST")) {

            // FIXME: Esto se tiene que configurar en la pc donde se haga la demostracion

            $msg  = "Mensaje recibido desde el formulario de contacto del sitio web del polo educativo tecnologico Durazno.\n\n";
            $msg .= "Mensaje de " . $_POST["name"] . " " . $_POST["surname"] . ".\n";
            $msg .= "Correo: " . $_POST["email"] . "\n";
            $msg .= "Asunto: " . $_POST["subject"] . "\n";
            $msg .= "Mensaje:\n\n" . $_POST["message"];

            $sub = "PETD: " . $_POST["subject"];

            $headers  = 'MIME-Version: 1.0' . PHP_EOL;
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . PHP_EOL;
            $headers .= 'From: Me <me@email.com>' . PHP_EOL;

            $s = mail("nicolas.mg.uy@gmail.com", $sub, $msg, $headers);

            if($s) header("Location: ./contacto?success=1");
            else   header("Location: ./contacto?success=0");
        }

        echo "not nice";
    }

}

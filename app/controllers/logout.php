<?php

class Logout extends Controller {

    public function index($args = []) {

        // TODO: implement cross-site attacks safety measure

        $redirect = "./";
        
        if(isset($_GET["redirect"])) {
            $redirect = $redirect . ltrim($_GET["redirect"], "/");
        }
    
        if(isset($_SESSION["token"])) {
            session_unset();
            session_destroy();
        }

        header("Location: " . $redirect);
    }

}

?>
<?php

class test extends Controller {
    public function index($data = []) {
        $security_hash = hash("sha1", random_bytes(8));

        $_SESSION["security_hash"] = $security_hash;
        $data["security_hash"]     = $security_hash;

        echo "<fieldset><legend>Panel de control de categorias</legend>";
        $this->load_view("components/CategoryList", $data);
        echo "</fieldset><fieldset><legend>Editor de articulos</legend>";
        $this->load_view("components/CategoryChooser", $data);
        echo "</fieldset>";
    }
}
<?php

class Contacto extends Controller {

    public function index($data = []) {
        $this->load_view("ContactPage", $data);
    }

}

?>
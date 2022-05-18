<?php

class Home extends Controller {

    public function index($data = []) {
        if(isset($_SESSION["token"])) {
            $data["token"] = $_SESSION["token"];
            $data["user"] = $this->load_model("User");
            $data["user"]->load("");
        }

        $this->load_view("HomePage", $data);
    }

}

?>
<?php

class Articulo extends Controller {

    public function index($data = []) {

        
        if(isset($_GET["id"])) {
            $data["article_id"]    = $_GET["id"];
        }
        
        if(isset($_SESSION["rol"]) && $_SESSION["rol"] != "e") {
            $_SESSION['security_hash'] = hash('sha1', random_bytes(8));
            $data["security_hash"]     = $_SESSION['security_hash'];
            
            $this->load_view("ArticleEditPage", $data);
        } else {
            header("location:./");
        }

    }

}

?>
<?php

class Llamados extends Controller {

    public function index($data = []) {

        // FIXME: for some reason the last forward slash doesn't get removed and causes scripts to malfunction

        $year  = isset($data[0]) ? $data[0] : false;
        $month = isset($data[1]) ? $data[1] : false;
        $day   = isset($data[2]) ? $data[2] : false;
        $id    = isset($data[3]) ? $data[3] : false;


        $this->load_model("Categoria");
        $cat = new Categoria;
        $cat = $cat->findByNombre("ofertas laborales");

        if(is_null($cat->getId())) {
            echo "La categoria ofertas laborales no fue creada";
            return;
        }

        // If category exists, store its id in $data for the view
        $data["category_id"] = $cat->getId();

        $this->load_model("Articulo");
        $art = new Articulo;

        // If an ID was provided, load article straight up
        if($id) {
            $art = $art->findById($id);
            // If the article exists, load the article view
            if($art) {
                $data["article"] = $art->findById($id);
                $this->load_view("AtriclePage", $data);
                return;
            }

            // Else display error view
            echo "Article not found";
            return;
        }

        $data["year"]  = $year;
        $data["month"] = $month;
        $data["day"]   = $day;
            
        $this->loadNewsSearchPage($data);
    }

    private function loadNewsSearchPage($data) {
        $security_hash = hash("sha1", random_bytes(8));

        $_SESSION["security_hash"] = $security_hash;
        $data["security_hash"]     = $security_hash;
        $data["page_title"]        = "Oferta laboral";

        echo "<script type='text/javascript'>const BASE_URL = window.location.href.substr(0, 36);</script>";
        $this->load_view("ArticleSearchPage", $data);
    }

}

?>
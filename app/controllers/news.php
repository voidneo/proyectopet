<?php

class News extends Controller {

    public function index($args = []) {
        $year  = isset($args[0]) ? $args[0] : false;
        $month = isset($args[1]) ? $args[1] : "__";
        $day   = isset($args[2]) ? $args[2] : "__";
        $id    = isset($args[3]) ? $args[3] : false;

        $this->load_model("Categoria");
        $cat = new Categoria;
        $cat = $cat->findByNombre("noticias");

        if(is_null($cat->getId())) {
            echo "La categoria noticias no fue creada";
            return;
        }

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

        // Else, if at least the year was given, search by date
        if($year) {
            $data["news"] = $art->searchByCategoryAndDate($cat->getId(), $year, $month, $day);
            $this->loadNewsSearchPage($data);
            return;
        }

        // else, search without date
        $data["news"] = $art->searchByCategoria($cat->getId());
        $this->loadNewsSearchPage($data);
    }

    private function loadNewsSearchPage($data) {
        if(count($data["news"]) == 0) {
            echo "No se encontraron articulos";
            return;
        }

        $data["base_url"] = explode("news", strtolower($_SERVER['REQUEST_URI']))[0] . "news/";
        $this->load_view("NewsSearchPage", $data);
    }

}

?>
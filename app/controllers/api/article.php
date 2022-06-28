<?php
class Article extends ApiObject {

    public function create() {
        if (!self::exist(["titulo", "cuerpo", "id_categoria", "security_hash"], "POST")) {
            self::send(self::STATUS_BAD_REQUEST, "Missing essential information");
            return;
        }

        if (!self::isSecurityHashValid()) return;

        // TODO: validate data

        $this->load_model("Categoria");
        $this->load_model("Articulo");

        $cat = new Categoria;
        $cat = $cat->findById($_POST["id_categoria"]);

        if(!$cat) {
            self::send(self::STATUS_NOT_FOUND, "Invalid category");
            return;
        }

        $art = new Articulo;

        if ( $art->create($_POST["titulo"], $_POST["cuerpo"], $_POST["id_categoria"])) {
            self::send(self::STATUS_CREATED, "");
            return;
        }

        self::send(self::INTERNAL_SERVER_ERROR, "Internal server error");
    }

    public function read() {
        if (!self::exist(["security_hash"])) {
            self::send(self::STATUS_BAD_REQUEST, "Missing essential information");
            return;
        }

        if (!self::isSecurityHashValid("GET")) return;

        $this->load_model("Articulo");
        $art = new Articulo;

        if (self::exist(["id"])) {
            $art = $art->findById($_GET["id"]);

            if($art) {
                $json = json_encode([
                    "id"           => $art->getId(),
                    "titulo"       => $art->getTitulo(),
                    "cuerpo"       => $art->getCuerpo(),
                    "url_imagen"   => $art->getUrlImagen(),
                    "id_categoria" => $art->getIdCategoria(),
                    "fecha"        => $art->getFecha()
                ]);

                self::send(self::STATUS_OK, "", $json);
                return;
            }

            self::send(self::STATUS_NOT_FOUND, "Article not found");
            return;
        }

        $search_query = "";
        $date         = ["year"   => "____", "month"  => "__", "day" => "__"];
        $pagination   = ["page"   => 1,      "length" => 5];
        $sort         = ["column" => "id",   "order"  => "ASC"];

        if (self::exist(["query"])) {
            $search_query = $_GET["query"];
        }

        if(self::exist(["year"])) {
            $date["year"] = $_GET["year"];
        }

        if(self::exist(["month"])) {
            $month = strlen($_GET["month"]) == 1 ? "0" . $_GET["month"] : $_GET["month"];
            $date["month"] = $month;
        }

        if(self::exist(["day"])) {
            $day = strlen($_GET["day"]) == 1 ? "0" . $_GET["day"] : $_GET["day"];
            $date["day"] = $day;
        }

        if (self::exist(["page"])) {
            $pagination["page"] = $_GET["page"];
        }

        if (self::exist(["page_length"])) {
            $pagination["length"] = $_GET["page_length"];
        }

        if (self::exist(["sort_column"])) {
            $sort["column"] = $_GET["sort_column"];
        }

        if (self::exist(["sort_order"])) {
            $sort["order"] = $_GET["sort_order"];
        }

        $art = $art->getAll($search_query, $date, $sort, $pagination);

        if($art) {
            self::send(self::STATUS_OK, "", $art);
            return;
        }

        self::send(self::INTERNAL_SERVER_ERROR, "Internal server error");
    }

    public function update() {
        if (!self::exist(["id", "titulo", "cuerpo", "id_categoria", "security_hash"], "POST")) {
            self::send(self::STATUS_BAD_REQUEST, "Missing essential information");
            return;
        }

        if (!self::isSecurityHashValid()) return;

        // TODO: validate data

        $this->load_model("Categoria");
        $this->load_model("Articulo");

        $cat = new Categoria;
        $cat = $cat->findById($_POST["id_categoria"]);

        if(!$cat) {
            self::send(self::STATUS_NOT_FOUND, "Invalid category");
            return;
        }

        $art = new Articulo;
        $art = $art->findById($_POST["id"]);

        if ($art) {
            $art->setTitulo($_POST["titulo"]);
            $art->setCuerpo($_POST["cuerpo"]);
            $art->setIdCategoria($_POST["id_categoria"]);

            if ($art->update()) {
                self::send(self::STATUS_NO_CONTENT, "");
                return;
            }
        }

        self::send(self::INTERNAL_SERVER_ERROR, "Internal server error");
    }

    public function delete() {
        if (!self::exist(["id", "security_hash"], "POST")) {
            self::send(self::STATUS_BAD_REQUEST, "Missing essential information");
            return;
        }

        if (!self::isSecurityHashValid()) return;

        $this->load_model("Articulo");
        $art = new Articulo;

        if ($art->delete(($_POST["id"]))) {
            self::send(self::STATUS_NO_CONTENT, "");
            return;
        }

        self::send(self::INTERNAL_SERVER_ERROR, "Internal server error");
    }
}

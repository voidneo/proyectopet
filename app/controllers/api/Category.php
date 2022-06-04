<?php

// TODO: code CRUD for Category API
class Category extends ApiObject {

    public function create() {
        if (!self::exist(["nombre", "security_hash"], "POST")) {
            self::send(self::STATUS_BAD_REQUEST, "Missing essential information");
            return;
        }

        if (!self::isSecurityHashValid()) return;

        // TODO: validate data

        $this->load_model("Categoria");
        $cat = new Categoria;

        if ($cat->create(($_POST["nombre"]))) {
            self::send(self::STATUS_CREATED, "");
            return;
        }

        self::send(self::INTERNAL_SERVER_ERROR, "Internal server error");
    }

    public function read() {
        /*if (!self::exist(["security_hash"])) {
            self::send(self::STATUS_BAD_REQUEST, "Missing essential information");
            return;
        }

        if (!self::isSecurityHashValid()) return;
*/
        $pagination = ["page" => 1, "rows_per_page" => 5];
        $orderby    = ["column" => "id", "order" => "ASC"];

        //var_dump($_GET);

        if (self::exist(["pagination"])) {
            $pag = json_decode($_GET["pagination"]);
            $pagination = [
                "page"          => $pag->page,
                "rows_per_page" => $pag->rows_per_page
            ];
        }

        if (self::exist(["order"])) {
            $ord = json_decode($_GET["order"]);
            $orderby    = [
                "column" => $ord->column,
                "order"  => $ord->order
            ];
        }

        $this->load_model("Categoria");
        $cat = new Categoria;
        $cat = $cat->getAll($pagination, $orderby);

        if($cat) {
            self::send(self::STATUS_OK, "", $cat);
            return;
        }

        self::send(self::INTERNAL_SERVER_ERROR, "Internal server error");
    }

    public function update() {
        if (!self::exist(["id", "nombre", "security_hash"], "POST")) {
            self::send(self::STATUS_BAD_REQUEST, "Missing essential information");
            return;
        }

        if (!self::isSecurityHashValid()) return;

        // TODO: validate data

        $this->load_model("Categoria");
        $cat = new Categoria;
        $cat = $cat->findById($_POST["id"]);

        if ($cat) {
            $cat->setNombre($_POST["nombre"]);

            if ($cat->update()) {
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

        $this->load_model("Categoria");
        $cat = new Categoria;

        if ($cat->delete(($_POST["id"]))) {
            self::send(self::STATUS_NO_CONTENT, "");
            return;
        }

        self::send(self::INTERNAL_SERVER_ERROR, "Internal server error");
    }
}

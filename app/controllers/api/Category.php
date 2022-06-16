<?php

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
        if (!self::exist(["security_hash"])) {
            self::send(self::STATUS_BAD_REQUEST, "Missing essential information");
            return;
        }

        if (!self::isSecurityHashValid("GET")) return;

        $search_query = "";
        $pagination   = ["page" => 1, "length" => 5];
        $sort         = ["column" => "id", "order" => "ASC"];

        if (self::exist(["query"])) {
            $search_query = $_GET["query"];
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

        $this->load_model("Categoria");
        $cat = new Categoria;
        $cat = $cat->getAll($search_query, $sort, $pagination);

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

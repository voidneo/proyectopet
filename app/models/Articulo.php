<?php

class Articulo extends Model {
    private const CREATE_QUERY                  = "INSERT INTO articulos(titulo, cuerpo, url_imagen, id_categoria) VALUES(?,?,?,?)";
    private const FIND_BY_ID_QUERY              = "SELECT * FROM articulos WHERE id=:id";
    private const SEARCH_BY_TITLE_PARTIAL_QUERY = "SELECT * FROM articulos WHERE titulo LIKE :title";
    private const SEARCH_BY_CATEGORY_QUERY      = "SELECT * FROM articulos WHERE id_categoria=:id";
    private const SEARCH_BY_DATE_QUERY          = "SELECT * FROM articulos WHERE fecha=:creationdate";
    private const SEARCHS_BY_CAT_AND_DATE_QUERY = "SELECT * FROM articulos WHERE id_categoria=:id AND fecha LIKE :yyyymmdd";
    private const GET_ALL_QUERY                 = "SELECT * FROM articulos";
    private const UPDATE_QUERY                  = "UPDATE articulos SET titulo=?, cuerpo=?, url_imagen=?, id_categoria=?, fecha=? WHERE id=?";
    private const DELETE_BY_ID_QUERY            = "DELETE FROM articulos WHERE id=?";
    // TODO: add result pagination
    // TODO: add support to clause ORDER BY

    private $id           = null;
    private $titulo       = null;
    private $cuerpo       = null;
    private $url_imagen   = null;
    private $id_categoria = null;
    private $fecha        = null;

    private static function new($id, $title, $body, $img_url, $id_cat, $date) {
        $obj               = new Articulo;
        $obj->id           = $id;
        $obj->titulo       = $title;
        $obj->cuerpo       = $body;
        $obj->url_imagen   = $img_url;
        $obj->id_categoria = $id_cat;
        $obj->fecha        = $date;
        return $obj;
    }

    public function create($title, $body, $cat_id, $img_url = "") {
        $this->connect();

        $stmt = $this->pdo->prepare(self::CREATE_QUERY);
        return $stmt->execute([$title, $body, $img_url, $cat_id]);
    }

    public function findById($id) {
        $this->connect();

        $stmt = $this->pdo->prepare(self::FIND_BY_ID_QUERY);
        $stmt->execute([":id" => $id]);
        $row = $stmt->fetch();
        return self::new(
            $row["id"],
            $row["titulo"],
            $row["cuerpo"],
            $row["url_imagen"],
            $row["id_categoria"],
            $row["fecha"]
        );
    }

    public function searchByTituloPartial($title) {
        $this->connect();

        $stmt = $this->pdo->prepare(self::SEARCH_BY_TITLE_PARTIAL_QUERY);
        $stmt->execute([":title" => "%$title%"]);
        $rslt = $stmt->fetchAll();
        $objs = [];

        foreach($rslt as $row) {
            array_push($objs, self::new(
                $row["id"],
                $row["titulo"],
                $row["cuerpo"],
                $row["url_imagen"],
                $row["id_categoria"],
                $row["fecha"]
            ));
        }

        return $objs;
    }

    public function searchByCategoria($cat) {
        $this->connect();

        $stmt = $this->pdo->prepare(self::SEARCH_BY_CATEGORY_QUERY);
        $stmt->execute([":id" => "$cat"]);
        $rslt = $stmt->fetchAll();
        $objs = [];

        foreach($rslt as $row) {
            array_push($objs, self::new(
                $row["id"],
                $row["titulo"],
                $row["cuerpo"],
                $row["url_imagen"],
                $row["id_categoria"],
                $row["fecha"]
            ));
        }

        return $objs;
    }

    public function searchByCategoryAndDate($cat, $year, $month = "__", $day = "__") {
        $this->connect();

        $stmt = $this->pdo->prepare(self::SEARCHS_BY_CAT_AND_DATE_QUERY);
        $stmt->execute([
            ":id" => "$cat",
            ":yyyymmdd" => "$year-$month-$day%"
        ]);
        $rslt = $stmt->fetchAll();
        $objs = [];

        foreach($rslt as $row) {
            array_push($objs, self::new(
                $row["id"],
                $row["titulo"],
                $row["cuerpo"],
                $row["url_imagen"],
                $row["id_categoria"],
                $row["fecha"]
            ));
        }

        return $objs;
    }

    public function getAll($limit = ["offset" => 0, "row_count" => 0]) {
        $this->connect();
        $pagination = "";
        $offset     = $limit["offset"];
        $count      = $limit["row_count"];

        if($offset != 0 && $count != 0) {
            $pagination = " LIMIT $offset, $count";
        }
        else if($count != 0) {
            $pagination = " LIMIT $count";
        }

        $stmt = $this->pdo->prepare(self::GET_ALL_QUERY . $pagination);
        $stmt->execute([]);
        $rslt = $stmt->fetchAll();
        $objs = [];

        foreach($rslt as $row) {
            array_push($objs, self::new(
                $row["id"],
                $row["titulo"],
                $row["cuerpo"],
                $row["url_imagen"],
                $row["id_categoria"],
                $row["fecha"]
            ));
        }

        return $objs;
    }

    public function update() {
        $this->connect();

        if (is_null($this->id)) {
            throw new Exception("Missing id on the row to update");
        }

        $stmt = $this->pdo->prepare(self::UPDATE_QUERY);
        return $stmt->execute([
            $this->titulo,
            $this->cuerpo,
            $this->url_imagen,
            $this->id_categoria,
            $this->fecha,
            $this->id
        ]);
    }

    public function delete($id) {
        $this->connect();

        $stmt = $this->pdo->prepare(self::DELETE_BY_ID_QUERY);
        return $stmt->execute([$id]);
    }

    public function getId() {
        return $this->id;
    }

    public function getTitulo() {
        return $this->titulo;
    }

    public function setTitulo($title) {
        $this->titulo = $title;
    }

    public function getCuerpo() {
        return $this->cuerpo;
    }

    public function setCuerpo($body) {
        $this->cuerpo = $body;
    }

    public function getUrlImagen() {
        return $this->url_imagen;
    }

    public function setUrlImagen($img_url) {
        $this->url_imagen = $img_url;
    }

    public function getIdCategoria() {
        return $this->id_categoria;
    }

    public function setIdCategoria($cat_id) {
        $this->id_categoria = $cat_id;
    }

    public function getFecha() {
        return $this->fecha;
    }
}

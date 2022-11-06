<?php

class Articulo extends Model {
    private const CREATE_QUERY                  = "INSERT INTO articulos(titulo, cuerpo, url_imagen, id_categoria) VALUES(?,?,?,?)";
    private const FIND_BY_ID_QUERY              = "SELECT * FROM articulos WHERE id=:id";
    private const SEARCH_BY_TITLE_PARTIAL_QUERY = "SELECT * FROM articulos WHERE titulo LIKE :title";
    private const GET_ROW_COUNT_QUERY           = "SELECT COUNT(id) AS row_count FROM articulos";
    private const SEARCH_BY_CATEGORY_QUERY      = "SELECT * FROM articulos WHERE id_categoria=:id";
    private const SEARCH_BY_DATE_QUERY          = "SELECT * FROM articulos WHERE fecha=:creationdate";
    private const SEARCHS_BY_CAT_AND_DATE_QUERY = "SELECT * FROM articulos WHERE id_categoria=:id AND fecha LIKE :yyyymmdd";
    private const GET_ALL_QUERY                 = "SELECT * FROM articulos";
    private const UPDATE_QUERY                  = "UPDATE articulos SET titulo=?, cuerpo=?, url_imagen=?, id_categoria=?, fecha=? WHERE id=?";
    private const DELETE_BY_ID_QUERY            = "DELETE FROM articulos WHERE id=?";

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
        if($row)
            return self::new(
                $row["id"],
                $row["titulo"],
                $row["cuerpo"],
                $row["url_imagen"],
                $row["id_categoria"],
                $row["fecha"]
            );
        return new Articulo;
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

    public function getAll($search_query = "", $category = "", $date = ["year" => "____", "month" => "__", "day" => "__"], $sort = "", $page = ["page" => 1, "length" => 10]) {
        $this->connect();
        $where_clause = "";
        $order_clause = "";
        $limit_clause = "";
        $values       = [];

        // FIXME: order and limit clauses are vulnerable to SQL injections

        if (!empty($search_query)) {
            $where_clause = " WHERE titulo LIKE :search_query";
            $values["search_query"] = "%$search_query%";
        }

        if(!empty($category)) {
            if(empty($where_clause)) {
                $where_clause = " WHERE id_categoria = $category";
            } else {
                $where_clause = "$where_clause AND id_categoria = $category";
            }
        }

        // If either the year, month or day isn't a wildcard
        if(
           $date["year"]  != "____"
        || $date["month"] != "__"
        || $date["day"]   != "__"
        ) {
            if(empty($where_clause)) {
                $where_clause = " WHERE fecha LIKE :yyyymmdd";
            } else {
                $where_clause = "$where_clause AND fecha LIKE :yyyymmdd";
            }
            $values["yyyymmdd"] = $date["year"] . "-" . $date["month"] . "-" . $date["day"] . " %";
        }

        if(!empty($sort)) {     
            $column = $sort["column"];
            $order = $sort["order"];
            $order_clause = " ORDER BY $column $order";
        }

        if (!empty($page)) {
            $length = intval($page["length"]);
            $offset = ($page["page"] - 1) * $length;

            if ($offset != 0) {
                $limit_clause = " LIMIT $offset, $length";
            } else {
                $limit_clause = " LIMIT $length";
            }
        }

        $stmt = $this->pdo->prepare(self::GET_ALL_QUERY . $where_clause . $order_clause . $limit_clause);
        $stmt->execute($values);
        $rslt = $stmt->fetchAll();

        $filtered_results = [];
        foreach ($rslt as $row) {
            array_push($filtered_results, [
                "id"           => $row["id"],
                "titulo"       => $row["titulo"],
                "cuerpo"       => $row["cuerpo"],
                "url_imagen"   => $row["url_imagen"],
                "id_categoria" => $row["id_categoria"],
                "fecha"        => $row["fecha"]
            ]);
        }

        $stmt = $this->pdo->prepare(self::GET_ROW_COUNT_QUERY . $where_clause . $order_clause);
        $stmt->execute($values);
        $rslt = $stmt->fetch();

        return [
            "page"       => $page["page"],
            "total_rows" => $rslt["row_count"],
            "results"    => $filtered_results
        ];
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

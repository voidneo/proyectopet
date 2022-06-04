<?php

class Categoria extends Model {
    private const CREATE_QUERY        = "INSERT INTO categorias(nombre) VALUES(?)";
    private const FIND_BY_ID_QUERY    = "SELECT * FROM categorias WHERE id=:id";
    private const FIND_BY_NAME_QUERY  = "SELECT * FROM categorias WHERE nombre=:name";
    private const GET_ROW_COUNT_QUERY = "SELECT COUNT(id) AS row_count FROM categorias";
    private const GET_ALL_QUERY       = "SELECT * FROM categorias";
    private const UPDATE_QUERY        = "UPDATE categorias SET nombre=? WHERE id=?";
    private const DELETE_BY_ID_QUERY  = "DELETE FROM categorias WHERE id=?";

    // TODO: engineer a query builder

    private $id     = null;
    private $nombre = null;

    private static function new($id, $name) {
        $obj         = new Categoria;
        $obj->id     = $id;
        $obj->nombre = $name;
        return $obj;
    }

    public function create($nombre) {
        $this->connect();

        $stmt = $this->pdo->prepare(self::CREATE_QUERY);
        return $stmt->execute([$nombre]);
    }

    public function findById($id) {
        $this->connect();

        $stmt = $this->pdo->prepare(self::FIND_BY_ID_QUERY);
        $stmt->execute([":id" => $id]);
        $row = $stmt->fetch();
        return self::new($row["id"], $row["nombre"]);
    }

    public function getRowCount() {
        $this->connect();
        $stmt = $this->pdo->prepare(self::GET_ROW_COUNT_QUERY);
        $stmt->execute([]);
        $rslt = $stmt->fetch();

        return $rslt["row_count"];
    }

    public function getAll($limit = ["page" => 1, "rows_per_page" => 5], $orderby = ["column" => "id", "order" => "ASC"]) {
        $this->connect();
        $pagination = "";

        $count      = $limit["rows_per_page"];
        $offset     = ($limit["page"] - 1) * $count;

        if($offset != 0) {
            $pagination = " LIMIT $offset, $count";
        }
        else {
            $pagination = " LIMIT $count";
        }

        $order = " ORDER BY " . $orderby["column"] . " " . $orderby["order"] . " ";

        $stmt = $this->pdo->prepare(self::GET_ALL_QUERY . $order . $pagination);
        $stmt->execute([]);
        $rslt = $stmt->fetchAll();

        $filtered_results = [];
        foreach($rslt as $row) {
            array_push($filtered_results, [
                "id" => $row["id"],
                "nombre" => $row["nombre"]
            ]);
        }

        return [
            "page" => $limit["page"],
            "rows" => count($rslt),
            "total_rows" => $this->getRowCount(),
            "results" => $filtered_results
        ];
    }

    public function findByNombre($name) {
        $this->connect();

        $stmt = $this->pdo->prepare(self::FIND_BY_NAME_QUERY);
        $stmt->execute([":name" => $name]);
        $row = $stmt->fetch();
        return self::new($row["id"], $row["nombre"]);
    }

    public function update() {
        $this->connect();

        if (is_null($this->id)) {
            throw new Exception("Missing id on the row to update");
        }

        $stmt = $this->pdo->prepare(self::UPDATE_QUERY);
        return $stmt->execute([$this->nombre, $this->id]);
    }

    public function delete($id) {
        $this->connect();

        $stmt = $this->pdo->prepare(self::DELETE_BY_ID_QUERY);
        return $stmt->execute([$id]);
    }

    public function getId() {
        return $this->id;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }
}

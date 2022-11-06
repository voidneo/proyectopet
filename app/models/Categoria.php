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

    public function getAll($search_query = "", $sort = "", $page = "") {
        $this->connect();
        $where_clause = "";
        $order_clause = "";
        $limit_clause = "";
        $values       = [];

        // FIXME: order and limit clauses are vulnerable to SQL injections

        if (!empty($search_query)) {
            $where_clause = " WHERE nombre LIKE :search_query";
            $values["search_query"] = "%$search_query%";
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
                "id"     => $row["id"],
                "nombre" => $row["nombre"]
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

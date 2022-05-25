<?php

class Categoria extends Model {
    private const CREATE_QUERY       = "INSERT INTO categorias(nombre) VALUES(?)";
    private const FIND_BY_ID_QUERY   = "SELECT * FROM categorias WHERE id=:id";
    private const FIND_BY_NAME_QUERY = "SELECT * FROM categorias WHERE nombre=:name";
    private const GET_ALL_QUERY      = "SELECT * FROM categorias";
    private const UPDATE_QUERY       = "UPDATE categorias SET nombre=? WHERE id=?";
    private const DELETE_BY_ID_QUERY = "DELETE FROM categorias WHERE id=?";

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
                $row["nombre"]
            ));
        }

        return $objs;
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

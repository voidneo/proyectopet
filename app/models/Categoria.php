<?php

class Categoria extends Model {
    private const CREATE_QUERY = "INSERT INTO categorias(nombre) VALUES(?)";
    private const FIND_BY_ID_QUERY = "SELECT * FROM categorias WHERE id=:id";
    private const FIND_BY_NAME_QUERY = "SELECT * FROM categorias WHERE nombre=:name";
    private const UPDATE_QUERY = "UPDATE categorias SET nombre=? WHERE id=?";

    private $id = null;
    private $nombre = null;

    private static function new($id, $name) {
        $cat = new Categoria;
        $cat->id = $id;
        $cat->nombre = $name;
        return $cat;
    }

    public function create($nombre) {
        $this->connect();

        if (is_null($nombre)) {
            throw new Exception("Name cannot be null");
        }

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

    public function findByNombre($name) {
        $this->connect();

        $stmt = $this->pdo->prepare(self::FIND_BY_NAME_QUERY);
        $stmt->execute([":name" => $name]);
        $row = $stmt->fetch();
        return self::new($row["id"], $row["nombre"]);
    }

    public function update() {
        $this->connect();

        if (!isset($this->id)) {
            throw new Exception("Missing id on the row to update");
        }

        $stmt = $this->pdo->prepare(self::UPDATE_QUERY);
        return $stmt->execute([$this->nombre, $this->id]);
    }

    public function getID() {
        return $this->id;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }
}

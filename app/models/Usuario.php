<?php

class Usuario extends Model {
    // TODO: update password only query
    private const CREATE_QUERY       = "INSERT INTO usuarios(ci, nombre, apellido, correo, contrasena, rol, telefono) VALUES(?,?,?,?,?,?,?)";
    private const FIND_BY_CI_QUERY   = "SELECT * FROM usuarios WHERE ci=:ci";
    private const FIND_BY_ID_QUERY   = "SELECT * FROM usuarios WHERE id=:id";
    private const GET_ALL_QUERY      = "SELECT * FROM usuarios";
    private const UPDATE_QUERY       = "UPDATE usuarios SET ci=?, nombre=?, apellido=?, correo=?, contrasena=?, telefono=?, valido=? WHERE id=?";
    private const DELETE_BY_CI_QUERY = "DELETE FROM usuarios WHERE ci=?";

    private $id             = null;
    private $ci             = null;
    private $nombre         = null;
    private $apellido       = null;
    private $correo         = null;
    private $contrasena     = null;
    private $telefono       = null;
    private $rol            = null;
    private $valido         = null;
    private $fecha_registro = null;

    private static function new($id, $ci, $name, $surname, $email, $pwd, $role, $phone, $valid, $reg_Date) {
        $obj                 = new Usuario;
        $obj->id             = $id;
        $obj->ci             = $ci;
        $obj->nombre         = $name;
        $obj->apellido       = $surname;
        $obj->correo         = $email;
        $obj->contrasena     = $pwd;
        $obj->rol            = $role;
        $obj->telefono       = $phone;
        $obj->valido         = $valid;
        $obj->fecha_registro = $reg_Date;
        return $obj;
    }

    public function create($ci, $name, $surname, $email, $pwd, $role = "e", $phone = "") {
        $this->connect();

        $stmt = $this->pdo->prepare(self::CREATE_QUERY);
        return $stmt->execute([$ci, $name, $surname, $email, $pwd, $role, $phone]);
    }

    public function findByCI($ci) {
        $this->connect();

        $stmt = $this->pdo->prepare(self::FIND_BY_CI_QUERY);
        $stmt->execute([":ci" => $ci]);
        $row = $stmt->fetch();

        if($row)
            return self::new(
                $row["id"],
                $row["ci"],
                $row["nombre"],
                $row["apellido"],
                $row["correo"],
                $row["contrasena"],
                $row["rol"],
                $row["telefono"],
                $row["valido"],
                $row["fecha_registro"],
            );
        else return false;
    }

    public function findById($id) {
        $this->connect();

        $stmt = $this->pdo->prepare(self::FIND_BY_ID_QUERY);
        $stmt->execute([":id" => $id]);
        $row = $stmt->fetch();

        if($row)
            return self::new(
                $row["id"],
                $row["ci"],
                $row["nombre"],
                $row["apellido"],
                $row["correo"],
                $row["contrasena"],
                $row["rol"],
                $row["telefono"],
                $row["valido"],
                $row["fecha_registro"],
            );
        else return false;
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
                $row["ci"],
                $row["nombre"],
                $row["apellido"],
                $row["correo"],
                $row["contrasena"],
                $row["rol"],
                $row["telefono"],
                $row["valido"],
                $row["fecha_registro"],
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
            $this->ci,
            $this->nombre,
            $this->apellido,
            $this->correo,
            $this->contrasena,
            $this->telefono,
            $this->valido,
            $this->id
        ]);
    }

    public function delete($ci) {
        $this->connect();

        $stmt = $this->pdo->prepare(self::DELETE_BY_CI_QUERY);
        return $stmt->execute([$ci]);
    }

    public function getId() {
        return $this->id;
    }

    public function getCI() {
        return $this->ci;
    }

    public function setCI($val) {
        $this->ci = $val;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function setNombre($val) {
        $this->nombre = $val;
    }

    public function getApellido() {
        return $this->apellido;
    }

    public function setApellido($val) {
        $this->apellido = $val;
    }

    public function getCorreo() {
        return $this->correo;
    }

    public function setCorreo($val) {
        $this->correo = $val;
    }

    public function getContrasena() {
        return $this->contrasena;
    }

    public function setContrasena($val) {
        $this->contrasena = $val;
    }

    public function getRol() {
        return $this->rol;
    }

    public function setRol($val) {
        $this->rol = $val;
    }

    public function getTelefono() {
        return $this->telefono;
    }

    public function setTelefono($val) {
        $this->telefono = $val;
    }

    public function getValido() {
        return $this->valido;
    }

    public function setValido($val) {
        $this->valido = $val;
    }

}
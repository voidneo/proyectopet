<?php

class Curso extends Model {
    private const CREATE_QUERY         = "INSERT INTO cursos(nombre, duracion, id_articulo) VALUES(?,?,?)";
    private const FIND_BY_ID_QUERY     = "SELECT * FROM cursos WHERE id=:id";
    private const FIND_BY_NAME_QUERY   = "SELECT * FROM cursos WHERE nombre=:name";
    private const GET_ROW_COUNT_QUERY  = "SELECT COUNT(id) AS row_count FROM cursos";
    private const GET_ALL_QUERY        = "SELECT * FROM cursos";
    private const UPDATE_QUERY         = "UPDATE cursos SET nombre=?, duracion=?, id_articulo=? WHERE id=?";
    private const DELETE_BY_ID_QUERY   = "DELETE FROM cursos WHERE id=?";
    private const GET_SUBJECTS         = "SELECT id, nombre FROM materias_curso mc JOIN materias m ON mc.id_materia=m.id WHERE mc.id_curso=:course_id";
    private const ADD_SUBJECT_QUERY    = "INSERT INTO materias_curso(id_materia, id_curso) VALUES(?, ?)";
    private const REMOVE_SUBJECT_QUERY = "DELETE FROM materias_curso WHERE id_materia=? AND id_curso=?";

    // TODO: engineer a query builder

    private $id          = null;
    private $nombre      = null;
    private $duracion    = null;
    private $id_articulo = null;
    private $materias    = null;

    private static function new($id, $name, $duration, $art_id) {
        $obj              = new Curso;
        $obj->id          = $id;
        $obj->nombre      = $name;
        $obj->duracion    = $duration;
        $obj->id_articulo = $art_id;
        return $obj;
    }

    public function create($nombre, $length = 3, $id_art = 1) {
        $this->connect();

        $stmt = $this->pdo->prepare(self::CREATE_QUERY);
        return $stmt->execute([$nombre, $length, $id_art]);
    }

    public function findById($id) {
        $this->connect();

        $stmt = $this->pdo->prepare(self::FIND_BY_ID_QUERY);
        $stmt->execute([":id" => $id]);
        $row = $stmt->fetch();
        if($row)
            return self::new($row["id"], $row["nombre"], $row["duracion"], $row["id_articulo"]);
        return new Curso;
    }

    public function getRowCount() {
        $this->connect();
        $stmt = $this->pdo->prepare(self::GET_ROW_COUNT_QUERY);
        $stmt->execute([]);
        $rslt = $stmt->fetch();

        return $rslt["row_count"];
    }

    public function getAll($search_query = "", $sort = "", $page = []) {
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

        if (count($page)) {
            $length = intval($page["length"]);
            $offset = ($page["page"] - 1) * $length;

            if ($offset != 0) {
                $limit_clause = " LIMIT $offset, $length";
            } else {
                $limit_clause = " LIMIT $length";
            }
        } else {
            $page["page"] = 1;
        }

        $stmt = $this->pdo->prepare(self::GET_ALL_QUERY . $where_clause . $order_clause . $limit_clause);
        $stmt->execute($values);
        $rslt = $stmt->fetchAll();

        $filtered_results = [];
        foreach ($rslt as $row) {
            array_push($filtered_results, [
                "id"          => $row["id"],
                "nombre"      => $row["nombre"],
                "duracion"    => $row["duracion"],
                "id_articulo" => $row["id_articulo"],
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
        return self::new($row["id"], $row["nombre"], $row["duracion"], $row["id_articulo"]);
    }

    public function update() {
        $this->connect();

        if (is_null($this->id)) {
            throw new Exception("Missing id on the row to update");
        }

        $stmt = $this->pdo->prepare(self::UPDATE_QUERY);
        return $stmt->execute([$this->nombre, $this->duracion, $this->id_articulo, $this->id]);
    }

    public function delete($id) {
        $this->connect();

        $stmt = $this->pdo->prepare(self::DELETE_BY_ID_QUERY);
        return $stmt->execute([$id]);
    }

    public function addSubject($cid, $sub) {
        $this->connect();

        $stmt = $this->pdo->prepare(self::ADD_SUBJECT_QUERY);
        return $stmt->execute([$sub, $cid]);
    }

    public function addSubjects($cid, $subs) {
        $success_rate = 0;
        foreach($subs as $s) {
            $success_rate += $this->addSubject($cid, $s);
        }

        return $success_rate / count($subs);
    }

    public function removeSubject($cid, $sub) {
        $this->connect();

        $stmt = $this->pdo->prepare(self::REMOVE_SUBJECT_QUERY);
        return $stmt->execute([$sub, $cid]);
    }

    public function removeSubjects($cid, $subs) {
        $success_rate = 0;
        foreach($subs as $s) {
            $success_rate += $this->removeSubject($cid, $s);
        }

        return $success_rate / count($subs);
    }

    public function getMaterias() {
        if($this->materias != null) return $this->materias;

        $this->connect();

        if (is_null($this->id)) {
            throw new Exception("Missing course id");
        }

        $stmt = $this->pdo->prepare(self::GET_SUBJECTS);
        $stmt->execute([":course_id" => "$this->id"]);
        $rslt = $stmt->fetchAll();
        $this->materias = [];

        (new Controller)->load_model("Materia");

        foreach($rslt as $row) {
            array_push($this->materias, Materia::new($row["id"], $row["nombre"]));
        }

        return $this->materias;
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

    public function getDuracion() {
        return $this->duracion;
    }

    public function setDuracion($duracion) {
        $this->duracion = $duracion;
    }

    public function getIdArticulo() {
        return $this->id_articulo;
    }

    public function setIdArticulo($id_articulo) {
        $this->id_articulo = $id_articulo;
    }
}

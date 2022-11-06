<?php

class Nota extends Model {
    private const CREATE_QUERY = "INSERT INTO notas(id_inscripcion, id_materia, nota) VALUES(?,?,?)";
    private const FIND_QUERY   = "SELECT * FROM notas WHERE id_inscripcion=:id_inscripcion AND id_materia=:id_materia";
    private const UPDATE_QUERY = "UPDATE notas SET nota=? WHERE id_inscripcion=? AND id_materia=?";
    private const DELETE_QUERY = "DELETE FROM notas WHERE id_inscripcion=? AND id_materia=?";

    private $id_inscripcion = null;
    private $id_materia     = null;
    private $nota           = null;
    private $inscripcion    = null;
    private $materia        = null;

    public static function new($id_inscripcion, $id_materia, $nota) {
        $obj                 = new Nota;
        $obj->id_inscripcion = $id_inscripcion;
        $obj->id_materia     = $id_materia;
        $obj->nota           = $nota;
        return $obj;
    }

    public function create($id_inscripcion, $id_materia, $nota) {
        $this->connect();

        $stmt = $this->pdo->prepare(self::CREATE_QUERY);
        return $stmt->execute([$id_inscripcion, $id_materia, $nota]);
    }

    public function find($id_inscripcion, $id_materia) {
        $this->connect();

        $stmt = $this->pdo->prepare(self::FIND_QUERY);
        $stmt->execute([":id_inscripcion" => $id_inscripcion, ":id_materia" => $id_materia]);
        $row = $stmt->fetch();

        if($row)
            return self::new(
                $row["id_inscripcion"],
                $row["id_materia"],
                $row["nota"]
            );
        else return false;
    }

    public function update() {
        $this->connect();

        if (is_null($this->id_inscripcion) || is_null($this->id_materia)) {
            throw new Exception("Missing id on the row to update");
        }

        $stmt = $this->pdo->prepare(self::UPDATE_QUERY);
        return $stmt->execute([
            $this->nota,
            $this->id_inscripcion,
            $this->id_materia
        ]);
    }

    public function delete() {
        $this->connect();

        $stmt = $this->pdo->prepare(self::DELETE_QUERY);
        return $stmt->execute([$this->id_inscripcion, $this->id_materia]);
    }

    public function getId() {
        if(is_null($this->id_inscripcion) || is_null($this->id_materia))
            return 0;
        else
            return "i" . $this->id_inscripcion . "m" . $this->id_materia;
    }

    public function getIdInscripcion() {
        return $this->id_inscripcion;
    }

    public function getIdMateria() {
        return $this->id_materia;
    }

    public function getNota() {
        return $this->nota;
    }

    public function setNota($nota) {
        $this->nota = $nota;
    }

    public function getInscripcion() {
        if($this->inscripcion != null) return $this->inscripcion;

        (new Controller)->load_model("Inscripcion");
        $this->inscripcion = (new Inscripcion)->findById($this->id_inscripcion);
        return $this->inscripcion;
    }

    public function getMateria() {
        if($this->materia != null) return $this->materia;

        (new Controller)->load_model("Materia");
        $this->materia = (new Materia)->findById($this->id_materia);
        return $this->materia;
    }

}
<?php

class Inscripcion extends Model
{
    // TODO: update password only query
    private const CREATE_QUERY        = "INSERT INTO inscripciones(ci_usuario, id_curso, periodo) VALUES(?,?,?)";
    private const FIND_BY_CI_QUERY    = "SELECT * FROM inscripciones WHERE ci_usuario=:ci ORDER BY ano DESC";
    private const FIND_BY_ID_QUERY    = "SELECT * FROM inscripciones WHERE id=:id";
    private const GET_ALL_QUERY       = "SELECT * FROM inscripciones";
    private const GET_ROW_COUNT_QUERY = "SELECT COUNT(*) AS row_count FROM inscripciones";
    private const UPDATE_QUERY        = "UPDATE inscripciones SET id=?, ci_usuario=?, id_curso=?, periodo=?, ano=?, valido=? WHERE id=?";
    private const DELETE_BY_ID_QUERY  = "DELETE FROM inscripciones WHERE id=?";

    private $id         = null;
    private $ci_usuario = null;
    private $id_curso   = null;
    private $periodo    = null;
    private $ano        = null;
    private $usuario    = null;
    private $curso      = null;
    private $notas      = null;
    private $valido     = null;

    private static function new($id, $ci_usuario, $id_curso, $periodo, $ano, $valido)
    {
        $obj             = new Inscripcion;
        $obj->id         = $id;
        $obj->ci_usuario = $ci_usuario;
        $obj->id_curso   = $id_curso;
        $obj->periodo    = $periodo;
        $obj->ano        = $ano;
        $obj->valido     = $valido;
        return $obj;
    }

    public function create($ci_usuario, $id_curso, $periodo)
    {
        $this->connect();

        $stmt = $this->pdo->prepare(self::CREATE_QUERY);
        return $stmt->execute([$ci_usuario, $id_curso, $periodo]);
    }

    public function findByCi($ci)
    {
        $this->connect();

        $stmt = $this->pdo->prepare(self::FIND_BY_CI_QUERY);
        $stmt->execute([":ci" => $ci]);
        $rslt = $stmt->fetchAll();
        $objs = [];

        foreach ($rslt as $row) {
            array_push($objs, self::new(
                $row["id"],
                $row["ci_usuario"],
                $row["id_curso"],
                $row["periodo"],
                $row["ano"],
                $row["valido"]
            ));
        }

        return $objs;
    }

    public function findById($id)
    {
        $this->connect();

        $stmt = $this->pdo->prepare(self::FIND_BY_ID_QUERY);
        $stmt->execute([":id" => $id]);
        $row = $stmt->fetch();

        if ($row)
            return self::new(
                $row["id"],
                $row["ci_usuario"],
                $row["id_curso"],
                $row["periodo"],
                $row["ano"],
                $row["valido"]
            );
        else return false;
    }

    public function getAll($search_query = "", $approved = 0, /*$category = "", $date = ["year" => "____", "month" => "__", "day" => "__"],*/ $sort = [ "column" => "ano", "order" => "DESC" ], $page = ["page" => 1, "length" => 10]) {
        $this->connect();
        $where_clause = "";
        $order_clause = "";
        $limit_clause = "";
        $values       = [];

        // FIXME: order and limit clauses are vulnerable to SQL injections

        if (!empty($search_query)) {
            $where_clause = " WHERE (ci_usuario LIKE :search_query OR ano LIKE :search_query)";
            $values["search_query"] = "%$search_query%";
        }

        if ($approved == 1 || $approved == 2) {
            if (empty($where_clause)) $where_clause  = " WHERE";
            else                      $where_clause .= " AND";

            $where_clause .= " valido=:valid";
            $values[":valid"] = $approved - 1;
        }

        /*if(!empty($category)) {
            if(empty($where_clause)) {
                $where_clause = " WHERE id_categoria = $category";
            } else {
                $where_clause = "$where_clause AND id_categoria = $category";
            }
        }*/

        // If either the year, month or day isn't a wildcard
        /*if(
            count($date)
            && (
        			$date["year"]  != "____"
        		 || $date["month"] != "__"
        		 || $date["day"]   != "__"
        	)
		) {
            if(empty($where_clause)) {
                $where_clause = " WHERE fecha LIKE :yyyymmdd";
            } else {
                $where_clause = "$where_clause AND fecha LIKE :yyyymmdd";
            }
            $values["yyyymmdd"] = $date["year"] . "-" . $date["month"] . "-" . $date["day"] . " %";
        }*/

        if(count($sort)) {     
            $column       = $sort["column"];
            $order        = $sort["order"];
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
                "id"         => $row["id"],
                "ci_usuario" => $row["ci_usuario"],
                "id_curso"   => $row["id_curso"],
                "periodo"    => $row["periodo"],
                "periodo"    => $row["periodo"],
                "ano"        => $row["ano"],
                "valido"     => $row["valido"],
            ]);
        }

        return [
            "page"       => $page["page"],
            "row_count" => $this->getRowCount($where_clause, $values),
            "results"    => $filtered_results
        ];
    }
    
    public function getRowCount($where = "", $values = [])
    {
        $this->connect();

        $stmt = $this->pdo->prepare(self::GET_ROW_COUNT_QUERY . $where);
        $stmt->execute($values);
        $row = $stmt->fetch();

        if ($row) return $row["row_count"];
        else      return false;
    }

    public function update()
    {
        $this->connect();

        if (is_null($this->id)) {
            throw new Exception("Missing id on the row to update");
        }

        $stmt = $this->pdo->prepare(self::UPDATE_QUERY);
        return $stmt->execute([
            $this->id,
            $this->ci_usuario,
            $this->id_curso,
            $this->periodo,
            $this->ano,
            $this->valido,
            $this->id
        ]);
    }

    public function delete($id)
    {
        $this->connect();

        $stmt = $this->pdo->prepare(self::DELETE_BY_ID_QUERY);
        return $stmt->execute([$id]);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCiUsuario()
    {
        return $this->ci_usuario;
    }

    public function setCiUsuario($ci_usuario)
    {
        $this->ci_usuario = $ci_usuario;
    }

    public function getIdCurso()
    {
        return $this->id_curso;
    }

    public function setIdCurso($id_curso)
    {
        $this->id_curso = $id_curso;
    }

    public function getPeriodo()
    {
        return $this->periodo;
    }

    public function setPeriodo($periodo)
    {
        $this->periodo = $periodo;
    }

    public function getAno()
    {
        return $this->ano;
    }

    public function setAno($ano)
    {
        $this->ano = $ano;
    }

    public function getValido()
    {
        return $this->valido;
    }

    public function setValido($valido)
    {
        $this->valido = $valido;
    }

    public function getUsuario()
    {
        if ($this->usuario != null) return $this->usuario;

        (new Controller)->load_model("Usuario");
        $this->usuario = (new Usuario)->findByCi($this->ci_usuario);
        return $this->usuario;
    }

    public function getCurso()
    {
        if ($this->curso != null) return $this->curso;

        (new Controller)->load_model("Curso");
        $this->curso = (new Curso)->findById($this->id_curso);
        return $this->curso;
    }

    public function getNotas() {
        if ($this->notas != null) return $this->notas;

        (new Controller)->load_model("Nota");
        $this->notas = [];
        $mats  = $this->getCurso()->getMaterias();
        $score = new Nota;

        foreach($mats as $m) {
            $s = $score->find($this->id, $m->getId());
            if($s)
                $this->notas[$m->getNombre()] = $s;
            else {
                $n = new Nota;
                $tries = 0;

                // Try to create it, retry up to 6 times if needed
                while(!$n->create($this->id, $m->getId(), 0)) {
                    $tries++;

                    if($tries > 5) break;
                }

                $tries = 0;

                // Try to find it up to 5 times if needed
                while($tries < 6) {
                    $s = $score->find($this->id, $m->getId());
                    $tries++;

                    if($s) {
                        $this->notas[$m->getNombre()] = $s;
                        break;
                    }
                }

                // If still couldn't create and find it, fuck it, create a dummy
                if(!$s)
                    $this->notas[$m->getNombre()] = Nota::new($this->id, $m->getId(), 0);
            }
        }

        return $this->notas;
    }
}

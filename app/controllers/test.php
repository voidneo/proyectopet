<?php

class Test extends Controller {
    public function index($data = []) {


        error_reporting(-1);
        ini_set('display_errors', 'On');
        set_error_handler("var_dump");

        $this->load_model("Curso");
        $this->load_model("Inscripcion");
        $this->load_model("Nota");

        $i = (new Inscripcion)->findByCi("10000000");
        $m = (new Curso)->findById($i->getIdCurso())->getMaterias();
        $n = (new Nota)->find($i->getId(), $m[0]->getId());

        echo "id -> " . $i->getId() . "<br>";
        echo "Ci usuario -> " . $i->getCiUsuario() . "<br>";
        echo "Id curso -> " . $i->getCurso()->getNombre() . "<br>";
        echo "periodo -> " . $i->getPeriodo() . "<br>";
        echo "ano -> " . $i->getAno() . "<br>";

        for($r = 0; $r < count($m); $r++) {
            echo $m[$r]->getId() . " -> " . $m[$r]->getNombre() . "<br>";
        }

        echo $m[0]->getNombre() . " -> " . $n->getNota() . "<br>";
    }
}
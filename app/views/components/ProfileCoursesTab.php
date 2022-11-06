<h3 class="section-title">Cursos</h3>
<div class="list-item">
    <?php
    $ctrl = new Controller;
    $ctrl->load_model("Inscripcion");
    $ctrl->load_model("Articulo");
    $insc = (new Inscripcion)->findByCi($data["user"]->getCi());

    foreach ($insc as $course) {

        if (!$course->getValido()) {
            continue;
        }

        $ano  = $course->getPeriodo();

        switch ($ano) {
            case 1:
                $ano = "Primer";
                break;
            case 2:
                $ano = "Segundo";
                break;
            case 3:
                $ano = "Tercer";
                break;
            case 4:
                $ano = "Cuarto";
                break;
            case 5:
                $ano = "Quinto";
                break;
            case 6:
                $ano = "Sexto";
                break;
            case 7:
                $ano = "S&eacute;ptimo";
                break;
            case 8:
                $ano = "Octavo";
                break;
        }

        $art_id = $course->getCurso()->getIdArticulo();
        $art    = (new Articulo)->findById($art_id);
        $date   = new DateTime($art->getFecha());
        $date   = $date->format("Y") . "/" . $date->format("m") . "/" . $date->format("d");
        $url    = "cursos/$date/" . $art->getId();

        echo "<div class='flex sb'><div><a href='./" . $data["path_fix"] . "$url'>";
        echo $course->getCurso()->getNombre() . "</a></div><a href='./" . $data["path_fix"] . "perfil/notas?iid=" . $course->getId() .  "&uid=" . $data["user"]->getId() . "' target='_BLANK'>Ver notas</a></div>";
        echo "<span class='text-secondary'>" . $ano . " a&ntilde;o, " . substr($course->getAno(), 0, 4);
        echo "</span><hr>";
    }

    if (!count($insc)) {
        echo "<p class='text-secondary'>Oops, parece que no hay nada que mostrar.</p>";
    }
    ?>
</div>
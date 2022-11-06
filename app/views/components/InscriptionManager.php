<?php
$query    = isset($_GET["query"])    ? $_GET["query"]    : "";
$page     = isset($_GET["page"])     ? $_GET["page"]     : 1;
$rc       = isset($_GET["rc"])       ? $_GET["rc"]       : 10;
$approved = isset($_GET["approved"]) ? $_GET["approved"] : 0;
$e        = isset($_GET["e"])        ? $_GET["e"]        : 0;
$color    = $e == 0                  ? ""    : "class='text-danger'";
$msg      = "";

if($e == 1) $msg = "Algo sali&oacute; mal";
if($e == 2) $msg = "No se pudo encontrar la inscripci&oacute;n";
if($e == 3) $msg = "No se pudo actualizar la inscripci&oacute;n";
if($e == 4) $msg = "No se pudo borrar la inscripci&oacute;n";

$c    = new Controller;
$c->load_model("Inscripcion");
$c->load_model("Usuario");
$c->load_model("Curso");
$insc = new Inscripcion;
$user = new Usuario;
$cour = new Curso;
$date = "";

$insc  = $insc->getAll($query, $approved, ["column" => "ano", "order" => "DESC"], ["page" => $page, "length" => $rc]);
$count = $insc["row_count"];
?>
<table class="table table-striped table-hover">
    <thead>
        <tr>
            <td colspan="5">
                <?php echo "<p $color>$msg</p>"; ?>
                <form class="row g-2" action="./controlpanel" method="GET">
                    <div class="input-group">
                        <input class="form-control" type="text" name="query" placeholder="Buscar..." value="<?php echo $query ?>">

                        <input type="hidden" name="tab" value="inscriptions" readonly>
                        <input type="hidden" name="page" value="<?php echo $page  ?>" readonly>

                        <select class="form-select" style="max-width: 6em" name="rc" title="Resultados por p&aacute;gina">
                            <?php
                            for ($i = 5; $i <= 50; $i += 5) {
                                echo "<option" . ($i == $rc ? " selected" : "") . ">$i</option>";
                            }
                            ?>
                        </select>
                        <button title="Buscar" class="btn btn-primary" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                    </div>
                    <div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="approved" id="option1" value="2" <?php echo $approved == 2 ? "checked" : ""; ?>>
                            <label for="option1">Aprobados</label>
                        </div>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="approved" id="option2" value="1" <?php echo $approved == 1 ? "checked" : ""; ?>>
                            <label for="option2">No aprobados</label>
                        </div>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="approved" id="option3" value="0" <?php echo $approved == 0 ? "checked" : ""; ?>>
                            <label for="option3">Ambos</label>
                        </div>
                    </div>
                </form>
            </td>
        </tr>
        <tr>
            <th>C.I. estudiante</th>
            <th>Curso</th>
            <th>Per&iacute;odo</th>
            <th>Fecha de inscripci&oacute;n</th>
            <th></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($insc["results"] as $i) {
            $user = $user->findByCI($i["ci_usuario"]);
            $cour = $cour->findById($i["id_curso"]);
            $date = (new DateTime($i["ano"]));
            $date = $date->format("d") . "/" . $date->format("m") . "/" . $date->format("Y");

            echo "<tr>";
            echo "<td><a href='./perfil?id=" . $user->getId() . "'>" . $i["ci_usuario"] . "</a></td>";
            echo "<td><a href='./curso_?cid=" . $cour->getId() . "'>" . $cour->getNombre() . "</a></td>";
            echo "<td>" . rtrim(substr($cour->getDuracion(), 2), "s") . " " . $i["periodo"] . "</td>";
            echo "<td>$date</td>";
            echo "<td>";
            if ($i["valido"]) {
                echo "<a href='./inscripcion_/notas?id=" . $i["id"] . "' title='Ver/editar notas' class='btn btn-outline-primary'>Ver notas</a>";
            }
            echo "</td>";
            echo "<td><div class='input-group'>";
            if (!$i["valido"]) {
                echo "<a href='./inscripcion_/aceptar?id=" . $i["id"] . "&query=$query&approved=$approved&page=$page&rc=$rc' title='Aprobar inscripci&oacute;n' class='btn btn-outline-success'>";
                echo "<i class='fa-solid fa-check'></i>";
                echo "</a>";
            } else {
                echo "<button class='btn btn-outline-primary disabled'><i class='fa-solid fa-check'></i></button>";
            }
            echo "<a href='./inscripcion_/borrar?id=" . $i["id"] . "&query=$query&approved=$approved&page=$page&rc=$rc'  title='Borrar inscripcion' class='btn btn-danger'>";
            echo "<i class='fa-solid fa-trash'></i>";
            echo "</a>";
            echo "</div></td>";
            echo "</tr>";
        }
        ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="7">
                <nav aria-label="...">
                    <ul class="pagination justify-content-center" id="art-pagination">
                        <?php
                        $pages = floor($count / $rc);
                        $pages += ($count / $rc - floor($count / $rc) > 0 ? 1 : 0);

                        for ($i = 1; $i <= $pages; $i++) {
                            $active = $page == $i    ? "active" : "";
                            $href   = $i    == $page ? "#" : "./controlpanel?tab=inscriptions&query=$query&approved=$approved&page=$i&rc=$rc";
                            echo "<a href='$href'>";
                            echo "<li title='Ir a la pagina $i' class='page-item $active'>";
                            echo "<span class='page-link'>$i</span></li></a>";
                        }
                        ?>
                    </ul>
                </nav>
            </td>
        </tr>
    </tfoot>
</table>
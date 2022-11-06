<?php
$query = isset($_GET["query"]) ? $_GET["query"] : "";
$page  = isset($_GET["page"])  ? $_GET["page"]  : 1;
$rc    = isset($_GET["rc"])    ? $_GET["rc"]    : 10;

(new Controller)->load_model("Curso");
(new Controller)->load_model("Articulo");
$m = (new Curso)->getAll($query, "", ["page" => $page, "length" => $rc])["results"];
$count = (new Curso)->getRowCount();
?>

<table class="table table-striped table-hover">
    <thead>
        <tr>
            <th colspan="4">
                <form action="./controlpanel" method="GET">
                    <div class="input-group">
                        <a href="./curso_" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Crear</a>

                        <input class="form-control" type="text" name="query" placeholder="Buscar..." value="<?php echo $query ?>">

                        <input type="hidden" name="tab" value="courses" readonly>
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
                </form>
            </th>
        </tr>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Duraci&oacute;n</th>
            <th>Art&iacute;culo</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $a = new Articulo;
        foreach ($m as $c) {
            $a = $a->findById($c["id_articulo"]);
            $date = $a ? $a->getFecha() : 0;
            $date = $date ? new DateTime($date) : 0;
            $href = $a ? "./cursos/" . ($date ? $date->format("Y") : "") . "/" . ($date ? $date->format("m") : "") . "/" . ($date ? $date->format("d") : "") . "/" . ($a ? $a->getId() : "") : "#";
            $titulo = $a ? $a->getTitulo() : "-";

            echo "<tr><td>" . $c["id"] . "</td><td><a href='./curso_?cid=" . $c["id"] . "'>" . $c["nombre"] . "</a></td>";
            echo "<td>" . $c["duracion"] . "</td>";
            echo "<td><a href='$href'>" . $titulo . "</a></td></tr>";
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
                            $href   = $i    == $page ? "#" : "./controlpanel?tab=courses&page=$i&rc=$rc";
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
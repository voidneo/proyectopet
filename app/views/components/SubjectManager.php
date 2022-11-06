<?php
$query = isset($_GET["query"]) ? $_GET["query"] : "";
$page = isset($_GET["page"]) ? $_GET["page"] : 1;
$rc   = isset($_GET["rc"])   ? $_GET["rc"]   : 10;

$c    = new Controller;
$c->load_model("Materia");
$subs = new Materia;

$count = $subs->getRowCount();
$subs  = $subs->getAll($query, "", ["page" => $page, "length" => $rc]);
?>
<table class="table table-striped table-hover">
    <thead>
        <tr>
            <th colspan="2">
                <form action="./controlpanel" method="GET">
                    <div class="input-group">
                    <a href="./materias" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Crear</a>    
                    
                    <input class="form-control" type="text" name="query" placeholder="Buscar..." value="<?php echo $query ?>">

                        <input type="hidden" name="tab"   value="subjects"             readonly>
                        <input type="hidden" name="page"  value="<?php echo $page  ?>" readonly>

                        <select class="form-select" style="max-width: 6em" name="rc" title="Resultados por p&aacute;gina">
                            <?php
                                for($i = 5; $i <= 50; $i += 5) {
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
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($subs["results"] as $s) {
            echo "<tr><td>" . $s["id"] . "</td><td><a href='./materias?sid=" . $s["id"] . "'>" . $s["nombre"] . "</a></td></tr>";
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
                            $href   = $i    == $page ? "#" : "./controlpanel?tab=subjects&page=$i&rc=$rc";
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
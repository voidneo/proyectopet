<?php
$query = isset($_GET["query"]) ? $_GET["query"] : "";
$page  = isset($_GET["page"])  ? $_GET["page"]  : 1;
$rc    = isset($_GET["rc"])    ? $_GET["rc"]    : 10;
$valid = isset($_GET["valid"]) ? $_GET["valid"] : 0;
$roles = [];
$roles["std"] = isset($_GET["std"]) ? $_GET["std"] : 0;
$roles["tch"] = isset($_GET["tch"]) ? $_GET["tch"] : 0;
$roles["adm"] = isset($_GET["adm"]) ? $_GET["adm"] : 0;

(new Controller)->load_model("Usuario");
$u           = new Usuario;
$r           = $u->getAll($query, $roles, $valid, ["offset" => ($page - 1) * $rc, "row_count" => $rc]);
$count       = $r["row_count"];
$valido      = 0;
$valid_color = "";
$rol_color   = "";
$rol         = "";
?>
<div id="user-manager" security-hash="<?php $data["security_hash"] ?>">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <td colspan="5">
                    <form action="./controlpanel" method="GET" class="row g-2">
                        <div class="input-group">
                            <input class="form-control" type="text" name="query" placeholder="Buscar..." value="<?php echo $query ?>">

                            <input type="hidden" name="tab" value="users" readonly>
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
                            <div class="form-check form-switch form-check-inline">
                                <input class="form-check-input" type="checkbox" name="std" value="1" id="ecb" <?php echo $roles["std"] ? "checked" : ""; ?>>
                                <label class="form-check-label" for="ecb">
                                    Estudiantes
                                </label>
                            </div>
                            <div class="form-check form-switch form-check-inline">
                                <input class="form-check-input" type="checkbox" name="tch" value="1" id="dcb" <?php echo $roles["tch"] ? "checked" : ""; ?>>
                                <label class="form-check-label" for="ecb">
                                    Docentes
                                </label>
                            </div>
                            <div class="form-check form-switch form-check-inline">
                                <input class="form-check-input" type="checkbox" name="adm" value="1" id="acb" <?php echo $roles["adm"] ? "checked" : ""; ?>>
                                <label class="form-check-label" for="ecb">
                                    Adscriptos
                                </label>
                            </div>
                        </div>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="valid" id="option1" value="2" <?php echo $valid == 2 ? "checked" : ""; ?>>
                                <label for="option1">Aprobados</label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="valid" id="option2" value="1" <?php echo $valid == 1 ? "checked" : ""; ?>>
                                <label for="option2">No aprobados</label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="valid" id="option3" value="0" <?php echo $valid == 0 ? "checked" : ""; ?>>
                                <label for="option3">Ambos</label>
                            </div>
                        </div>
                    </form>
                </td>
            </tr>
            <tr>
                <th>C.I.</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Telefono</th>
                <th>Rol</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($r["results"] as $uu) {
                $rol          = $uu->getRol()    == "e"          ? "estudiante" : ($uu->getRol() == "d" ? "docente" : "adscripto");
                $valido       = $uu->getValido()                 ? "si" : "no";
                $valid_color  = $valido          == "si"         ? ($rol    != "estudiante" ? "table-primary" : "") : "table-warning";
                $rol_color    = $rol             == "estudiante" ? ($valido == "si"         ? "valid" : "invalid")  : "docente";

                echo "<tr><td><span class='pos-fix'>";
                echo $uu->getCI();
                echo "</span></td><td><a title='Ir al perfil' class='u' href='./perfil/opciones?id=" . $uu->getId() . "'><span class='pos-fix'>";
                echo $uu->getNombre() . " " . $uu->getApellido();
                echo "</span></td><td><a title='Enviar un correo' class='u' href='mailto:" . $uu->getCorreo() .  "'><span class='pos-fix'>";
                echo $uu->getCorreo();
                echo "</span></a></td><td><span class='pos-fix'>";
                echo $uu->getTelefono();
                echo "</td><td><span class='pos-fix'>$rol</span></td></tr>";
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
                                $active = $page == $i ? "active" : "";
                                $href   = $i    == $page ? "#" : "./controlpanel?tab=users&query=$query&valid=$valid&page=$i&rc=$rc";
                                $href .= $roles["std"] ? "&std=1" : "";
                                $href .= $roles["tch"] ? "&tch=1" : "";
                                $href .= $roles["adm"] ? "&adm=1" : "";

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
</div>
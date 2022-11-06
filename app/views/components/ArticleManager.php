<div id="art-mgr" security-hash="<?php echo $data["security_hash"]; ?>">
    <table id="category-widget" class="table table-striped table-hover">
        <thead>
            <tr>
                <td colspan="5">
                    <div class="input-group">
                        <a href="./articulo" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Crear</a>
                        <input type="text" id="art-search-field" class="form-control" placeholder="Buscar..." aria-label="Buscar...">
                        <button title="Buscar" class="btn btn-primary" id="search-btn" type="button">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                        <select title="Resultados por pagina" id="page-length" class="btn btn-primary dropdown-toggle">
                            <option>5</option>
                            <option>10</option>
                            <option>15</option>
                            <option>20</option>
                            <option>25</option>
                            <option>30</option>
                        </select>
                    </div>
                    <div id="search-props" page-length="5" sort-column="id" sort-order="asc" page="1"></div>
                </td>
            </tr>
            <tr>
                <td colspan="5">
                    <div class="input-group">
                        <?php (new Controller)->load_view("components/CategoryChooser", $data); ?>
                        <input title="Filtrar por ano" type="number" class="form-control" placeholder="A&ntilde;o.." id="year" />
                        <input title="Filtrar por mes" type="number" class="form-control" min="1" max="12" placeholder="Mes.." id="month" />
                        <input title="Filtrar por dia" type="number" class="form-control" min="1" max="31" placeholder="Dia.." id="day" />
                    </div>
                </td>
            </tr>
            <tr>
                <th title="Ordenar por ID" id="th-id">ID</th>
                <th title="Ordenar por titulo" id="th-title">Titulo</th>
                <th title="Ordenar por categoria" id="th-category">Categor&iacute;a</th>
                <th title="Ordenar por fecha" id="th-date">Fecha</th>
                <th id="th-date">Opciones</th>
            </tr>
        </thead>
        <tbody id="art-table-body">
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5">
                    <nav aria-label="...">
                        <ul class="pagination justify-content-center" id="art-pagination"></ul>
                    </nav>
                </td>
            </tr>
        </tfoot>
    </table>
</div>
<script type="text/javascript">
    <?php echo file_get_contents("./scripts/article-manager.js"); ?>
</script>
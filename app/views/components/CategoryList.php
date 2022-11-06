<table id="category-widget" class="table table-striped table-hover">
    <thead>
        <tr>
            <td colspan="3">
                <div class="input-group">
                    <input type="text" id="search-box" class="form-control" placeholder="Buscar..." aria-label="Buscar...">
                    <button title="Buscar" class="btn btn-primary" id="search-btn" type="button">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                    <select title="Resultados por pagina" id="rows-per-page" class="btn btn-primary dropdown-toggle">
                        <option>5</option>
                        <option>10</option>
                        <option>15</option>
                        <option>20</option>
                        <option>25</option>
                        <option>30</option>
                    </select>
                </div>

            </td>
            <input type="hidden" id="loaded-page" value="1" />
            <input type="hidden" id="order-column" value="id" />
            <input type="hidden" id="order-direction" value="asc" />
        </tr>
        <tr>
            <th title="Ordenar por ID" id="column-id">ID</th>
            <th title="Ordenar por nombre" id="column-nombre">Nombre</th>
            <th>Opciones</th>
        </tr>
    </thead>
    <tbody id="table-body">
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3">
                <div class="input-group">
                    <input type="text" id="cat-creation-name" class="form-control" placeholder="Categoria..." aria-label="Categoria..." />
                    <input title="Crear categoria" type="submit" class="btn btn-success" id="cat-creation-btn" value="Crear" />
                </div>
                <input type="hidden" id="security-hash" value="<?php echo $data["security_hash"] ?>" />
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <nav aria-label="...">
                    <ul id="pages" class="pagination justify-content-center" id="pagination"></ul>
                </nav>
            </td>
        </tr>
    </tfoot>
</table>

<script name="widget-script" type="text/javascript">
    <?php echo file_get_contents("./scripts/category-list.js"); ?>
</script>
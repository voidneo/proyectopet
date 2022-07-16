<table id="category-widget">
    <thead>
        <tr>
            <td colspan="3">
                <input type="text" id="search-box" placeholder="Buscar.." />
                <button id="search-btn">Buscar</button>
                <select id="rows-per-page">
                    <option>5</option>
                    <option>10</option>
                    <option>15</option>
                    <option>20</option>
                    <option>25</option>
                    <option>30</option>
                </select>
            </td>
            <input type="hidden" id="loaded-page" value="1"/>
            <input type="hidden" id="order-column" value="id"/>
            <input type="hidden" id="order-direction" value="asc"/>
        </tr>
        <tr>
            <td id="column-id">ID</td>
            <td id="column-nombre">Nombre</td>
            <td>Opciones</td>
        </tr>
    </thead>
    <tbody id="table-body">
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3">
                <input type="text" id="cat-creation-name" placeholder="Categoria..." />
                <input type="hidden" id="security-hash" value="<?php echo $data["security_hash"] ?>" />
                <input type="submit" id="cat-creation-btn" value="Crear" />
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <span id="pages"></span>
            </td>
        </tr>
    </tfoot>
</table>

<?php
$script_path = "./scripts/";
if(isset($data["script_path"])) {
    $script_path = $data["script_path"];
}
?>

<script type="text/javascript" src="<?php echo $script_path; ?>category-list.js""></script>
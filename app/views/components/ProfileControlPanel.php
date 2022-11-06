<?php
$mainComponent = "ProfileCoursesTab";
$uid           = isset($_GET["id"]) ? "?id=" . $_GET["id"] : "";
switch ($data["tab"]) {
    case "opciones":
        $mainComponent = "ProfileConfigTab";
        break;
}

$cp_path_fix = $data["cp_path_fix"];
?>

<div class="row">
    <div class="row d-flex">
        <div class="border-end bg-white col-md-3" id="sidebar-wrapper">
            <div class="sidebar-heading border-bottom bg-light">Panel de control</div>
            <div class="list-group list-group-flush w-100">
                <a class="list-group-item list-group-item-action list-group-item-light p-3 w-100" href="<?php echo $mainComponent == "ProfileCoursesTab" ? "#" : "./" . $cp_path_fix . "cursos$uid"; ?>" id="btn-users">
                    <i class="fa-solid fa-users"></i> Cursos
                </a>
                <a class="list-group-item list-group-item-action list-group-item-light p-3 w-100" href="<?php echo $data["tab"] == "opciones" ? "#" : "./" . $cp_path_fix . "opciones$uid"; ?>" id="btn-categories">
                    <i class="fa-solid fa-gear"></i> Opciones
                </a>
            </div>
        </div>
        <div id="page-content" class="col-md-9">
            <div id="component" class="side-margin">
                <?php (new Controller)->load_view("components/$mainComponent", $data); ?>
            </div>
        </div>
    </div>
</div>
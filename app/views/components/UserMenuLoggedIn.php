<?php
$path_fix = "";
if(isset($data["path_fix"])) {
  $path_fix = $data["path_fix"];
}
?>

<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        <?php echo $_SESSION["nombre"]; ?>
    </a>
    <ul class="dropdown-menu">
        <?php
        if(isset($_SESSION["rol"]) && $_SESSION["rol"] != "e") {
            echo "<li><a class='dropdown-item' href='./" . $path_fix . "controlpanel?tab=users&std=1&tch=1&adm=1'><i class='fa-solid fa-gear'></i> Panel de control</a></li>";
        }
        ?>
        <li>
            <a class="dropdown-item" href="./<?php echo $path_fix; ?>perfil">
            <i class="fa-solid fa-user"></i> Perfil
        </a>
    </li>
        <li>
            <a class="dropdown-item" href="./<?php echo $path_fix; ?>logout">
            <i class="fa-solid fa-right-from-bracket"></i> Cerrar sesi&oacute;n
        </a>
    </li>
    </ul>
</li>
</ul>
<?php
$path_fix = "";
if(isset($data["path_fix"])) {
  $path_fix = $data["path_fix"];
}
?>

</ul>
<div class="d-flex" role="search">
    <a href="./<?php echo $path_fix; ?>login"><button type="button" class="btn btn-outline-primary me-2">Iniciar sesi&oacute;n</button></a>
    <a href="./<?php echo $path_fix; ?>inscripciones"><button type="button" class="btn btn-primary">Registrarse</button></a>
</div>
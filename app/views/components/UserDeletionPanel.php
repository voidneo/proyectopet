<?php
// Viewer's role
$vrole   = $_SESSION["rol"];
$is_admin= $vrole != "e";
if(!$is_admin) return;

$u       = $data["user"];
$id      = $u->getId();
$role    = $u->getRol();
$name    = $u->getNombre();
$surname = $u->getApellido();
$email   = $u->getCorreo();
$phone   = $u->getTelefono();
$valid   = $u->getValido();
?>

<hr>

<div class="container v-flex gap">
    <h3 class="section-title" style="align-self: flex-end;">Eliminar cuenta</h3>
    <form action="./delete_student?uid=<?php echo "$id"; ?>" method="POST" class="v-flex gap">
        <p class="text-secondary">Borrar de forma definitiva la cuenta de este usuario y toda informaci&oacute;n relacionada. Esta acci&oacute;n no se puede deshacer.</p>
        <div class="row g-3" style="align-self: flex-end;">
            <input type="submit" class="btn btn-danger margin" value="Eliminar cuenta">
        </div>
    </form>
</div>
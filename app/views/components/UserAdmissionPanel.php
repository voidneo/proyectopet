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

$str1 = $valid ? "Rechazar" : "Admitir";
$str2 = $valid ? "Al rechazar a esta persona est&aacute;s indicando que esta persona no es estudiante o que no puede estudiar en el Polo tecnol&oacute;gico y por lo tanto no se le autoriza a utilizar el sitio web, su cuenta seguira existiendo pero no podr&aacute; usarla." :
"Al admitir a esta persona como estudiante est&aacute;s indicando que esta persona es estudiante en el Polo tecnol&oacute;gico y se le brinda permiso para utilizar sitio web. En su perfil se mostrar&aacute;n los cursos a los que se ha inscripto.";
?>

<hr>

<div class="container v-flex gap">
    <h3 class="section-title" style="align-self: flex-end;"><?php echo $str1; ?> estudiante</h3>
    <form action="./allow_student?uid=<?php echo "$id&v=" . ($valid ? 0 : 1); ?>" method="POST" class="v-flex gap">
        <p class="text-secondary"><?php echo $str2; ?></p>
        <div class="row g-3" style="align-self: flex-end;">
            <input type="submit" class="btn btn-warning margin" value="<?php echo $str1; ?> estudiante">
        </div>
    </form>
</div>
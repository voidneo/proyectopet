<?php
$u       = $data["user"];
$id      = $u->getId();
$role    = $u->getRol();
$name    = $u->getNombre();
$surname = $u->getApellido();
$email   = $u->getCorreo();
$phone   = $u->getTelefono();
$valid   = $u->getValido();
// Viewer's role
$vrole   = $_SESSION["rol"];
$is_admin= $vrole != "e";
?>

<h3 class="section-title">Opciones</h3>
<div class="v-flex gap">
    <div class="container v-flex gap">
        <form action="./update_data" method="POST" class="v-flex gap" id="user_data_form">
            <div class="row g-3 <?php if (!$is_admin) echo "hidden"; ?>">
                <div class="form-group col-md-6">
                    <label for="name">ID</label>
                    <input type="text" class="form-control" name="id" id="id" value="<?php echo $id; ?>" readonly>
                </div>
                <div class="form-group col-md-6">
                    <label for="role">Rol</label>
                    <select name="role" id="role" form="user_data_form" class="form-select">
                        <option value="e">Estudiante</option>
                        <option value="d" <?php if ($role == "d") echo "selected"; ?>>Docente</option>
                        <option value="a" <?php if ($role == "a") echo "selected"; ?>>Adscripto</option>
                    </select>
                </div>
            </div>
            <div class="row g-3">
                <div class="form-group col-md-6">
                    <label for="name">Nombre</label>
                    <input type="text" class="form-control" name="name" id="name" placeholder="Nombre" value="<?php echo $name; ?>">
                </div>
                <div class="form-group col-md-6">
                    <label for="surname">Apellido</label>
                    <input type="text" class="form-control" name="surname" id="surname" placeholder="Apellido" value="<?php echo $surname; ?>">
                </div>
            </div>
            <div class="row g-3">
                <div class="form-group col-md-12">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" name="email" id="email" placeholder="Email" value="<?php echo $email; ?>">
                </div>
            </div>
            <div class="row g-3">
                <div class="form-group col-md-12">
                    <label for="phone">Telefono</label>
                    <input type="tel" class="form-control" name="phone" id="phone" placeholder="Telefono" pattern="[0-9]{8,9}" value="<?php echo $phone; ?>">
                </div>
            </div>
            <div class="row g-3" style="align-self: flex-end;">
                <input type="submit" class="btn btn-primary margin" value="Guardar">
            </div>
        </form>
    </div>

    <?php (new Controller)->load_view("components/UserAdmissionPanel", $data); ?>

    <hr>

    <div class="container v-flex gap">
        <h3 class="section-title">Actualizar contrase&ntilde;a</h3>
        <form action="./update_pwd" method="POST" class="v-flex gap">
            <input type="hidden" name="id"value="<?php echo $id; ?>" readonly>
            <div class="row g-3">
                <div class="form-group col-md-6 <?php echo $is_admin ? "hidden" : ""; ?>">
                    <label for="name">Contrase&ntilde;a actual</label>
                    <input type="password" class="form-control" name="pwd" id="pwd" placeholder="Contrasena actual" value="">
                </div>
                <div class="form-group col-md-6">
                    <label for="newpwd">Nueva contrase&ntilde;a</label>
                    <input type="password" class="form-control" name="newpwd" id="newpwd" placeholder="Nueva contrasena" value="" required>
                </div>
                <?php echo $is_admin ? "" : "</div><div class='row g-3'><div class='form-group col-md-6'></div>"; ?>
                <div class="form-group col-md-6">
                    <label for="newpwd2">Confirmar contrase&ntilde;a</label>
                    <input type="password" class="form-control" name="newpwd2" id="newpwd2" placeholder="Confirmar contrasena" value="" required>
                </div>
            </div>
            <div class="row g-3" style="align-self: flex-end;">
                <input type="submit" class="btn btn-primary margin" value="Actualizar contrase&ntilde;a">
            </div>
        </form>
    </div>

    <?php (new Controller)->load_view("components/UserDeletionPanel", $data); ?>

</div>
<div class="margin"><br></div>
<div class="margin"><br></div>
<div class="margin"><br></div>
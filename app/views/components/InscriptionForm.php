<?php
(new Controller)->load_model("Curso");
$courses = new Curso;
$courses = $courses->getAll()["results"];
$logged_in = isset($_SESSION["id"]);
$error = isset($_GET["e"]) ? $_GET["e"] : -1;
$msg = "";

switch($error) {
    case 0: $msg = "La inscripci&oacute;n se ha realizado con exito";  break;
    case 1: $msg = "Por favor complete los datos necesarios";  break;
    case 2: $msg = "Algo sali&oacute; mal. Intente nuevamente, si el problema persiste contacte a un administrador";  break;
}

$msg_color = $error == 0 ? "success" : "danger";

?>

<div class="container margin">
    <h1 class="h3 mb-3 fw-normal">Inscribirme</h1>
    <p class="text-<?php echo $msg_color; ?>"><?php echo $msg; ?></p>
    <form id="form" action="./inscripciones/send" method="POST" class="row g-3">
        <div class="row g-3">
            <div class="col-md-6">
                <label for="name" class="form-label">Nombre <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="name" name="name" <?php echo $logged_in ? "value=\"" . $_SESSION["nombre"] . "\" readonly" : "\""; ?> required>
            </div>
            <div class="col-md-6">
                <label for="surname" class="form-label">Apellido <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="surname" name="surname" <?php echo $logged_in ? "value=\"" . $_SESSION["apellido"] . "\" readonly" : "\""; ?> required>
            </div>
        </div>
        <div class="col-md-6">
            <label for="id" class="form-label">C&eacute;dula <span class="text-danger">*</span></label>
            <input type="tel" class="form-control" id="id" name="ci" pattern="^[0-9]{8}" <?php echo $logged_in ? "value=\"" . $_SESSION["cedula"] . "\" readonly" : "\""; ?> required>
        </div>
        <div class="col-12">
            <label for="inputEmail4" class="form-label">Email <span class="text-danger">*</span></label>
            <input type="email" class="form-control" id="inputEmail4" name="email" <?php echo $logged_in ? "value=\"" . $_SESSION["correo"] . "\" readonly" : "\""; ?> required>
        </div>
        <div class="row g-3">
            <div class="col-md-6">
                <label for="inputState" class="form-label">Curso <span class="text-danger">*</span></label>
                <select for="form" id="inputState" name="course" class="form-select" required>
                    <?php
                    foreach ($courses as $c) {
                        echo "<option value='" . $c["id"] . "'>" . $c["nombre"] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-6">
                <label for="perioud" class="form-label">Per&iacute;odo <span class="text-danger">*</span> <span class="text-secondary">(a&ntilde;o o semestre)</span></label>
                <input type="number" class="form-control" id="period" name="periodo" min="1" max="8" required>
            </div>
        </div>
        <div class="col-12">
            <p class="text-secondary"><?php echo $logged_in ? "" : "Al inscribirte se te crear&aacute; una cuenta cuya contrase&ntilde;a por defecto ser&aacute; tu c&eacute;dula. Solo podras usar tu cuenta una vez un adscripto valide la inscripci&oacute;n."; ?></p>
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-primary">Inscribirme</button>
        </div>
    </form>
</div>
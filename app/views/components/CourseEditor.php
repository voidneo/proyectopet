<?php

error_reporting(-1);
ini_set('display_errors', 'On');
set_error_handler("var_dump");


$c      = isset($data["course"]) ? $data["course"] : 0;
$id     = $c ? $c->getId()       : "";
$name   = $c ? $c->getNombre()   : "";
$length = $c ? $c->getDuracion() : "1";
$mats   = $c ? $c->getMaterias() : [];
$msg    = "";
$color  = "success";
if(isset($_GET["e"])) {
	$e = $_GET["e"];
	if($e == 0) $msg   = "Curso creado/actualizado con &eacute;xito";
	if($e == 1) $msg   = "Fall&oacute; la actualizaci&oacute;n de las materias del curso";
	if($e == 2) $msg   = "No se pudo actualizar el curso";
	if($e == 3) $msg   = "Algunas materias no pudieron vincularse al curso";
	if($e == 4) $msg   = "Las materias no pudieron vincularse al curso";
	if($e == 5) $msg   = "No se pudo vincular el articulo al curso";
	if($e == 6) $msg   = "No se pudo encontrar el articulo para el curso";
	if($e == 7) $msg   = "No se pudo crear un articulo para el curso";
	if($e == 8) $msg   = "No se pudo encontrar el curso";
	if($e == 9) $msg   = "No se pudo crear el curso";
	if($e != 0) $color = "danger";
}
?>

<div class="container">
	<form class="flex" action="./curso_/send" method="POST" id="form">
	<?php if($id) echo "<input type='hidden' name='id' value='$id'>"; ?>
		<div class="row">
			<div class="row form-group g-3">
			<p class="text-<?php echo $color; ?>"><?php echo $msg; ?></p>
			<p class="text-secondary">Al crear un curso tambi&eacute;n se crear&aacute; un art&iacute;culo el cual se utilizar&aacute; para describir las cualidades del curso</p>
				<div class="control-group col-md-9">
					<label for="name">Nombre</label>
					<input class="form-control" type="text" name="name" value="<?php echo $name; ?>" placeholder="Nombre..." required>
				</div>
				<div class="control-group col-md-3">
					<label for="length">Duraci&oacute;n</label>
					<div class="input-group">
						<input class="form-control" type="number" min="1" max="8" name="length" value="<?php echo $length[0]; ?>" placeholder="Duraci&oacute;n" required>
					<select class="form-select" name="period-type">
						<option value="a&ntilde;os" selected>A&ntilde;os</option>
						<option value="semestres">Semestres</option>
					</select>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="form-group">
				<div class="control-group">
					<label for="subjects[]">Materias</label>
					<select class="form-select" name="subjects[]" title="Aprete Ctrl y haga click para seleccionar m&aacute;s de una" multiple required>
						<?php
						(new Controller)->load_model("Materia");
						$subs = (new Materia)->getAll()["results"];

						foreach ($subs as $s) {
							$selected = "";

							foreach($mats as $m) {
								if($m->getNombre() == $s["nombre"]) {
									$selected = "selected";
									break;
								}
							}

							echo "<option value='" . $s["id"] . "' $selected>" . $s["nombre"] . "</option>";
						}
						?>
					</select>
				</div>
			</div>
		</div>
		<div class="row g-3" style="align-self: flex-end;">
			<input class="btn btn-primary" style="align-self: flex-end" type="submit" value="<?php echo $data["mode"]; ?>">
		</div>
	</form>
</div>
<?php
$name = isset($data["sub"]) ? $data["sub"]->getNombre() : "";
$id = isset($data["sub"])   ? $data["sub"]->getId()     : "";
?>

<div class="container" style="margin: 2em auto;">
	<form action="./materias/send" method="POST">
		<div class="row g-3">
			<p class="text-<?php echo $_GET["e"] == 0 ? "success" : "danger"; ?>">
				<?php
				if (isset($_GET["e"])) {
					if ($_GET["e"] == 0) echo "La categoria se cre&oacute;/actualiz&oacute; con &eacute;xito";
					if ($_GET["e"] == 1) echo "Algo sali&oacute; mal. Intente nuevamente. Si el problema persiste contacte a un administrador";
				}
				?>
			</p>
		</div>
		<div class="row g-3">
			<div class="form-group col-md-11">
				<label for="name">Nombre</label>
				<?php
				if ($id) {
					echo "<input type='hidden' name='id' value='$id' readonly>";
				}
				?>
				<input value="<?php echo $name; ?>" class="form-control" type="text" name="name" placeholder="Nombre de la materia..." required>
			</div>
			<div class="form-group col-md-1" style="display: flex;flex-direction: column;justify-content: flex-end;">
				<input class="btn btn-primary" type="submit" value="<?php echo $data["mode"]; ?>" style="width: fit-content;">
			</div>
		</div>
	</form>
</div>
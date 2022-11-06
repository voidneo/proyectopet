<?php
$scrs  = $data["scrs"];
$e     = isset($_GET["e"]) ? $_GET["e"] : -1;
$color = $e > 0 ? "text-danger" : "text-success";
$msg   = "";

if($e == 0) $msg = "Notas actualizadas con &eacute;xito";
if($e == 1) $msg = "No se pudo encontrar la inscripci&oacute;";
if($e == 2) $msg = "No se pudo actualizar las notas";
if($e == 3) $msg = "Algunas de las notas no se actualizaron correctamente";
?>

<div class="container">
	<form action="./update_scores" method="POST" style="display: flex; flex-direction: column; margin: 2em auto;">
		<?php

		echo "<p class='$color'>$msg</p>";

		$i = 0;
		foreach ($scrs as $s) {
			$name     = $s->getMateria()->getNombre();
			$val      = $s->getNota();
			$mid      = $s->getMateria()->getId();

			if($i == 0) {
				echo "<div class='row g-3'>";
			}

			echo "<div class='col-sm-3'>";
			echo "<div class='form-group'>";
			echo "<label for='m-$mid'>$name</label>";
			echo "<input type='number' min='0' max='12' value='$val' name='m-$mid' class='form-control'>";
			echo "</div>";
			echo "</div>";

			if($i == 3){
				echo "</div>";
				$i = -1;
			}

			$i++;
		}
		if($i < 3) echo "</div>";
		echo "<input type='hidden' name='iid' value='" . $data["insc"]->getId() . "'>";
		?>
		<div class="row g-3" style="align-self: flex-end;">
			<input class="btn btn-primary" type="submit" value="Guardar">
		</div>
	</form>
</div>
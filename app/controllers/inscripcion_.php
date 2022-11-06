<?php

class Inscripcion_ extends Controller
{
	public function aceptar($data = [])
	{
		$id       = isset($_GET["id"])       ?                $_GET["id"]       : 0;
		$query    = isset($_GET["query"])    ? "&query="    . $_GET["query"]    : "";
		$page     = isset($_GET["page"])     ? "&page="     . $_GET["page"]     : 1;
		$rc       = isset($_GET["rc"])       ? "&rc="       . $_GET["rc"]       : 10;
		$approved = isset($_GET["approved"]) ? "&approved=" . $_GET["approved"] : 0;
		$ref      = "controlpanel?tab=inscriptions$query" . "$approved" . "$page" . "$rc";

		if (!isset($_SESSION["rol"]) || $_SESSION["rol"] != "e") {
			header("Location: ./../");
		}

		if (!$id) {
			header("Location: ./../$ref?e=1");
			return;
		}

		$this->load_model("Inscripcion");
		$i = new Inscripcion;

		$i = $i->findById($id);
		if ($i) {
			$i->setValido(1);

			if ($i->update())
				header("Location: ./../$ref&e=0");
			else
				header("Location: ./../$ref&e=3");
		} else {
			header("Location: ./../$ref&e=2");
		}
	}


	public function borrar($data = [])
	{
		$id       = isset($_GET["id"])       ?                $_GET["id"]       : 0;
		$query    = isset($_GET["query"])    ? "&query="    . $_GET["query"]    : "";
		$page     = isset($_GET["page"])     ? "&page="     . $_GET["page"]     : 1;
		$rc       = isset($_GET["rc"])       ? "&rc="       . $_GET["rc"]       : 10;
		$approved = isset($_GET["approved"]) ? "&approved=" . $_GET["approved"] : 0;
		$ref      = "controlpanel?tab=inscriptions$query" . "$approved" . "$page" . "$rc";

		if (!isset($_SESSION["rol"]) || $_SESSION["rol"] != "e") {
			header("Location: ./../");
		}

		if (!$id) {
			header("Location: ./../$ref?e=1");
			return;
		}

		$this->load_model("Inscripcion");
		$i = new Inscripcion;

		if ($i->delete($id))
			header("Location: ./../$ref&e=0");
		else
			header("Location: ./../$ref&e=4");
	}

	public function notas($data = []) {
		// If not logged in or not admin/teach, out
		if(!isset($_SESSION["rol"]) || $_SESSION["rol"] == "e") {
			header("Location: ./../");
		}
		
		$iid = isset($_GET["id"]) ? $_GET["id"] : 0;
		
		// If no inscription id, out
		if (!$iid) {
			header("Location: ./../");
		}
		
		$this->load_model("Inscripcion");
		$this->load_model("Curso");
		$this->load_model("Materia");
		$this->load_model("Nota");
		
		$insc = (new Inscripcion)->findById($iid);

		// If can't find inscripcion, go home
		if(!$insc) {
			header("Location: ./../");
		}
		
		$data["insc"]     = $insc;
		$data["curs"]     = $insc->getCurso();
		$data["user"]     = $insc->getUsuario();
		$data["mats"]     = $data["curs"]->getMaterias();
		$data["scrs"]     = $insc->getNotas();
		$data["path_fix"] = "../";

		$this->load_view("ScoresPage", $data);
	}

	public function update_scores($data = []) {

		error_reporting(-1);
        ini_set('display_errors', 'On');
        set_error_handler("var_dump");

		// If not logged in or not admin/teach, out
		if(!isset($_SESSION["rol"]) || $_SESSION["rol"] == "e") {
			//header("Location: ./../");
		}
		
		$iid = isset($_POST["iid"]) ? $_POST["iid"] : 0;

		// if no insc id, form has been tampered with, go back
		if(!$iid) {
			header("Location: ./../controlpanel?tab=inscriptions");
		}

		$this->load_model("Inscripcion");
		$this->load_model("Curso");
		$this->load_model("Nota");

		$insc = new Inscripcion;
		$insc = $insc->findById($iid);

		// Couldn't find the inscription
		if(!$insc) {
			header("Location: ./../inscripcion_/notas?id=$iid&e=1");
		}

		$scrs = $insc->getNotas();

		// Couldn't get the scores
		if(!$scrs) {
			header("Location: ./../inscripcion_/notas?id=$iid&e=1");
		}

		$success_rate = 0;
		$updated_scrs = 0;

		// For each score linked to the inscription
		foreach($scrs as $s) {
			$mid = $s->getMateria()->getId();

			// if a parameter matching score's ID was sent
			if(isset($_POST["m-$mid"])) {
				$sc = $_POST["m-$mid"];

				// and if value is different from existing score value
				if($sc != $s->getNota()) {

					// Update it
					$s->setNota($sc);
					$updated_scrs += 1;
					
					// if successful on update, increase success rate
					if($s->update())
						$success_rate += 1;
				}
			}
		}

		if($updated_scrs != 0) $success_rate /= $updated_scrs;
		else                   $success_rate  = 1;

		// If total success
		if($success_rate == 1) {
			header("Location: ./../inscripcion_/notas?id=$iid&e=0");
		}
		// If total failure
		else if($success_rate == 0) {
			header("Location: ./../inscripcion_/notas?id=$iid&e=2");
		}
		// If moderate success
		else {
			header("Location: ./../inscripcion_/notas?id=$iid&e=3");
		}
	}
}

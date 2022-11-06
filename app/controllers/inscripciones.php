<?php

class Inscripciones extends Controller
{
	public function index($data = [])
	{
		$this->load_view("InscriptionPage", $data);
	}

	public function send($data = [])
	{
		$fields = ["course", "periodo"];
		$uci = "";

		// if not logged in, require all fields
		if (!isset($_SESSION["cedula"])) {
			array_push($fields, "ci", "name", "surname", "email");
			$uci = $_POST["ci"];
		} else {
			$uci = $_SESSION["cedula"];
		}

		// Check all needed fields were sent
		foreach ($fields as $f) {
			if (!isset($_POST[$f])) {
				header("Location: ./../inscripciones?e=1");
				return;
			}
		}

		$this->load_model("Usuario");
		$u = new Usuario;
		$u = $u->findByCI($uci);

		// If user not registered, create an accout
		if (!$u) {
			$u = new Usuario;
			$u = $u->create($_POST["ci"], $_POST["name"], $_POST["surname"], $_POST["email"], password_hash($_POST["ci"], PASSWORD_DEFAULT));
			// If failed to create accout, send error message
			if (!$u) {
				header("Location: ./../inscripciones?e=2");
				return;
			}
		}

		// Create inscription
		$this->load_model("Inscripcion");
		$i = new Inscripcion;

		$i = $i->create($_POST["ci"], $_POST["course"], $_POST["periodo"]);

		if ($i) {
			header("Location: ./../inscripciones?e=0");
			return;
		} else {
			header("Location: ./../inscripciones?e=2");
			return;
		}
	}
}

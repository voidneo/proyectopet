<?php

class Materias extends Controller
{
	public function index($data = [])
	{
		$logged_in = isset($_SESSION["rol"]);
		$is_admin  = $_SESSION["rol"] != "e";
		$sid       = isset($_GET["sid"]) ? $_GET["sid"] : 0;
		$m = 0;

		if ($logged_in && $is_admin) {

			if ($sid) {
				$this->load_model("Materia");
				$m = new Materia;
				$m = $m->findById($sid);
			}

			if ($m) {
				$data["sid"]  = $sid;
				$data["mode"] = "Editar";
				$data["sub"]  = $m;
				$this->load_view("SubjectEditPage", $data);
			} else {
				$data["mode"] = "Crear";
				$this->load_view("SubjectEditPage", $data);
			}
		} else {
			header("Location: ./");
		}
	}

	public function send($data = [])
	{
		$id   = isset($_POST["id"])   ? $_POST["id"]   : 0;
		$name = isset($_POST["name"]) ? $_POST["name"] : 0;

		if (!$name) {
			header("Location: ./../materias" . ($id ? "?sid=$id" : ""));
		}

		$this->load_model("Materia");
		$m = new Materia;

		// If id, update
		if ($id) {
			$m = $m->findById($id);
			$m->setNombre($name);

			if ($m->update()) {
				header("Location: ./../materias?sid=$id&e=0");
			} else {
				header("Location: ./../materias?sid=$id&e=1");
			}
		}
		// If no id, create new
		else {
			if ($m->create($name)) {
				$m  = $m->findByNombre($name);
				$id = $m->getId();
				header("Location: ./../materias?sid=$id&e=0");
			} else {
				header("Location: ./../materias?sid=$id&e=1");
			}
		}
	}
}

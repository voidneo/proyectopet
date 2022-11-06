<?php

class Curso_ extends Controller
{
	public function index($data = [])
	{
		$logged_in = isset($_SESSION["rol"]);
		$is_admin  = $_SESSION["rol"] != "e";
		$cid       = isset($_GET["cid"]) ? $_GET["cid"] : 0;
		$c = 0;

		if ($logged_in && $is_admin) {

			if ($cid) {
				$this->load_model("Curso");
				$c = new Curso;
				$c = $c->findById($cid);
			}

			if($c && !is_null($c->getId())) {
				$data["cid"]    = $cid;
				$data["mode"]   = "Editar";
				$data["course"] = $c;
				$this->load_view("CourseEditPage", $data);
			} else {
				$data["mode"] = "Crear";
				$this->load_view("CourseEditPage", $data);
			}
		} else {
			header("Location: ./");
		}
	}

	public function send($data = [])
	{
		$id       = isset($_POST["id"])     ? $_POST["id"]     : 0;
		$name     = isset($_POST["name"])   ? $_POST["name"]   : 0;
		$length   = isset($_POST["length"]) ? $_POST["length"] : 0;
		$len_type = isset($_POST["period-type"]) ? $_POST["period-type"] : "a&ntilde;os";
		$subjects = [];

		// If selected, get all subjects into an array
		if (isset($_POST["subjects"])) {
			foreach ($_POST["subjects"] as $s) {
				array_push($subjects, $s);
			}
		}

		if (!$name || !$length || count($subjects) == 0) {
			header("Location: ./curso_");
		}

		$this->load_model("Curso");
		$this->load_model("Articulo");
		$c = new Curso;
		$a = new Articulo;

		// If id, update
		if ($id) {
			$c = $c->findById($id);

			// if course with such id exists
			if (!is_null($c->getId())) {
				$c->setNombre($name);
				$c->setDuracion("$length $len_type");

				// Update its values
				if ($c->update()) {

					$a = $a->findById($c->getIdArticulo());
					if(!is_null($a->getId())) {
						$a->setTitulo($name);
						$a->update();
					}

					$to_create = [];
					$to_delete = [];
					$mats      = $c->getMaterias();

					// Check for newly selected subjects
					foreach ($subjects as $s) {
						$create = 1;

						foreach ($mats as $m) {
							if ($m->getId() == $s) {
								$create = 0;
								break;
							}
						}
						// Mark for creation
						if ($create) array_push($to_create, $s);
					}

					// Check for unselected existing subjects
					foreach ($mats as $m) {
						$delete = 1;

						foreach ($subjects as $s) {
							if ($m->getId() == $s) {
								$delete = 0;
								break;
							}
						}
						// Mark for deletion
						if ($delete) array_push($to_delete, $m->getId());
					}

					$creation = 1;
					$deletion = 1;
					if (count($to_create)) {
						$creation = $c->addSubjects($c->getid(), $to_create);
					}
					if (count($to_delete)) {
						$deletion = $c->removeSubjects($c->getid(), $to_delete);
					}

					if ($creation && $deletion)
						header("Location: ./../curso_?cid=$id&e=0");
					else if(!$creation || !$deletion)
						header("Location: ./../curso_?cid=$id&e=1");
				} else { // Failed to update course
					header("Location: ./../curso_?cid=$id&e=2");
				}
			} else { // Failed to find the course
				header("Location: ./../curso_?cid=$id&e=8");
			}
		}
		// If no id, create new
		else {
			// if could create course
			if ($c->create($name, "$length $len_type")) {
				// Find it to get its id
				$c  = $c->findByNombre($name);

				// If course is found
				if ($c) {
					$id = $c->getId();

					// if could create an article for the course
					if ($a->create($name, "", 2, "")) {
						$d = new DateTime();
						$d = [
							"year"  => $d->format("Y"),
							"month" => $d->format("m"),
							"day"   => $d->format("d")
							// Find it to get its id
						];
						$a = $a->getAll($name, 2, $d)["results"][0];

						// If could find the article
						if ($a) {
							// Update course with article id
							$c->setIdArticulo($a["id"]);

							// If could update course's article id
							if ($c->update()) {

								// If any subjects to create
								if (count($subjects)) {
									$success_rate = $c->addSubjects($c->getId(), $subjects);

									if ($success_rate == 1)
										header("Location: ./../curso_?cid=$id&e=0");
									else if ($success_rate > 0)
										header("Location: ./../curso_?cid=$id&e=3");
									else if ($success_rate == 0)
										header("Location: ./../curso_?cid=$id&e=4");
								}
							} else { // Failed to update article id
								header("Location: ./../curso_?cid=$id&e=5");
							}
						} else { // Couldn't find the article
							header("Location: ./../curso_?cid=$id&e=6");
						}
					} else { // Failed to create article
						header("Location: ./../curso_?cid=$id&e=7");
					}
				} else { // Failed to find newly created course
					header("Location: ./../curso_?cid=$id&e=8");
				}
			} else { // Failed to create course
				header("Location: ./../curso_?cid=$id&e=9");
			}
		}
	}
}

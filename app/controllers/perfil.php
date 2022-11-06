<?php

class Perfil extends Controller
{

    private function logic($data, $view)
    {
        // If user's is logged in
        if (isset($_SESSION["token"])) {

            $this->load_model("Usuario");
            $usr = new Usuario;

            // If user id exists and current user is not a student
            if (isset($_GET["id"]) && $_SESSION["rol"] != "e") {
                $usr = $usr->findById($_GET["id"]);

                // Id user exists in DB
                if ($usr) {
                    $data["user"] = $usr;
                    $this->load_view($view, $data);
                    return;
                }
            }

            $usr = $usr->findById($_SESSION["id"]);

            // Id user exists in DB
            if ($usr) {
                $data["user"] = $usr;
                $this->load_view($view, $data);
                return;
            }
        }

        header("Location: ./");
    }

    public function index($data = [])
    {
        $data["path_fix"] = "";
        $data["cp_path_fix"] = "perfil/";
        $data["tab"]      = "cursos";
        $this->logic($data, "ProfilePage");
    }

    public function cursos($data = [])
    {
        $data["path_fix"] = "../";
        $data["cp_path_fix"] = "";
        $data["tab"]      = "cursos";
        $this->logic($data, "ProfilePage");
    }

    public function opciones($data = [])
    {
        $data["path_fix"] = "../";
        $data["cp_path_fix"] = "";
        $data["tab"]      = "opciones";
        $this->logic($data, "ProfilePage");

        if (isset($_GET["success"])) {
            if ($_GET["success"] == 1) echo "<script>alert('Datos actualizados correctamente')</script>";
            if ($_GET["success"] == 0) echo "<script>alert('Algo salio mal')</script>";
        }


        if (isset($_GET["error"])) {
            echo "<script>";
            if ($_GET["error"] == 1) echo "alert('La contrasena es incorrecta')";
            if ($_GET["error"] == 2) echo "alert('Las contrasenas no coinciden')";
            if ($_GET["error"] == 3) echo "alert('Acceso denegado')";
            if ($_GET["error"] == 4) echo "alert('No se encontro usuario con el id especificado')";
            if ($_GET["error"] == 5) echo "alert('Algo salio mal, no se puedo actualizar el usuario')";
            if ($_GET["error"] == 6) echo "alert('Algo salio mal, no se pudo borrar el usuario')";
            echo "</script>";
        }
    }

    public function notas($data = [])
    {
        $data["path_fix"] = "../";
        $data["cp_path_fix"] = "";
        $data["tab"]      = "scores";
        $this->logic($data, "ScoresPagePDF");
    }

    public function update_data($data = [])
    {
        // Profile id
        $pid   = isset($_POST["id"]) ? $_POST["id"] : 0;
        // Logged-in user id
        $uid   = $_SESSION["id"];
        $urole = $_SESSION["rol"];
        $self_profile = $pid == $uid;
        $is_admin     = $urole != "e";

        if ($self_profile || $is_admin) {

            $this->load_model("Usuario");
            $u = new Usuario;
            $u = $u->findById($pid);

            // If user exists
            if ($u) {

                // If role exists and editing user is admin, update
                if (isset($_POST["role"]) && $is_admin) {
                    $u->setRol($_POST["role"]);
                }

                // If name exists, update
                if (isset($_POST["name"])) {
                    $u->setNombre($_POST["name"]);
                }

                // If surname exists, update
                if (isset($_POST["surname"])) {
                    $u->setApellido($_POST["surname"]);
                }

                // If email exists, update
                if (isset($_POST["email"])) {
                    $u->setCorreo($_POST["email"]);
                }

                // If phone exists, update
                if (isset($_POST["phone"])) {
                    $u->setTelefono($_POST["phone"]);
                }

                // Persist changes
                if ($u->update()) {
                    if ($self_profile) {
                        $_SESSION["rol"] = $u->getRol();
                        $_SESSION["nombre"] = $u->getNombre();
                        $_SESSION["apellido"] = $u->getApellido();
                    }

                    header("Location: ./opciones?" . ($self_profile ? "" : "id=$pid&") . "success=1");
                    return;
                }
            }
        }

        header("Location: ./opciones?" . ($self_profile ? "" : "id=$pid&") . "success=0");
    }

    public function update_pwd($data = [])
    {
        $pid     = isset($_POST["id"])      ? $_POST["id"]      : 0;
        $pwd     = isset($_POST["pwd"])     ? $_POST["pwd"]     : 0;
        $newpwd  = isset($_POST["newpwd"])  ? $_POST["newpwd"]  : 0;
        $newpwd2 = isset($_POST["newpwd2"]) ? $_POST["newpwd2"] : 0;
        $uid   = $_SESSION["id"];
        $self_profile = $pid == $uid;

        $this->load_model("Usuario");
        $u = new Usuario;
        if ($pid) $u = $u->findById($pid);

        // If both pwd and user exist
        if (($u && $newpwd && $newpwd2)) {

            // if user isn't admin and tries to edit someone else's profile
            if($_SESSION["rol"] == "e" && $uid != $_SESSION["id"]) {
                header("Location: ./opciones?" . ($self_profile ? "" : "id=$pid&") . "error=3");
                return;
            }

            // If user is a regular user
            if ($_SESSION["rol"] == "e") {
                // If old password is correct
                if (password_verify($pwd, $u->getContrasena())) {
                    $u->setContrasena(password_hash($newpwd, PASSWORD_DEFAULT));
                } else {
                    header("Location: ./opciones?" . ($self_profile ? "" : "id=$pid&") . "error=1");
                    return;
                }
            } else { // If user is admin
                // Update password
                $u->setContrasena(password_hash($newpwd, PASSWORD_DEFAULT));
            }

            // If new pwd and confirmation don't match
            if ($newpwd != $newpwd2) {
                header("Location: ./opciones?" . ($self_profile ? "" : "id=$pid&") . "error=2");
                return;
            }

            // Persist changes
            if ($u->update()) {
                header("Location: ./opciones?" . ($self_profile ? "" : "id=$pid&") . "success=1");
                return;
            }
        }

        header("Location: ./opciones?" . ($self_profile ? "" : "id=$pid&") . "success=0");
        return;
    }

    public function allow_student($data = []) {
        $logged_in = isset($_SESSION["rol"]);
        $is_admin  = $_SESSION["rol"] != "e";
        $uid       = isset($_GET["uid"]) ? $_GET["uid"] :  0;
        $valid     = isset($_GET["v"])   ? $_GET["v"]   : -1;

        // If not logged-in, is not admin or missing any parameter
        if(!$logged_in || !$is_admin || !$uid || $valid == -1) {
            header("Location: ./../");  // Go back the way you came
            return;
        }
        
        $this->load_model("Usuario");
        $u = new Usuario;
        $u = $u->findById($uid);

        // Couldn't find the user
        if(!$u) {
            header("Location: ./../perfil/opciones?id=$uid&error=4");
            return;
        }

        $u->setValido($valid);
        if($u->update()) {
            // If user disallows self, log out
            if($uid == $_SESSION["id"]) header("Location: ./../logout");
            else                        header("Location: ./../perfil/opciones?id=$uid");
            return;
        }

        // Couldn't update user
        header("Location: ./../perfil/opciones?id=$uid&error=5");
    }

    public function delete_student($data = []) {
        $logged_in = isset($_SESSION["rol"]);
        $is_admin  = $_SESSION["rol"] != "e";
        $uid       = isset($_GET["uid"]) ? $_GET["uid"] :  0;

        // If not logged-in, is not admin or missing user id
        if(!$logged_in || !$is_admin || !$uid) {
            header("Location: ./../");  // Go back the way you came
            return;
        }
        
        $this->load_model("Usuario");
        $u = new Usuario;
        $u = $u->findById($uid);

        // Couldn't find the user
        if(!$u) {
            header("Location: ./../perfil/opciones?id=$uid&error=4");
            return;
        }

        if($u->delete($u->getCI())) {
            // If user deletes self, log out
            if($uid == $_SESSION["id"]) header("Location: ./../logout");
            else                        header("Location: ./../");
            return;
        }

        // Couldn't delete user
        header("Location: ./../perfil/opciones?id=$uid&error=6");
    }
}

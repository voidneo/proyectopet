<?php

class Controller {

    private static function resolvePath($path) {
        $folders    = explode("/", $path);
        $nOfFolders = count($folders) - 1;
        $script      = $folders[$nOfFolders];
        $path = "";

        for($i = 0; $i < $nOfFolders; $i++) {
            $path = $path . "$folders[$i]/";
        }

        return ["path" => $path, "name" => $script];
    }

    public function index() {}

    public function load_model($model) {
        require_once("../app/models/" . $model . ".php");
    }

    public function load_view($view, $data = []) {
        $script = self::resolvePath($view);
        require_once("../app/views/" . $script["path"] . $script["name"] . ".php");
    }

    public function load_controller($ctrl) {
        $script = self::resolvePath($ctrl);
        require_once("../app/controllers/" . $script["path"] . $script["name"] . ".php");
    }

    public function load_lib($lib) {
        $script = self::resolvePath($lib);
        require_once("../app/libs/" . $script["path"] . $script["name"] . ".php");
    }

}

?>
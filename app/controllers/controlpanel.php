<?php
class ControlPanel extends Controller {
    public function index($data = []) {
        $security_hash = hash("sha1", random_bytes(8));

        $_SESSION["security_hash"] = $security_hash;
        $data["security_hash"]     = $security_hash;

        $this->load_view("ControlPanel", $data);
    }
}
?>
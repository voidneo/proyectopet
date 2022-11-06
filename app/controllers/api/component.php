<?php

class Component extends ApiObject {

    public function index($data = [], $sendResponseOnSuccess = true) {
        if (!self::exist(["name"])) {
            self::send(self::STATUS_BAD_REQUEST, "Missing essential information");
            return;
        }

        //if (!self::isSecurityHashValid()) return;


        $this->load_view("components/" . $_GET["name"]);
    }

    public function category_list($data = []) {
        if (!self::isSecurityHashValid("GET")) return;

        $data["security_hash"] = $_GET["security_hash"];

        // TODO: adapt BASE_URL to work regardless of domain name
        $this->load_view("components/CategoryList", $data);
    }

    public function article_manager($data = []) {
        if (!self::isSecurityHashValid("GET")) return;

        $data["security_hash"] = $_GET["security_hash"];

        // TODO: adapt BASE_URL to work regardless of domain name
        $this->load_view("components/ArticleManager", $data);
    }
}

<?php

class Model {
    private const DB_HOST = "localhost";
    private const DB_NAME = "pet";
    private const DB_USER = "root";
    private const DB_PASS = "";
    protected $pdo = null;

    public function connect() {
        if(is_null($this->pdo)) {
            $this->pdo = new PDO(
                "mysql:host=" . self::DB_HOST . ";dbname=" . self::DB_NAME . ";charset=UTF8",
                self::DB_USER,
                self::DB_PASS
            );
        }

        return $this->pdo;
    }
}

<?php

namespace model;

class DataBase {

    protected $conn;

    public function __construct() {

        $host = 'localhost';
        $db = 'blog';
        $username = 'root';
        $password = '';

        try {
            $this->conn = new \PDO('mysql:host=' . $host . ';dbname=' . $db, $username, $password);
            $this->conn->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_OBJ);
        } catch (PDOException $ex) {
            throw new $ex;
        }
    }

}

<?php

namespace model;

class Session extends DataBase {

    private $table;

    public function __construct() {
        parent::__construct();

        $this->table = 'sessions';
    }

    public function all(): array {

        $stmt = $this->conn->query('SELECT * FROM ' . $this->table);
        if (!$stmt) {
            return [];
        }
        $result = $stmt->fetchAll();
        return $result;
    }

    public function findById() {

        $stmt = $this->conn->prepare('SELECT * FROM ' . $this->table . ' WHERE id = :id');
        if (!$stmt) {
            return null;
        }
        $stmt->execute([
            'id' => $session->id
        ]);
        $result = $stmt->fetch();
        return $result ? $result : null;
    }

    public function findByAccessToken() {

        $stmt = $this->conn->prepare('SELECT * FROM ' . $this->table . ' WHERE accesstoken = :accesstoken AND id = :id');
        if (!$stmt) {
            return null;
        }
        $stmt->execute([
            'accesstoken' => $session->accesstoken,
            'id' => $session->id
        ]);
        $result = $stmt->fetch();
        return $result ? $result : null;
    }

    public function findByRefreshToken() {

        $stmt = $this->conn->prepare('SELECT * FROM ' . $this->table . ' WHERE accesstoken = :accesstoken AND refreshtoken = :refreshtoken AND id = :id');
        if (!$stmt) {
            return null;
        }
        $stmt->execute([
            'accesstoken' => $session->accesstoken,
            'refreshtoken' => $session->refreshtoken,
            'id' => $session->id
        ]);
        $result = $stmt->fetch();
        return $result ? $result : null;
    }

    public function add(\controller\Session $session) {

        $stmt = $this->conn->prepare('INSERT INTO ' . $this->table . ' (userid, accesstoken, accesstokenexpiry, refreshtoken, refreshtokenexpiry) VALUES (:userid, :accesstoken, date_add(NOW(), INTERVAL :accesstokenexpiry SECOND), :refreshtoken, date_add(NOW(), INTERVAL :refreshtokenexpiry SECOND))');
        $stmt->execute([
            'userid' => $session->userid,
            'accesstoken' => $session->accesstoken,
            'accesstokenexpiry' => $session->accesstokenexpiry,
            'refreshtoken' => $session->refreshtoken,
            'refreshtokenexpiry' => $session->refreshtokenexpiry
        ]);
        $last_id = $this->conn->lastInsertId();
        return $last_id;
    }

    public function udpade(\controller\Session $session) {

        $stmt = $this->conn->prepare('UPDATE ' . $this->table . ' SET userid = :userid, accesstoken = :accesstoken, accesstokenexpiry = :accesstokenexpiry, refreshtoken = :refreshtoken, refreshtokenexpiry = :refreshtokenexpiry WHERE id = :id');
        if (!$stmt) {
            return null;
        }
        $stmt->execute([
            'userid' => $session->userid,
            'accesstoken' => $session->accesstoken,
            'accesstokenexpiry' => $session->accesstokenexpiry,
            'refreshtoken' => $session->refreshtoken,
            'refreshtokenexpiry' => $session->refreshtokenexpiry,
            'id' => $session->id
        ]);
        $rowCount = $stmt->rowCount();
        return $rowCount;
    }

    public function delete(\controller\Session $session) {

        $stmt = $this->conn->prepare('DELETE FROM ' . $this->table . ' WHERE accesstoken = :accesstoken AND id = :id');
        if (!$stmt) {
            return null;
        }
        $stmt->execute([
            'accesstoken' => $session->accesstoken,
            'id' => $session->id
        ]);
        $rowCount = $stmt->rowCount();
        return $rowCount;
    }

}

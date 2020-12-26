<?php

namespace model;

class User extends DataBase {

    private $table;

    public function __construct() {
        parent::__construct();

        $this->table = 'user';
    }

    public function all(): array {

        $stmt = $this->conn->query('SELECT * FROM ' . $this->table);
        if (!$stmt) {
            return [];
        }
        $result = $stmt->fetchAll();
        return $result;
    }

    public function find(int $id) {

        $stmt = $this->conn->prepare('SELECT * FROM ' . $this->table . ' WHERE id = :id');
        if (!$stmt) {
            return null;
        }
        $stmt->execute([
            'id' => $id
        ]);
        $result = $stmt->fetch();
        return $result ? $result : null;
    }

    public function findByUsername($username) {

        $stmt = $this->conn->prepare('SELECT * FROM ' . $this->table . ' WHERE username = :username');
        if (!$stmt) {
            return null;
        }
        $stmt->execute([
            'username' => $username
        ]);
        $result = $stmt->fetch();
        return $result ? $result : null;
    }

    public function add(\controller\User $user) {

        $stmt = $this->conn->prepare('INSERT INTO ' . $this->table . ' (username, password, email, loginattempts) VALUES (:username, :password, :email, 0)');
        if (!$stmt) {
            return null;
        }
        $stmt->execute([
            'username' => $user->username,
            'password' => $user->password,
            'email' => $user->email
        ]);
        $last_id = $this->conn->lastInsertId();
        return $last_id;
    }

    public function udpade(\controller\User $user) {

        $stmt = $this->conn->prepare('UPDATE ' . $this->table . ' SET username = :username, password = :password, email = :email, loginattempts = :loginattempts WHERE id = :id');
        if (!$stmt) {
            return null;
        }
        $stmt->execute([
            'username' => $user->username,
            'password' => $user->password,
            'email' => $user->email,
            'loginattempts' => $user->loginattempts,
            'id' => $user->id
        ]);
        $rowCount = $stmt->rowCount();
        return $rowCount;
    }

    public function delete(\controller\User $user) {

        $stmt = $this->conn->prepare('DELETE FROM ' . $this->table . ' WHERE id = :id');
        if (!$stmt) {
            return null;
        }
        $stmt->execute([
            'id' => $user->id
        ]);
        $rowCount = $stmt->rowCount();
        return $rowCount;
    }

}

<?php

namespace model;

class Post extends DataBase {

    private $table;

    public function __construct() {
        try {
            parent::__construct();
        } catch (Exception $ex) {
            throw new $ex;
        }

        $this->table = 'post';
    }

    public function all(): array {
        
        $stmt = $this->conn->query('SELECT * FROM ' . $this->table);
        $result = $stmt->fetchAll();
        return $result;
    }

    public function find(int $id) {

        $stmt = $this->conn->prepare('SELECT * FROM ' . $this->table . ' WHERE id = :id');
        $stmt->execute([
            'id' => $id
        ]);
        $result = $stmt->fetch();
        return $result ? $result : null;
    }

    public function add(\controller\Post $post) {

        $stmt = $this->conn->prepare('INSERT INTO ' . $this->table . ' (name, content, photo) VALUES (:name, :content, :photo)');
        $stmt->execute([
            'name' => $post->name,
            'content' => $post->content,
            'photo' => $post->photo
        ]);
        $last_id = $this->conn->lastInsertId();
        return $last_id;
    }

    public function udpade(\controller\Post $post) {

        $stmt = $this->conn->prepare('UPDATE ' . $this->table . ' SET name = :name, content = :content, photo = :photo WHERE id = :id');
        $stmt->execute([
            'name' => $post->name,
            'content' => $post->content,
            'photo' => $post->photo,
            'id' => $post->id
        ]);
        $rowCount = $stmt->rowCount();
        return $rowCount;
    }

    public function delete(\controller\Post $post) {

        $stmt = $this->conn->prepare('DELETE FROM ' . $this->table . ' WHERE id = :id');
        $stmt->execute([
            'id' => $post->id
        ]);
        $rowCount = $stmt->rowCount();
        return $rowCount;
    }

}

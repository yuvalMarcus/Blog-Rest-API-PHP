<?php

namespace controller;

class User {

    private $model;
    public $id;
    public $username;
    public $password;
    public $email;
    public $loginattempts;

    public function __construct(int $id = 0, string $username = '', string $password = '', string $email = '', string $loginattempts = '') {

        $this->model = new \model\User();

        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
        $this->loginattempts = $loginattempts;
    }

    public function all() {

        return $this->model->all();
    }

    public function getByUsername() {

        return $this->model->findByUsername($this->username);
    }

    public function get() {

        return $this->model->find($this->id);
    }

    public function store() {

        return $this->model->add($this);
    }

    public function save() {

        return $this->model->udpade($this);
    }

    public function remove() {

        return $this->model->delete($this);
    }

}

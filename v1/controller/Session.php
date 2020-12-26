<?php

namespace controller;

class Session {

    private $model;
    public $id;
    public $userid;
    public $accesstoken;
    public $accesstokenexpiry;
    public $refreshtoken;
    public $refreshtokenexpiry;

    public function __construct(int $id = 0, int $userid = 0, string $accesstoken = '', string $accesstokenexpiry = '', string $refreshtoken = '', string $refreshtokenexpiry = '') {

        $this->model = new \model\Session();

        $this->id = $id;
        $this->userid = $userid;
        $this->accesstoken = $accesstoken;
        $this->accesstokenexpiry = $accesstokenexpiry;
        $this->refreshtoken = $refreshtoken;
        $this->refreshtokenexpiry = $refreshtokenexpiry;
    }

    public function all() {

        return $this->model->all();
    }

    public function getById() {

        return $this->model->findById($this);
    }

    public function getByAccessToken() {

        return $this->model->findByAccessToken($this);
    }

    public function getByRefreshToken() {

        return $this->model->findByRefreshToken($this);
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

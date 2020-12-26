<?php
namespace controller;

class Post {

    private $model;
    public $id;
    public $name;
    public $content;
    public $photo;

    public function __construct(int $id = 0, string $name = '', string $content = '', string $photo = '') {

        $this->model = new \model\Post();

        $this->id = $id;
        $this->name = $name;
        $this->content = $content;
        $this->photo = $photo;
    }

    public function all() {

        return $this->model->all();
    }

    public function find() {

        return $this->model->get($this->id);
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

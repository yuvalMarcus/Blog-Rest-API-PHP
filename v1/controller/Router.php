<?php
namespace controller;

class Router {

    private $_url;
    private $_response;
    private $_choice;

    public function __construct() {
        $url = !empty($_GET['url']) ? $_GET['url'] : '';
        $this->_url = rtrim($url, '/');
        $this->_response = new \controller\Response();
        $this->_choice = false;
    }

    private function checkUrl(string $url, array &$param): bool {

        $existUrlArray = explode('/', $url);
        $currentUrlArray = explode('/', $this->_url);

        $existUrlArrayCount = count($existUrlArray);
        $currentUrlArrayCount = count($currentUrlArray);

        if ($existUrlArrayCount !== $currentUrlArrayCount) {

            return false;
        }

        $result = true;

        for ($i = 0; $result && $i < $existUrlArrayCount; $i++) {

            if ($existUrlArray[$i] === $currentUrlArray[$i]) {
                $result = true;
            } elseif ($existUrlArray[$i][0] === ':') {
                
                $param[] = $currentUrlArray[$i];
                                
                $result = true;
            } else {
                $result = false;
            }
        }

        return $result;
    }

    public function get($url, $callback): void {

        if ($this->_choice)
            return;

        if ($_SERVER['REQUEST_METHOD'] == 'GET') {

            $param = [];

            if ($this->checkUrl($url, $param)) {

                $this->_choice = true;
                $callback($this->_response, ...$param);
            }
        }
    }

    public function post($url, $callback): void {

        if ($this->_choice)
            return;

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $param = [];

            if ($this->checkUrl($url, $param)) {

                $this->_choice = true;
                $callback($this->_response, ...$param);
            }
        }
    }
    
    public function put($url, $callback): void {

        if ($this->_choice)
            return;

        if ($_SERVER['REQUEST_METHOD'] == 'PUT') {

            $param = [];

            if ($this->checkUrl($url, $param)) {

                $this->_choice = true;
                $callback($this->_response, ...$param);
            }
        }
    }
    
        public function delete($url, $callback): void {

        if ($this->_choice)
            return;

        if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {

            $param = [];

            if ($this->checkUrl($url, $param)) {

                $this->_choice = true;
                $callback($this->_response, ...$param);
            }
        }
    }

    public function end($callback): void {

        if ($this->_choice)
            return;

        $this->_choice = true;
        $callback($this->_response);
    }

}

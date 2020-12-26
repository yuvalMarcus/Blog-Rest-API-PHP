<?php

/*
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
*/
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");

$_POST = json_decode(file_get_contents('php://input'), true);

require_once './hepler/hepler.php';

spl_autoload_register(function($file) {
    
    if (is_file("./$file.php")) {

        require "./$file.php";
    }
});

require_once './router/api.php';
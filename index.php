<?php
//inner dependencies
require 'php/request.php';

//error reporting
ini_set('display_errors',1);
error_reporting(E_ALL);

//requirements
require_once('vendor/autoload.php');

//instantiate base F3 base class
$f3 = Base::instance();

//define default route
$f3->route('GET|POST /', function(){
    if($_SERVER['REQUEST_METHOD'] == 'GET') {
        $view = new Template();
        echo $view->render('views/home.html');
    } else if($_SERVER['REQUEST_METHOD'] == 'POST') {
        echo getToken();
    }
});
//run fat free
$f3->run();
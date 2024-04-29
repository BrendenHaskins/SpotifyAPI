<?php
//error reporting
ini_set('display_errors',1);
error_reporting(E_ALL);

//requirements
require_once('vendor/autoload.php');
require('php/request.php');

//instantiate base F3 base class
$f3 = Base::instance();

//define default route
$f3->route('GET|POST /', function(){
    if($_SERVER['REQUEST_METHOD'] == 'GET') {
        $view = new Template();
        echo $view->render('views/home.html');
    } else {
        $view = new Template();
        echo 'NOT GET received...'.$view->render('views/home.html').getToken();
    }
});
//run fat free
$f3->run();
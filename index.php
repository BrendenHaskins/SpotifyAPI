<?php
//controller file
session_start();

//error reporting
ini_set('display_errors',1);
error_reporting(E_ALL);

//requirements
require('model/request.php');
require_once('vendor/autoload.php');

//instantiate base F3 base class
$f3 = Base::instance();
$con = new Controller($f3);

//define default route
$f3->route('GET|POST /', function() {
    $GLOBALS['con']->homePage();
});

//route to play a game
$f3->route('GET|POST /game', function($f3){
    $GLOBALS['con']->game();
});

$f3->route('GET|POST /victory', function($f3) {
    $GLOBALS['con']->victory();
});

$f3->route('GET|POST /defeat', function($f3) {
    $GLOBALS['con']->defeat();
});

//run fat free
$f3->run();
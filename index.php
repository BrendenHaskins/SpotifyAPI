<?php
//controller file

//error reporting
ini_set('display_errors',1);
error_reporting(E_ALL);

//requirements
require('model/request.php');
require_once('vendor/autoload.php');

// Start session
session_start();

//instantiate base F3 base class
$f3 = Base::instance();
$con = new Controller($f3);
$query = new Query();

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

$f3->route('GET|POST /login', function() {
   $GLOBALS['con']->login();
});

$f3->route('GET|POST /signup', function() {
   $GLOBALS['con']->signup();
});

$f3->route('GET|POST /guest', function() {
   $GLOBALS['con']->guest();
});

$f3->route('GET|POST /logout', function() {
   $GLOBALS['con']->logout();
});


//run fat free
$f3->run();
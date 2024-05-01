<?php
session_start();

//error reporting
ini_set('display_errors',1);
error_reporting(E_ALL);

//requirements
require ('php/request.php');
require_once('vendor/autoload.php');

//instantiate base F3 base class
$f3 = Base::instance();

//define default route
$f3->route('GET|POST /', function($f3){
    if($_SERVER['REQUEST_METHOD'] == 'GET') {
        //GET method: just show the home page.
        $view = new Template();
        echo $view->render('views/home.html');
    } else {
        //POST method: reroute to token, and call respective method to get said token!
        //at this point, the hive ($f3->) is aware of SESSION.accessToken
        $f3->reroute("token");
        getToken($f3);
    }
});

$f3->route('GET|POST /token', function($f3){
    if($_SERVER['REQUEST_METHOD'] == 'GET') {
        //GET method: shows the token page. Could probably get rid of this
        $view = new Template();
        echo $view->render('views/token.html');
    } else {
        makeArtistInfoRequest($_POST['url'], $f3);
        $view = new Template();
        echo $view->render('views/artist.html');
    }
});

//run fat free
$f3->run();
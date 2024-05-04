<?php
//controller file
session_start();

//error reporting
ini_set('display_errors',1);
error_reporting(E_ALL);

//requirements
require('php/request.php');
require_once('vendor/autoload.php');

//instantiate base F3 base class
$f3 = Base::instance();

//define default route
$f3->route('GET|POST /', function($f3){
    if($_SERVER['REQUEST_METHOD'] == 'GET') {
        //GET method: just show the home page.
        $view = new Template();
        echo $view->render('views/home.html').getToken($f3).getAllArtistsFromPlaylist('2o7xQn5eHWYpoNziBkcxEL',$f3);
    }
});
//run fat free
$f3->run();
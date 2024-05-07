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

//define default route
$f3->route('GET|POST /', function($f3){
    if($_SERVER['REQUEST_METHOD'] == 'GET') {
        //TESTING: run all necessary functions to choose a hidden artist. Display that artist.

        $view = new Template();
        getToken($f3);
        getAllArtistsFromSetUrl($f3);
        selectHiddenArtist($f3);

        $printArtist = $f3->get('SESSION.hiddenArtist');
        getHiddenArtistInfo($f3);
        echo $view->render('views/home.html').$printArtist;
    }
});
//run fat free
$f3->run();
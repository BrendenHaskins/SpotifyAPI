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
$controller = new

//define default route
$f3->route('GET|POST /', function() {
    if($_SERVER['REQUEST_METHOD'] == 'GET') {
        $view = new Template();
        echo $view->render('views/app.html');
    }
});

//route to play a game
$f3->route('GET|POST /game', function($f3){
    if($_SERVER['REQUEST_METHOD'] == 'GET') {
        //Present the guess screen, print all former guesses.
        $f3->set('SESSION.guessCount',0);

        $f3->set('allGuesses',array());


        $view = new Template();
        getToken($f3);
        getAllArtistsFromSetUrl($f3);
        selectHiddenArtist($f3);

        $printArtist = $f3->get('SESSION.hiddenArtist');
        getHiddenArtistInfo($f3);


        echo $view->render('views/home.html');
    }
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        //A guess has been submitted.
        $oldCount = $f3->get('SESSION.guessCount');
        $f3->set('SESSION.guessCount', $oldCount+1);

        $userGuess = $_POST['guess'];

        $f3->set('allGuesses',$f3->get('allGuesses').$userGuess);

        $hiddenArtist = $f3->get('SESSION.hiddenArtist');

        if($userGuess == $hiddenArtist) {
            $f3->reroute('victory');
        } else {
            $view = new Template();
            echo $view->render('views/home.html');
        }
    }
});

$f3->route('GET|POST /victory', function($f3) {
    if($_SERVER['REQUEST_METHOD'] == 'GET') {
        //User got it right.

        $view = new Template();
        echo $view->render('views/victory.html');
    } else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        //TESTING

    }
});

//run fat free
$f3->run();
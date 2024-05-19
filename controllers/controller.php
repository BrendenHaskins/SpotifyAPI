<?php


//Controller class, handles methods called from the router.

class Controller {
    private $_f3; // f3 router
    const MAXGUESSES = 10;

    function __construct($f3) {
        $this->_f3 = $f3;
    }



    function homePage() : void {
        if($_SERVER['REQUEST_METHOD'] == 'GET') {
            $view = new Template();
            echo $view->render('views/app.html');
        }
    }

    function game() : void {
        if($_SERVER['REQUEST_METHOD'] == 'GET') {
            //Present the guess screen, print all former guesses.
            $this->_f3->set('SESSION.guessCount',0);
            $this->_f3->clear('SESSION.artistArrays');


            $view = new Template();
            getToken($this->_f3);
            getAllArtistsFromSetUrl($this->_f3);
            selectHiddenArtist($this->_f3);
            getHiddenArtistInfo($this->_f3);


            echo $view->render('views/home.html');
        } else if($_SERVER['REQUEST_METHOD'] == 'POST') {
            //A guess has been submitted.
            $oldCount = $this->_f3->get('SESSION.guessCount');
            $newCount = $oldCount+1;
            $this->_f3->set('SESSION.guessCount', $newCount);


            $userGuess = $_POST['guess'];

            if($oldCount == 0) {
                $this->_f3->set('SESSION.artistArrays', array(searchForArtist($userGuess, $this->_f3)));
            } else {
                $oldArtistsArr = $this->_f3->get('SESSION.artistArrays');
                $oldArtistsArr[] = searchForArtist($userGuess, $this->_f3);
                $this->_f3->set('SESSION.artistArrays', $oldArtistsArr);
            }

            $hiddenArtist = $this->_f3->get('SESSION.hiddenArtist');

            if($userGuess == $hiddenArtist) {
                $this->_f3->reroute('victory');
            } else {
                if($newCount == 10) {
                    $this->_f3->reroute('defeat');
                }
                $view = new Template();
                echo $view->render('views/home.html');
            }
        }
    }

    function victory() : void {
        if($_SERVER['REQUEST_METHOD'] == 'GET') {
            //User got it right.

            $view = new Template();
            echo $view->render('views/victory.html');
        }
    }

    function defeat() : void {
        if($_SERVER['REQUEST_METHOD'] == 'GET') {
             $view = new Template();
             echo $view->render('views/defeat.html');
        }
    }
}
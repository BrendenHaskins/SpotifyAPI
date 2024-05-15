<?php


//Controller class, handles methods called from the router.

class Controller {
    private $_f3; // f3 router

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
            $this->_f3->clear('SESSION.allGuesses');

            //$this->_f3->set('allGuesses',array());


            $view = new Template();
            getToken($this->_f3);
            getAllArtistsFromSetUrl($this->_f3);
            selectHiddenArtist($this->_f3);
            getHiddenArtistInfo($this->_f3);


            echo $view->render('views/home.html');
        } else if($_SERVER['REQUEST_METHOD'] == 'POST') {
            //A guess has been submitted.
            $oldCount = $this->_f3->get('SESSION.guessCount');
            $this->_f3->set('SESSION.guessCount', $oldCount+1);

            $userGuess = $_POST['guess'];

            if($oldCount == 0) {
                $this->_f3->set('SESSION.allGuesses', array($userGuess));
            } else {
                $oldGuessesArr = $this->_f3->get('SESSION.allGuesses');
                $oldGuessesArr[] = $userGuess;
                $this->_f3->set('SESSION.allGuesses', $oldGuessesArr);
            }

            //$this->_f3->set('SESSION.allGuesses',$this->_f3->get('SESSION.allGuesses').$userGuess);

            $hiddenArtist = $this->_f3->get('SESSION.hiddenArtist');

            if($userGuess == $hiddenArtist) {
                $this->_f3->reroute('victory');
            } else {
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
}
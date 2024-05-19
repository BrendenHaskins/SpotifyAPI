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
            //reset all session arrays, it's a new game now.
            $this->_f3->set('SESSION.guessCount',0);
            $this->_f3->clear('SESSION.artistArrays');
            $this->_f3->clear('SESSION.hints');




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
                //init array
                $this->_f3->set('SESSION.artistArrays', array(searchForArtist($userGuess, $this->_f3)));

                //init array
                $this->_f3->set('SESSION.hints',array());
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
                } else if($newCount == 5) {
                    $oldHintsArr = $this->_f3->get('SESSION.hints');
                    $oldHintsArr[] = "Guess 5 hint";
                    $this->_f3->set('SESSION.hints', $oldHintsArr);
                } else if($newCount == 8) {
                    $oldHintsArr = $this->_f3->get('SESSION.hints');
                    $oldHintsArr[] = "Guess 8 hint";
                    $this->_f3->set('SESSION.hints', $oldHintsArr);
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
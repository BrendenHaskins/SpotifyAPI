<?php


//Controller class, handles methods called from the router.

class Controller {
    private $_f3; // f3 router

    function __construct($f3) {
        $this->_f3 = $f3;
    }



    function homePage() : void {
        if($_SERVER['REQUEST_METHOD'] == 'GET') {
            $this->setBoilerplateContent($this->_f3, 'views/app.html', array('app-home.css'));
        }
    }

    function game() : void {
        if($_SERVER['REQUEST_METHOD'] == 'GET') {
            //reset all session arrays, it's a new game now.
            $this->_f3->set('SESSION.guessCount',0);
            $this->_f3->clear('SESSION.artistArrays');
            $this->_f3->clear('SESSION.hints');
            $this->_f3->clear('SESSION.hiddenTopSong');





            getToken($this->_f3);
            getAllArtistsFromSetUrl($this->_f3);
            selectHiddenArtist($this->_f3);
            getHiddenArtistInfo($this->_f3);
        } else if($_SERVER['REQUEST_METHOD'] == 'POST') {
            //A guess has been submitted.
            $oldCount = $this->_f3->get('SESSION.guessCount');
            $newCount = $oldCount+1;
            $this->_f3->set('SESSION.guessCount', $newCount);


            $userGuess = $_POST['guess'];

            if($oldCount == 0) {
                //anything in this block should happen only once a game, initialize arrays, set globals etc...

                //TEST
                getHiddenArtistTopSong($this->_f3);
                getHiddenArtistPhoto($this->_f3);

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
                    $oldHintsArr[] = "Artist's top song: ".$this->_f3->get('SESSION.hiddenTopSong');
                    $this->_f3->set('SESSION.hints', $oldHintsArr);
                } else if($newCount == 8) {
                    $oldHintsArr = $this->_f3->get('SESSION.hints');
                    $oldHintsArr[] = $this->_f3->get('SESSION.hiddenPhotoURL');
                    $this->_f3->set('SESSION.hints', $oldHintsArr);
                }
            }
        }
        $this->setBoilerplateContent($this->_f3, 'views/home.html', array('autocomplete.css'), array('javascript/searchautocomplete.js'));
    }

    function victory() : void {
        if($_SERVER['REQUEST_METHOD'] == 'GET') {
            //User got it right.
            $this->setBoilerplateContent($this->_f3, 'views/victory.html', array());
        }
    }

    function defeat() : void {
        if($_SERVER['REQUEST_METHOD'] == 'GET') {
            $this->setBoilerplateContent($this->_f3, 'views/defeat.html', array());
        }
    }

    // setting routes to the boilerplate view
    function setBoilerplateContent($f3, string $content, array $styles, array $scripts = array()) : void {
        $f3->set('styles', $styles);
        $f3->set('scripts', $scripts);
        $f3->set('content', $content);

        echo Template::instance()->render('views/boilerplateBase.html');

    }
}
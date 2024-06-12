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
            $this->_f3->set('SESSION.priorGuesses',array());
            $this->_f3->clear('SESSION.artistArrays');
            $this->_f3->clear('SESSION.songHint');
            $this->_f3->clear('SESSION.photoHint');
            $this->_f3->clear('SESSION.hiddenTopSong');
            $this->_f3->clear('SESSION.hiddenPhotoURL');





            getToken($this->_f3);
            getAllArtistsFromSetUrl($this->_f3);
            selectHiddenArtist($this->_f3);
            getHiddenArtistInfo($this->_f3);
        } else if($_SERVER['REQUEST_METHOD'] == 'POST') {
            //A guess has been submitted.
            getToken($this->_f3);
            getAllArtistsFromSetUrl($this->_f3);
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

                //add first guess
                $guessArray = $this->_f3->get('SESSION.priorGuesses');
                $guessArray[] = $userGuess;
                $this->_f3->set('SESSION.priorGuesses',$guessArray);
            } else {
                $oldArtistsArr = $this->_f3->get('SESSION.artistArrays');

                //CHECK DUPLICATE GUESSES
                if(in_array($userGuess,$this->_f3->get('SESSION.priorGuesses'))){
                    echo '<div class="alert alert-warning" role="alert">You already guessed '.$userGuess.'.</div>';
                    $newCount = $oldCount;
                    $this->_f3->set('SESSION.guessCount', $newCount);
                } else {
                    $guessArray = $this->_f3->get('SESSION.priorGuesses');
                    $guessArray[] = $userGuess;
                    $this->_f3->set('SESSION.priorGuesses',$guessArray);
                    $oldArtistsArr[] = searchForArtist($userGuess, $this->_f3);
                    $this->_f3->set('SESSION.artistArrays', $oldArtistsArr);
                }
            }

            $hiddenArtist = $this->_f3->get('SESSION.hiddenArtist');

            if($userGuess == $hiddenArtist) {
                //TODO: Add increment scoring
                $this->_f3->reroute('victory');
            } else {
                if($newCount == 10) {
                    $this->_f3->reroute('defeat');
                } else if($newCount == 5) {
                    $this->_f3->set('SESSION.songHint', $this->_f3->get('SESSION.hiddenTopSong'));
                } else if($newCount == 8) {
                    $this->_f3->set('SESSION.photoHint', $this->_f3->get('SESSION.hiddenPhotoURL'));
                }
            }
        }
        $this->setBoilerplateContent(
            $this->_f3,
            'views/home.html',
            array('autocomplete.css', 'guessStyles.css'),
            array('searchautocomplete.js'));
    }

    function victory() : void {
        if($_SERVER['REQUEST_METHOD'] == 'GET') {
            //User got it right.
            $this->_f3->set('leaderboard', $GLOBALS['query']->getLeaderboard());
            $this->setBoilerplateContent($this->_f3, 'views/victory.html', array());
        }
    }

    function defeat() : void {
        if($_SERVER['REQUEST_METHOD'] == 'GET') {
            $this->_f3->set('leaderboard', $GLOBALS['query']->getLeaderboard());
            $this->setBoilerplateContent($this->_f3, 'views/defeat.html', array());
        }
    }

    /**
     * Brings the user to the login page where they need to login
     *
     * @return void
     */
    function login()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user = Validate::validateUser($this->_f3);

            if(empty($this->_f3->get('errors'))) {
                //checks database if already existing user exists
                $result = $GLOBALS['query']->checkUserPass($user);

                //checks if password hash matches
                $verifyPassword = password_verify($_POST['password'], $result['password']);
                if($verifyPassword) {
                    //TODO: Set session to logged in
                    $this->_f3->reroute('game');
                } else {
                    $this->_f3->set('errors["incorrectLogin"]', false);
                }
            }
        }

        $this->_f3->set('details', 'Log in');
        //render page
        $this->setBoilerplateContent($this->_f3, 'views/login.html', array());
    }


    /**
     * Brings the user to the signup page where they need to create an account
     *
     * @return void
     */
    function signup()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user = Validate::validateUser($this->_f3);
            Validate::checkDoublePassword($this->_f3);

            if(empty($this->_f3->get('errors'))) {
                //checks database if already existing user exists
                $result = $GLOBALS['query']->checkUserName($user);
                if($user->getName() == $result) {
                    $this->_f3->set('errors["userExists"]', false);
                } else {
                    $GLOBALS['query']->insertUser($user);
                    //TODO: Set session to logged in
                    $this->_f3->reroute('game');
                }
            }
        }
        $this->_f3->set('details', 'Sign up');
        //render page
        $this->setBoilerplateContent($this->_f3, 'views/login.html', array());
    }

    /**
     * Logs the user out, destroying the session
     *
     * @return void
     */
    function logout()
    {
        session_destroy();
        $this->_f3->reroute('/');
    }

    // setting routes to the boilerplate view
    function setBoilerplateContent($f3, string $content, array $styles, array $scripts = array()) : void
    {
        $f3->set('styles', $styles);
        $f3->set('scripts', $scripts);
        $f3->set('content', $content);

        echo Template::instance()->render('views/boilerplateBase.html');

    }
}
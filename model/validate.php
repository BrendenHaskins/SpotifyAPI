<?php

/*
 * Validate the signup and login form
 *
 * Also includes color logic for guesses
 */
class Validate {

    private $_f3; // f3 router

    function __construct($f3) {
        $this->_f3 = $f3;
    }

    /**
     * Checks if name is greater than 3 and less than 100
     *
     * @param $name
     * @return bool
     */
    static function validateName($name): bool
    {
        return strlen(trim($name)) >= 3 && strlen(trim($name)) < 100;
    }

    /**
     * Checks if the password has a capital letter and a number within 8-16 characters
     *
     * @param $password
     * @return false|int
     */
    static function validatePassword($password)
    {
        return preg_match("/^(?=.*\d)(?=.*[a-zA-Z])[a-zA-Z\d!@#$%&*_\-.]{8,16}$/", $password);
    }

    /**
     * Creates a user and assigns errors if errors occur
     *
     * @param $f3
     * @return User created object
     */
    static function validateUser($f3)
    {
        $name = "";
        $password = "";

        //checks username
        if (Validate::validateName($_POST['username'])) {
            $name = trim($_POST['username']);
        } else {
            $f3->set('errors["username"]', false);
        }
        //checks password
        if (Validate::validatePassword($_POST['password'])) {
            $password = $_POST['password'];
        } else {
            $f3->set('errors["password"]', false);
        }

        //creates a new user class
        return new User($name, $password);
    }

    /**
     * Checks if both passwords on the signup page match
     *
     * @param $f3
     * @return void
     */
    static function checkDoublePassword($f3) {
        //checks if both passwords match
        if ($_POST['password'] != $_POST['password2']) {
            $f3->set('errors["passwordMatch"]', false);
        }
    }

    /**
     * @param $f3
     * @param $value
     *      value for guess column
     * @return string
     *      class to apply via value logic
     */
    static function applyValidColoring($f3, $value) {
        if (is_numeric($value)) {
            $value = (int) $value;
            $popularity = $f3->get('SESSION.hiddenPopularity');
            if ($popularity > $value) {
                return 'less';
            } else if ($popularity < $value) {
                return 'more';
            } else {
                return 'equal';
            }
        } else {
            if (empty($value)) {
                return 'incorrect';
            } else {
                return 'partial';
            }
        }
    }
}
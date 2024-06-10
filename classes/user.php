<?php

/**
 * User class
 */
class User {
    private $_id;
    private $_name;
    private $_password;
    private $_score;

    /**
     * Creates a new user
     * @param string $_name name
     * @param string $_password un-hashed password
     * @param int $_score score
     */
    public function __construct($_name="", $_password="", $_score="0")
    {
        $this->_name = $_name;
        $this->_password = password_hash($_password, PASSWORD_DEFAULT);
        $this->_score = $_score;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->_id = $id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * @param mixed|string $name
     */
    public function setName($name)
    {
        $this->_name = $name;
    }

    /**
     * @return false|string|null
     */
    public function getPassword()
    {
        return $this->_password;
    }

    /**
     * @param false|string|null $password
     */
    public function setPassword($password)
    {
        $this->_password = $password;
    }

    /**
     * @return int|string
     */
    public function getScore()
    {
        return $this->_score;
    }

    /**
     * @param mixed|string $score
     */
    public function setScore($score)
    {
        $this->_score = $score;
    }

}
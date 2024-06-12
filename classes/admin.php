<?php

/**
 * A privileged user
 */
class Admin extends User
{
private bool $_adminStatus;


    /**
     * Creates a new admin level user
     * @param string $name name
     * @param string $password un-hashed password
     * sets score to a default of zero
     */
    public function __construct(string $name, string $password,)
    {
        parent::__construct($name, $password, 0);
        $this->_adminStatus = true;
    }

    /**
     * @return bool admin status of this user
     */
    public function getAdminStatus(): bool
    {
        return $this->_adminStatus;
    }

}
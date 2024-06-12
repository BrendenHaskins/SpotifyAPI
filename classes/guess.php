<?php

/**
 * represents any guessed artist
 */
class guess
{
private string $_name;
private int $_popularity;
private string $_topSong;
private string $_apiID;
private string $_photoURL;

    /**
     * @param string $_name
     * @param int $_popularity
     * @param string $_topSong
     * @param string $_apiID
     * @param string $_photoURL
     */
    public function __construct(string $_name, int $_popularity, string $_topSong, string $_apiID, string $_photoURL)
    {
        $this->_name = $_name;
        $this->_popularity = $_popularity;
        $this->_topSong = $_topSong;
        $this->_apiID = $_apiID;
        $this->_photoURL = $_photoURL;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->_name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->_name = $name;
    }

    /**
     * @return int
     */
    public function getPopularity(): int
    {
        return $this->_popularity;
    }

    /**
     * @param int $popularity
     */
    public function setPopularity(int $popularity): void
    {
        $this->_popularity = $popularity;
    }

    /**
     * @return string
     */
    public function getTopSong(): string
    {
        return $this->_topSong;
    }

    /**
     * @param string $topSong
     */
    public function setTopSong(string $topSong): void
    {
        $this->_topSong = $topSong;
    }

    /**
     * @return string
     */
    public function getApiID(): string
    {
        return $this->_apiID;
    }

    /**
     * @param string $apiID
     */
    public function setApiID(string $apiID): void
    {
        $this->_apiID = $apiID;
    }

    /**
     * @return string
     */
    public function getPhotoURL(): string
    {
        return $this->_photoURL;
    }

    /**
     * @param string $photoURL
     */
    public function setPhotoURL(string $photoURL): void
    {
        $this->_photoURL = $photoURL;
    }


}
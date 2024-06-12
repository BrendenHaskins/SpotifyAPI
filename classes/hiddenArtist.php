<?php

/**
 * a 'guess' object that is the hidden artist for a given game
 */
class hiddenArtist extends guess
{

    private string $_topGenre;
    private array $_genrePool;

    public function __construct(string $_name, int $_popularity, string $_topSong, string $_apiID, string $_photoURL,
                                string $topGenre, array $genrePool)
    {
        parent::__construct( $_name, $_popularity, $_topSong, $_apiID, $_photoURL);
        $this->_genrePool = $genrePool;
        $this->_topGenre = $topGenre;
    }

    /**
     * @return string
     */
    public function getTopGenre(): string
    {
        return $this->_topGenre;
    }

    /**
     * @param string $topGenre
     */
    public function setTopGenre(string $topGenre): void
    {
        $this->_topGenre = $topGenre;
    }

    /**
     * @return array
     */
    public function getGenrePool(): array
    {
        return $this->_genrePool;
    }

    /**
     * @param array $genrePool
     */
    public function setGenrePool(array $genrePool): void
    {
        $this->_genrePool = $genrePool;
    }


}
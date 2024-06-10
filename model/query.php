<?php

/*
 * This is for creating the DB connection and the queries alongside it
 *
 */
class Query {
    // Add a field to store the db connection object
    private $_dbh;

    function __construct()
    {
        // Require my PDO database connection credentials
        require_once $_SERVER['DOCUMENT_ROOT'].'/../config.php';

        try {
            //Instantiate our PDO Database Object
            $this->_dbh = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
            //echo 'Connected to database!!';
        }
        catch (PDOException $e) {
            die( $e->getMessage() );
            //die("<p>Something went wrong!</p>");
        }
    }

    /**
     * Collects top 10 users in the database (if there are 10)
     * @return array|false leaderboard
     */
    function getLeaderboard()
    {
        //Define and Prepare Query
        $statement = $this->_dbh->prepare(
            'SELECT user_id, name, score FROM `SpotifyUser` 
                            ORDER BY `SpotifyUser`.`score` DESC
                            LIMIT 10');

        //Execute Query
        $statement->execute();

        //Return results
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    function insertUser($user)
    {
        //Define and Prepare Query
        $statement = $this->_dbh->prepare(
            'INSERT INTO SpotifyUser (name, password, score) 
                    VALUES (:name, :password, :score)');

        //Bind parameters
        $name = $user->getName();
        $password = $user->getPassword();
        $score = $user->getScore();
        $statement->bindParam(':name', $name);
        $statement->bindParam(':password', $password);
        $statement->bindParam(':score', $score);

        //Execute Insert
        $statement->execute();

        //Get ID and save it
        $id = $this->_dbh->lastInsertId();
        $user->setId($id);
        return $id;
    }


}


//TODO: More specific queries depending on data needed to be taken out

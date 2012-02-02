<?php
/*
    This class has the db connection and credentials included inside 
    submit_Comments() and get_Comments().
*/
class Comment
{
    
    private $commentID;
    private $category;
    private $comment;
    private $votesup;
    private $votesdown;
    private $submittime;
    private $published;

    function __construct(){}

    function getCommentID(){
        return $this->commentID;
    }
    
    function getComment(){
        return $this->comment;
    }

    function getVotesUp(){
        return $this->votesup;
    }

    function getVotesDown(){
        return $this->votesdown;
    }
    
    function getSubmittime(){
        return $this->submittime;
    }

    // Returns bool and takes a string.
    function submit_Comment($comm, $category, $published = false) {
        // add connection credentials
        include("dbinfo.inc.php");
        // format string
        $this->comment = nl2br($comm);
        // Make sure category id is correct, if not then classify as general comment
        $this->category = ($category >= 1 && $category <= 3) ? $category : 1;
        $this->published = $published;
        // create connection        
        $pdo = new \PDO("mysql:host={$host};dbname={$database}", $username, $password);
        // For error handling
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        // Create PDO statement object
        $sth = $pdo->prepare("
            INSERT INTO Comment VALUES
                (NULL, :category, :comment, 0, 0, NOW(), :published)
        ");

        // Execute SQL query, bind parameter as we go.
        $sth->execute(array(
            ':comment' => $this->comment,
            ':category' => $this->category,
            ':published' => $this->published
        ));

        // number of rows affected.
        if ($sth->rowCount() == 1)
            return true;
        else
            return false;
    }

    // Returns an array of comments.
    // TODO: add pagination for getting comments.
    function get_Comments(){
        // add connection credentials
        include("dbinfo.inc.php");
        
        // create connection        
        $pdo = new \PDO("mysql:host={$host};dbname={$database}", $username, $password);
        // For error handling
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        // Create PDO statement object
        $sth = $pdo->prepare("
            SELECT * FROM Comment
                ORDER BY submittime DESC
                LIMIT 40
        ");
        // Comment class variables must reflect Table column names or a
        // proper array will not be returned.
        $sth->execute();
        $result = $sth->fetchAll(\PDO::FETCH_CLASS, "Comment");

        return $result;
    }
}
?>

<?php
/*
    This class has the db connection and credentials included inside 
    submit_Comments() and get_Comments().
*/
class Comment
{
    
    private $commentID;
    private $comment;
    private $upvotes;
    private $downvotes;
    private $submittime;

    function __construct(){}

    function getCommentID(){
        return $this->commentID;
    }
    
    function getComment(){
        return $this->comment;
    }

    function getDownvotes(){
        return $this->downvotes;
    }

    function getUpvotes(){
        return $this->upvotes;
    }
    
    function getSubmittime(){
        return $this->submittime;
    }

    // Returns bool and takes a string.
    function submit_Comment($comm) {
        // add connection credentials
        include("dbinfo.inc.php");
        // format string
        $this->comment = nl2br($comm);
        // create connection        
        $pdo = new \PDO("mysql:host={$host};dbname={$database}", $username, $password);
        // For error handling
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        // Create PDO statement object
        $sth = $pdo->prepare("
            INSERT INTO Comment VALUES
                (NULL, :comment, 0, 0, NOW())
        ");

        // Execute SQL query, bind parameter as we go.
        $sth->execute(array(
            ':comment' => $this->comment
        ));

        // number of rows affected.
        if ($sth->rowCount() == 1)
            return true;
        else
            return false;
    }

    // Returns an array of comments.
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
                LIMIT 10
        ");
        // Comment class variables must reflect Table column names or a
        // proper array will not be returned.
        $sth->execute();
        $result = $sth->fetchAll(\PDO::FETCH_CLASS, "Comment");

        return $result;
    }
}
?>

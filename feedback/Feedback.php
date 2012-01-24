<?php

/*
    This class has the db connection and credentials included inside 
    submit_Feedback() and get_Feedback().
*/

class Feedback
{
    protected $ID;
    protected $category;
    protected $message;
    protected $submit_time;
    protected $spam_rank;
    protected $published;
    protected $screen_name;
    
    function __construct(){}
    
    function __get($name)
    {
        return $this->$name;
    }
    
    // TODO: Add input checking via 3rd-party open source code
    function __set ($name, $value)
    {
        $this->$name = $value;
    }

    // TODO: Fix the rest of this code
    // TODO: add pagination for getting comments.
    // Returns an array of comments.
    function get_Content(){
        // add connection credentials
        include_once("dbinfo.inc.php");
        // Use own class to add more dynamic functionality
        $myclass = get_called_class();
        // create connection        
        $pdo = new \PDO("mysql:host={$host};dbname={$database}", $username, $password);
        // For error handling
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        // Create PDO statement object
        if ($myclass == "Feedback")
        {
            $sth = $pdo->prepare("
                SELECT f.ID, f.FK_FeedbackCat_ID as category, 
                    f.message, f.submit_time, f.spam_rank, 
                    f.published, fa.screen_name 
                FROM Feedback as f, FeedbackAnon as fa
                WHERE published=true
                AND f.ID=fa.FK_Feedback_ID
                    ORDER BY submit_time DESC
                    LIMIT 40
            ");
            
            $sth->execute();
        } 
        else // use category if not general feedback
        {
            $sth = $pdo->prepare("
                SELECT f.ID, f.FK_FeedbackCat_ID as category, 
                    f.message, f.submit_time, f.spam_rank, 
                    f.published, fa.screen_name 
                FROM Feedback as f, FeedbackAnon as fa
                WHERE published=true
                AND FK_FeedbackCat_ID=:category
                AND f.ID=fa.FK_Feedback_ID
                    ORDER BY submit_time DESC
                    LIMIT 40
            ");
            
            // Execute SQL query, bind parameter as we go.
            $sth->execute(array(
                ':category' => $this->category,
            ));
        }
        // Comment class variables must reflect Table column names or a
        // proper array will not be returned.
        $result = $sth->fetchAll(\PDO::FETCH_CLASS, $myclass);

        return $result;
    }
    
    function insertToDBAnon($sname, $msg, $spamrank)
    {
        // add connection credentials
        include_once("dbinfo.inc.php");
        // format string
        $this->message = nl2br($msg);
        // insert Sblam! spam rank
        $this->spam_rank = $spamrank;
        // If Sblam! spam rank is -2 then publish otherwise don't.
        $this->published = ($this->spam_rank == -2) ? true : false;
        // create connection        
        $pdo = new \PDO("mysql:host={$host};dbname={$database}", $username, $password);
        // For error handling
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        // Create PDO statement object
        $sth = $pdo->prepare("
            INSERT INTO Feedback VALUES
                (NULL, :category, :message, NOW(), :spamrank, :published);
            INSERT INTO FeedbackAnon VALUES
                (LAST_INSERT_ID(), :screenname);
        ");

        // Execute SQL query, bind parameter as we go.
        $sth->execute(array(
            ':category' => $this->category,
            ':message' => $this->message,
            ':spamrank' => $this->spam_rank,
            ':published' => $this->published,
            ':screenname' => $sname
        ));
        
        // number of rows affected.
        if ($sth->rowCount() == 1)
            return true;
        else
            return false;
    }
}

?>

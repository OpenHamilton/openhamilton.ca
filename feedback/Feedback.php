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
        // If screen name is empty then return Anonymous
        if ($name == "screen_name")
        {
            $n = trim($this->$name);
            return empty($n) ? "Anonymous" : $n;
        }
        // If getting message add line breaks in place of newline
        else if ($name == "message")
        {
            return nl2br($this->$name);
        }
        else
        {    
        return $this->$name;
        }
    }
    
    function __set ($name, $value)
    {
        $this->$name = $value;
    }

    // Returns an array of comments.
    function get_Content(){
        // Add connection credentials
        include("dbinfo.inc.php");
        // Use own class to add more dynamic functionality
        $myclass = get_called_class();
        // Create connection        
        $pdo = new \PDO("mysql:host={$host};dbname={$database}", $username, $password);
        // For error handling
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        /* If specific category not selected
           get Comment, Question, Dataset Request, and App Request */
        if ($myclass == "Feedback")
        {
            $sth = $pdo->prepare("
                SELECT f.ID, f.FK_FeedbackCat_ID as category, 
                    f.message, f.submit_time, f.spam_rank, 
                    f.published, fa.screen_name 
                FROM Feedback as f, FeedbackAnon as fa
                WHERE published=true
                AND f.ID=fa.FK_Feedback_ID
                AND f.FK_FeedbackCat_ID IN (1,2,3,4)
                    ORDER BY submit_time DESC
                    LIMIT 40
            ");
            
            $sth->execute();
        }
        else if ($myclass == "Response")
        {// This currently only finds anonymous users.
            $sth = $pdo->prepare("
                SELECT f.ID, f.FK_FeedbackCat_ID as category, 
                    f.message, f.submit_time, f.spam_rank, 
                    f.published, fa.screen_name, 
                    r.FK_Feedback_ID as parentID
                FROM Feedback as f, FeedbackAnon as fa, 
                    Response as r
                WHERE published=true
                AND FK_FeedbackCat_ID=:category
                AND f.ID=r.FK_Feedback_ID_R
                AND f.ID=fa.FK_Feedback_ID
                    ORDER BY submit_time DESC
                    LIMIT 40
            ");
            
            // Execute SQL query, bind parameter as we go.
            $sth->execute(array(
                ':category' => $this->category,
            ));
        } 
        else // Use category if not general feedback
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
                    LIMIT 100
            ");
            
            // Execute SQL query, bind parameter as we go.
            $sth->execute(array(
                ':category' => $this->category,
            ));
        }
        /* Comment class variables must reflect Table column names or a
           proper array will not be returned. */
        $result = $sth->fetchAll(\PDO::FETCH_CLASS, $myclass);

        return $result;
    }
    
    function insertToDBAnon($sname, $msg, $spamrank)
    {
        /* Make sure no leading or trailing spaces and only
           spaces and alphanumeric characters */
        $name = trim($sname);
        $pattern = '#^[a-z0-9 ]+$#i';
        if (!preg_match ($pattern, $name) ? true : false)
        {
            return -2;
        }
        $this->screen_name = $name;
        // Add connection credentials
        include("dbinfo.inc.php");
        // clean raw input before submitting
        $this->message = htmlentities($msg);
        // Insert Sblam! spam rank
        $this->spam_rank = $spamrank;
        // If Sblam! spam rank is -2 then publish otherwise don't.
        $this->published = ($this->spam_rank == -2) ? true : false;
        // Create connection        
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
            ':screenname' => $this->screen_name
        ));
        
        // Number of rows affected.
        if ($sth->rowCount() == 1)
            return true;
        else
            return false;
    }
}

?>

<?php
include_once("Feedback.php");
/*
    Question is a specific type of Feedback
*/
class Response extends Feedback
{
    private $parentID;
    
    // Provide the category in order to only search for related
    // responses, rather than looking up ALL responses to 
    // Comments, Questions, Dataset Requests, etc.
    function __construct($cat=1)
    {
        $this->category = $cat;
    }
    
    function __get($name)
    {
        return $this->$name;
    }
    
    function __set ($name, $value)
    {
        $this->$name = $value;
    }
}
?>

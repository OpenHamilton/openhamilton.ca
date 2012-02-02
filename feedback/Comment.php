<?php
include_once("Feedback.php");
/*
    Comment is a specific type of Feedback
*/
class Comment extends Feedback
{
    function __construct()
    {
        $this->category = 1;
    }
}
?>

<?php
include_once("Feedback.php");
/*
    Question is a specific type of Feedback
*/
class Question extends Feedback
{
    function __construct()
    {
        $this->category = 2;
    }
}
?>

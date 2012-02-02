<?php
include_once("Feedback.php");
/*
    App Request is a specific type of Feedback
*/
class AppRequest extends Feedback
{
    function __construct()
    {
        $this->category = 4;
    }
}
?>

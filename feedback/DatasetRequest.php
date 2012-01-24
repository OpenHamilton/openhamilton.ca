<?php
include_once("Feedback.php");
/*
    Dataset Request is a specific type of Feedback
*/
class DatasetRequest extends Feedback
{
    function __construct()
    {
        $this->FK_FeedbackCat_ID = 4;
    }
}
?>

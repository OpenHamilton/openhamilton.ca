<?php
// Use this script for simple function functionality
include("Feedback.php");
include("Comment.php");
include("Question.php");
include("DatasetRequest.php");
include("AppRequest.php");

define("COMMENT", 1);
define("QUESTION", 2);
define("DATASET_REQUEST", 4);
define("APP_REQUEST", 6);

function submitAnonFeedback($sname, $msg, $cat, $spamrank){
    $fb;
    // TODO: Validate input here before submitting
    switch ($cat) {
        case COMMENT:
            $fb = new Comment();
            break;
        case QUESTION:
            $fb = new Question();
            break;
        case DATASET_REQUEST:
            $fb = new DatasetRequest();
            break;
        case APP_REQUEST:
            $fb = new AppRequest();
            break;
        default:
            return false;
    }
    return $fb->insertToDBAnon($sname, $msg, $spamrank);
}

function getFeedback()
{
    $feedback = new Feedback();
    return $feedback->get_Content();
}

function getComments()
{
    $comments = new Comment();
    return $comments->get_Content();
}

function getQuestions()
{
    $questions = new Question();
    return $questions->get_Content();
}

function getDatasetRequests()
{
    $DSRs = new DatasetRequest();
    return $DSRs->get_Content();
}

function getAppRequests()
{
    $ARs = new AppRequest();
    return $ARs->get_Content();
}

function displayFeedback($fb)
{
}    
?>

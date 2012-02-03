<?php
// Use this script for simple function functionality
include("Feedback.php");
include("Comment.php");
include("Question.php");
include("DatasetRequest.php");
include("AppRequest.php");
include("Response.php");

define("COMMENT", 1);
define("QUESTION", 2);
define("DATASET_REQUEST", 3);
define("APP_REQUEST", 4);
define("ALL_FEEDBACK", 5);

define("SECOND", 1);
define("MINUTE", 60 * SECOND) ;
define("HOUR", 60 * MINUTE);
define("DAY", 24 * HOUR);
define("MONTH", 30 * DAY);

function submitAnonFeedback($sname, $msg, $cat, $spamrank, $errormessage = '')
{
    switch ($cat) 
    {
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
    
    $rval = (int) $fb->insertToDBAnon($sname, $msg, $spamrank);
   
    // Display error message if user, used spaces in their name.
    if ($rval == -2) 
    {   
        $errormessage .= '<br /><span class="error">*Only letters, numbers, and spaces can be in a name.</span><br />';
        return false;
    }
    else
    {
        return true;
    }
}

function getFeedback($category = ALL_FEEDBACK)
{
    $fb;
    switch ($category) {
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
        case ALL_FEEDBACK:
            $fb = new Feedback();
            break;
        default:
            return NULL;
    }
    return $fb->get_Content();
}

function getResponses($category)
{
    $responses = new Response($category);
    return $responses->get_Content();
}    

function displayFeedback($fb)
{
    // Clean screen_name and message in case db compromised
    $time_string = timeAgo($fb->submit_time);
    $screen_name = htmlentities($fb->screen_name, ENT_QUOTES, 'UTF-8');
    $message = strip_tags($fb->message, '<p><br>');

    return
        '<li>'.
            '<p>'.$message.'</p>'.
            '<span class="author">'.$screen_name.'</span>'.
                '&nbsp;&nbsp;&nbsp;'.
                '<span class="time">'.$time_string.'</span>'.
        '</li>'.
        "\n";
}

// Display time Facebook style
function timeAgo($time)
{
    // Time in seconds
    $delta = time() - strtotime($time);
    
    if ($delta < 0)
    {
        return "not yet";
    }
    if ($delta == 0)
    {
        return "just now";
    }
    if ($delta < 1 * MINUTE)
    {
        return $delta == 1 ? "one second ago" : $delta." seconds ago";
    }
    if ($delta < 2 * MINUTE)
    {
        return "a minute ago";
    }
    if ($delta < 45 * MINUTE)
    {
        return (int)($delta/MINUTE)." minutes ago";
    }
    if ($delta < 90 * MINUTE)
    {
        return "an hour ago";
    }
    if ($delta < 24 * HOUR)
    {
        return (int)($delta/HOUR)." hours ago";
    }
    if ($delta < 48 * HOUR)
    {
        return "yesterday";
    }
    if ($delta < 30 * DAY)
    {
        return (int)($delta/DAY)." days ago";
    }
    if ($delta < 12 * MONTH)
    {
        $months = (int)(($delta/DAY) / 30);
        return $months <= 1 ? "one month ago" : $months." months ago";
    }
    else
    {
        $years = (int)(($delta/DAY) / 365);
        return $years <= 1 ? "one year ago" : $years." years ago";
    }
}

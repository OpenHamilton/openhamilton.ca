<?php
// Use this script for simple function functionality
include ("comment.php");

function submitComment($comment){
    $cmt = new Comment();
    return $cmt->submit_Comment($comment);
}

function getComments(){
    $cmts = new Comment();
    return $cmts->get_Comments();
}
?>

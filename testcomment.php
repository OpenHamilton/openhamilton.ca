<?php
//include("comment.php");
include("easyComment.php");
$comment = (isset($_POST['comment']) ? $_POST['comment'] : NULL);
$request_method = isset($_SERVER['REQUEST_METHOD']) 
                    ? strtoupper($_SERVER['REQUEST_METHOD']) 
                    : '';
?>

<form action="testcomment.php" method="post">
Your suggestion: <br /><textarea rows="10" cols="50" maxlength="500" name="comment"></textarea><br />
<input type="submit" value="Submit Feedback" />
</form>

<?php
// Check if postback
if (strtoupper($request_method) == 'POST' &&
        $comment != "") {
    // Create comment object and submit if postback
/*
    $cmt = new Comment();
*/
    if(submitComment($comment)){
        echo "Your comment has been successfully added!";
    } else {
        echo "Sorry, your comment wasn't added.";
    }
    
}
?>
<hr />
<?php
// load comments
/*
$cmts = new Comment();
*/
$recentComments = getComments();
echo 'Here are some of the latest comments left by the general public: <br /><br />';
foreach($recentComments as $com)
        {
            echo $com->getComment().'<br /><br />';
        }
?>

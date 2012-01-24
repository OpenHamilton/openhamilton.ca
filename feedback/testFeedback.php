<?php
//include("Feedback.php");
include("easyFeedback.php");
define("COMMENT", 1);
define("QUESTION", 2);
define("DATASET_REQUEST", 4);
define("APP_REQUEST", 6);

$screen_name = (isset($_POST['screen_name']) ? $_POST['screen_name'] : NULL);
$message = (isset($_POST['message']) ? $_POST['message'] : NULL);
$category = (isset($_POST['category']) ? $_POST['category'] : NULL);

$request_method = isset($_SERVER['REQUEST_METHOD']) 
                    ? strtoupper($_SERVER['REQUEST_METHOD']) 
                    : '';
?>

<form method="post">
Screen Name (Optional): <br /><input type="text" value="" maxlength="30" size="30" name="screen_name" /><br />
<input type="radio" name="category" value="<?php echo COMMENT; ?>" checked />Comment
<input type="radio" name="category" value="<?php echo QUESTION; ?>" />Question
<input type="radio" name="category" value="<?php echo DATASET_REQUEST; ?>" />Dataset Request
<input type="radio" name="category" value="<?php echo APP_REQUEST; ?>" />App Request<br />
<textarea rows="10" cols="50" maxlength="500" name="message">Type your message here...</textarea><br />
<input type="submit" value="Submit Feedback" />
</form>

<?php
// Check if postback
if (strtoupper($request_method) == 'POST' &&
        $message != "") {
    // this variable assignment is temporary.    
    $spamrank = -2;        
    // Store feedback if user submitted feedback.
    if(submitAnonFeedback($screen_name, $message, $category, $spamrank)){
        echo "Your feedback has been successfully added!";
    } else {
        echo "Sorry, your feedback wasn't added.";
    }    
}
?>
<hr />
<?php
// Load feedback
//$recentFeedback = getFeedback();
//$recentFeedback = getComments();
//$recentFeedback = getQuestions();
//$recentFeedback = getDatasetRequests();
$recentFeedback = getAppRequests();
echo 'Here is some of the latest feedback left by the general public: <br /><br />';
foreach($recentFeedback as $fb)
        {
            echo    $fb->ID.'<br />'.
                    $fb->FK_FeedbackCat_ID.'<br />'.
                    $fb->message.'<br />'.
                    $fb->submit_time.'<br /><br />';
        }
?>

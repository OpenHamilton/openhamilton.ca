<?php
include_once("sblamtest.php");
include("easyFeedback.php");

// Submit variables
$screen_name = (isset($_POST['screen_name']) ? $_POST['screen_name'] : NULL);
$message = (isset($_POST['message']) ? $_POST['message'] : NULL);
$category = (isset($_POST['category']) ? $_POST['category'] : NULL);

// Display variables
$display_category = (isset($_POST['display_category']) ? $_POST['display_category'] : NULL);

$request_method = isset($_SERVER['REQUEST_METHOD']) 
                    ? strtoupper($_SERVER['REQUEST_METHOD']) 
                    : '';
?>
<head>
    <link rel="stylesheet" href="stylefeedback.css" />
</head>
<div class="actual_space">
<form method="post">
Name <span class="label_tips">(Optional)</span>:<br />
<input type="text" title="You can enter your real name or an alias, like 'CoolGuy65'" 
    value="E.g., 'Citizen Optimist123' or 'Peter Santos'" maxlength="30" size="30" 
    name="screen_name" onclick="this.value='';"
    onblur="this.value=!this.value?'Anonymous':this.value;" /><br />
Your thoughts:<br />
<textarea rows="10" cols="58" maxlength="500" 
    title="Type your thoughts here..."name="message"
    onfocus="if(this.value==this.defaultValue)this.value='';"
    onblur="if(this.value=='')this.value=this.defaultValue;">E.g., "Keep up the good work!", "What is Open Data?", "Could you get a listing of all the parks in Hamilton?", "Could you please make a park finder app?"</textarea><br />
This is a(n):
<select name="category">
  <option value="<?php echo COMMENT; ?>">Comment</option>
  <option value="<?php echo QUESTION; ?>">Question</option>
  <option value="<?php echo DATASET_REQUEST; ?>">Dataset Request</option>
  <option value="<?php echo APP_REQUEST; ?>">App Request</option>
</select>
<input type="submit" value="Submit Feedback"  class="styled_button" />
</form>
<script src="sblam.js.php" type="text/javascript"></script><!-- Added for more accurate checking with Sblam! -->
<?php
// Check if postback
if (strtoupper($request_method) == 'POST' &&
        $message != "") 
{
    // This tests if the post is spam. (Courtesy of Sblam!)
    $spamrank = -2;//sblamtestpost(array('message', 'screen_name', NULL, NULL));
    if ($spamrank > -2)
    {
        
        if(!submitAnonFeedback($screen_name, $message, $category, $spamrank))
        {
            echo "Sorry, your feedback wasn't added. Please, try again.";
        }
        else
        {
            echo 'Note: <span class="error">Your feedback has been marked as potential spam and
            will be reviewed before being posted. If you disagree with
            this, please let us know.</span><br />Thank you for your feedback.';
        }
            
    }
    else
    {
        // Store feedback if user submitted non-spam feedback.
        if(submitAnonFeedback($screen_name, $message, $category, $spamrank)){
            echo "Your feedback has been successfully added!";
        } else {
            echo "Sorry, your feedback wasn't added. Please, try again.";
            
        }
    }    
}
?>
<hr />
<form name="get_feedback" method="post">
    <input type="submit" value="Show me" class="styled_button">&nbsp;the latest
    <select name="display_category">
      <option value="<?php echo COMMENT; ?>">Comments</option>
      <option value="<?php echo QUESTION; ?>">Questions</option>
      <option value="<?php echo DATASET_REQUEST; ?>">Dataset Requests</option>
      <option value="<?php echo APP_REQUEST; ?>">App Requests</option>
      <option value="<?php echo ALL_FEEDBACK; ?>">Everything</option>
    </select>
    left by the citizens of Hamilton: <br /><br />
</form>
<div class="fixed_box">
    <div class="feedback_container">
<?php
// Check if "Show me" button was clicked
if (!empty($display_category))
{
    $recentFeedback = getFeedback($display_category);
}
else
{   
    $recentFeedback = getFeedback(COMMENT);
}

// If recentFeedback is not null, display feedback retrieved
if ($recentFeedback != NULL) 
{
    foreach($recentFeedback as $fb)
    {
        displayFeedback($fb);
    }
} 
else
{
    echo "Sorry, we couldn't get the latest feedback.";
}
?>
    </div>
</div>
</div>

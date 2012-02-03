<?php

include('..' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'templates.php');
include('..' . DIRECTORY_SEPARATOR . 'feedback' . DIRECTORY_SEPARATOR . 'easyFeedback.php');
include_once('..' . DIRECTORY_SEPARATOR . 'feedback' . DIRECTORY_SEPARATOR . 'sblamtest.php');

/* Feedback box variable setup*/

// Submit variables
$screen_name = (isset($_POST['screen_name']) ? $_POST['screen_name'] : NULL);
$message = (isset($_POST['message']) ? $_POST['message'] : NULL);
$category = (isset($_POST['category']) ? $_POST['category'] : NULL);

// Display variables
$display_category = (isset($_POST['display_category']) ? $_POST['display_category'] : NULL);

$request_method = isset($_SERVER['REQUEST_METHOD']) 
                    ? strtoupper($_SERVER['REQUEST_METHOD']) 
                    : '';

/* Feedback box variable setup end */

$builder = new TextBuilders();


$infoPanel = "";
$infoPanel .= "
                <h2>Follow us on twitter</h2>
                <p>Stay up to date with our latest breaking tweets.
                <br/>


<a href=\"https://twitter.com/share\" class=\"twitter-share-button\" data-url=\"http://openhamilton.ca/\" data-text=\"We need open data!\" data-via=\"OpenHamilton\" data-hashtags=\"OpenData\">Tweet</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=\"//platform.twitter.com/widgets.js\";fjs.parentNode.insertBefore(js,fjs);}}(document,\"script\",\"twitter-wjs\");</script>

                </p>
                <h2>Help out on GitHub</h2>
                <p><a href=\"\">Look at our source code, contribute to the project, or build your own killer app.
                <br/><img src=\"/img/github.png\"></a>
                </p>
                <h2>Find our discussion board</h2>
                <p><a href=\"http://groups.google.com/group/openhamilton\">Much of our planning and meeting notes are found at Google Groups.
                <br/><img src=\"/img/googlegroups.gif\"></a>
                </p>
";

$dataPanels = "";
$dataPanels .= 
    "
    <div>
        <form method=\"post\">
        <h2>Leave us some feedback</h2>
        Name <span class=\"label_tips\">(Optional)</span>:<br />
        <input type=\"text\" title=\"You can enter your real name or an alias, like 'CoolGuy65'\" 
            value=\"E.g., 'Citizen Optimist123' or 'Peter Santos'\" maxlength=\"30\" size=\"30\" 
            name=\"screen_name\" onclick=\"this.value='';\"
            onblur=\"this.value=!this.value?'Anonymous':this.value;\" /><br />
        Your thoughts:<br />
        <textarea rows=\"4\" cols=\"57\" maxlength=\"500\" 
            title=\"Type your thoughts here...\"name=\"message\"
            onfocus=\"if(this.value==this.defaultValue)this.value='';\"
            onblur=\"if(this.value=='')this.value=this.defaultValue;\">E.g., \"Keep up the good work!\", \"What is Open Data?\", \"Could you get a listing of all the parks in Hamilton?\", \"Could you please make a park finder app?\"</textarea><br />
        I am leaving a(n):
        <select name=\"category\">
          <option value=\"".COMMENT."\">Comment</option>
          <option value=\"".QUESTION."\">Question</option>
          <option value=\"".DATASET_REQUEST."\">Dataset Request</option>
          <option value=\"".APP_REQUEST."\">App Request</option>
        </select>
        <input type=\"submit\" value=\"Submit Feedback\" class=\"styled_button\" />
        </form>
        <script src=\"sblam.js.php\" type=\"text/javascript\"></script><!-- Added for more accurate checking with Sblam! -->
     "
;
/* For Submission of Feedback */

// Check if postback
if (strtoupper($request_method) == 'POST' &&
        $message != "") 
{
    $error_msg = '';
    // This tests if the post is spam. (Courtesy of Sblam!)
    $spamrank = -2;//sblamtestpost(array('message', 'screen_name', NULL, NULL));
    if ($spamrank > -2)
    {
        
        if(!submitAnonFeedback($screen_name, $message, $category, $spamrank, &$error_msg))
        {
            $dataPanels = substr_replace($dataPanels, "Sorry, your feedback wasn't added. Please, try again.", -12);
            $dataPanels .= $error_msg;
        }
        else
        {
            $dataPanels = substr_replace($dataPanels, 'Note: <span class="error">Your feedback has been marked as potential spam and
            will be reviewed before being posted. If you believe this is a mistake,
            please let us know.</span><br />Thank you for your feedback.', -12);
        }
            
    }
    else
    {
        // Store feedback if user submitted non-spam feedback.
        if(submitAnonFeedback($screen_name, $message, $category, $spamrank, &$error_msg))
        {
            $dataPanels = substr_replace($dataPanels, "Your feedback has been successfully added!", -12);
        } else {
            $dataPanels = substr_replace($dataPanels, "Sorry, your feedback wasn't added. Please, try again.", -12);
            $dataPanels .= $error_msg;
        }
    }    
}
/* For Submission of Feedback END */

/* For Display of Feedback BEGIN */
$dataPanels .= '
    <hr class="divider" />
    <form name="get_feedback" method="post">
        <input type="submit" value="Show me" class="styled_button">&nbsp;the latest
        <select name="display_category">
          <option value="'.COMMENT.'">Comments</option>
          <option value="'.QUESTION.'">Questions</option>
          <option value="'.DATASET_REQUEST.'">Dataset Requests</option>
          <option value="'.APP_REQUEST.'">App Requests</option>
          <option value="'.ALL_FEEDBACK.'">Everything</option>
        </select>
        left by the citizens of Hamilton: <br />
    </form>
    <ul class="feedback">';
    // Check if "Show me" button was clicked
    if (!empty($display_category))
    {
        $recentFeedback = getFeedback($display_category);
    }
    else
    {   
        $recentFeedback = getFeedback(COMMENT);
    }

    $display_error = '';
    // If recentFeedback is not null, display feedback retrieved
    if ($recentFeedback != NULL) 
    {
        foreach($recentFeedback as $fb)
        {
            $dataPanels .= displayFeedback($fb);
        }
    } 
    else
    {
        $dataPanels .= "Sorry, we couldn't get the latest feedback.";
    }
    $dataPanels .= '</ul>'.'</div>';


$page = new DetailsPage(array(
    "{PageTitle}"  => "Home | Open Hamilton",
    "{InfoPanel}"  => $infoPanel,
    "{DataPanels}" => $dataPanels
));

echo $page->mergeData();

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
        <textarea rows=\"10\" cols=\"58\" maxlength=\"500\" 
            title=\"Type your thoughts here...\"name=\"message\"
            onfocus=\"if(this.value==this.defaultValue)this.value='';\"
            onblur=\"if(this.value=='')this.value=this.defaultValue;\">E.g., \"Keep up the good work!\", \"What is Open Data?\", \"Could you get a listing of all the parks in Hamilton?\", \"Could you please make a park finder app?\"</textarea><br />
        This is a(n):
        <select name=\"category\">
          <option value=\"".COMMENT."\">Comment</option>
          <option value=\"".QUESTION."\">Question</option>
          <option value=\"".DATASET_REQUEST."\">Dataset Request</option>
          <option value=\"".APP_REQUEST."\">App Request</option>
        </select>
        <input type=\"submit\" value=\"Submit Feedback\" class=\"styled_button\" />
        </form>
        <script src=\"sblam.js.php\" type=\"text/javascript\"></script><!-- Added for more accurate checking with Sblam! -->
     </div>
     "
;
/* For Submission of Feedback */

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
            $dataPanels = substr_replace($dataPanels, "Sorry, your feedback wasn't added. Please, try again.</div>", -12);
        }
        else
        {
            $dataPanels.= 'Note: <span class="error">Your feedback has been marked as potential spam and
            will be reviewed before being posted. If you disagree with
            this, please let us know.</span><br />Thank you for your feedback.';
        }
            
    }
    else
    {
        // Store feedback if user submitted non-spam feedback.
        if(submitAnonFeedback($screen_name, $message, $category, $spamrank)){
            $dataPanels.= "Your feedback has been successfully added!";
        } else {
            $dataPanels = substr_replace($dataPanels, "Sorry, your feedback wasn't added. Please, try again.</div>", -12);
            
        }
    }    
}
/* For Submission of Feedback END */

$page = new DetailsPage(array(
    "{PageTitle}"  => "Home | Open Hamilton",
    "{InfoPanel}"  => $infoPanel,
    "{DataPanels}" => $dataPanels
));

echo $page->mergeData();

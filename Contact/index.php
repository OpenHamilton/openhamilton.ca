<?php

include('..' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'templates.php');

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
$dataPanels .= $builder->standardDivBlock(
    "Comments",
    "Coming soon: Have your say, right here."
);


$page = new DetailsPage(array(
    "{PageTitle}"  => "Home | Open Hamilton",
    "{InfoPanel}"  => $infoPanel,
    "{DataPanels}" => $dataPanels
));

echo $page->mergeData();

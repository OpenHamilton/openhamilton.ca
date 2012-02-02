<?php

include('.' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'templates.php');

$builder = new TextBuilders();

$infoPanel = "";
$infoPanel .= $builder->standardBlock(
    "What is OpenHamilton?",
    "We're a fast-growing group of Hamilton citizens who want to make life for everyone better through Open Data."
);

$infoPanel .= $builder->standardBlock(
    "What is OpenData?",
    "Open data is the idea that certain data should be freely available to everyone to use and republish as they wish, without restrictions from copyright, patents or other mechanisms of control."
);

$infoPanel .= "
<a href=\"https://twitter.com/share\" class=\"twitter-share-button\" data-url=\"http://openhamilton.ca/\" data-text=\"We need open data!\" data-via=\"OpenHamilton\" data-hashtags=\"OpenData\">Tweet</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=\"//platform.twitter.com/widgets.js\";fjs.parentNode.insertBefore(js,fjs);}}(document,\"script\",\"twitter-wjs\");</script>
";

$dataPanels = "";
$dataPanels .= $builder->standardAnchorDivBlock(
    "Apps",
    "Check out cool things we can do with open data.",
    "./Apps"
);
$dataPanels .= $builder->standardAnchorDivBlock(
    "Data",
    "Look at data, suggest new data, submit data.",
    "./Data"
);
$dataPanels .= $builder->standardAnchorDivBlock(
    "Feedback",
    "Tell us what you think.",
    "./Contact"
);
$dataPanels .= $builder->standardAnchorDivBlock(
    "Discuss",
    "Help build Open Data.",
    "http://groups.google.com/group/openhamilton"
);

$page = new MainPage(array(
    "{PageTitle}"  => "Home | Open Hamilton",
    "{InfoPanel}"  => $infoPanel,
    "{DataPanels}" => $dataPanels
));

echo $page->mergeData();

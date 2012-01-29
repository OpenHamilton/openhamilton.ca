<?php

include('..' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'templates.php');

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


$dataPanels = "";
$dataPanels .= $builder->standardDivBlock(
    "Apps",
    "Check out cool things we can do with open data."
);
$dataPanels .= $builder->standardDivBlock(
    "Data",
    "Look at data, suggest new data, submit data."
);
$dataPanels .= $builder->standardDivBlock(
    "Feedback",
    "Tell us what you think."
);
$dataPanels .= $builder->standardDivBlock(
    "Support",
    "Help spread Open Data."
);


$page = new MainPage(array(
    "{PageTitle}"  => "Home | Open Hamilton",
    "{InfoPanel}"  => $infoPanel,
    "{DataPanels}" => $dataPanels
));

echo $page->mergeData();

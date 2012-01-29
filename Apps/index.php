<?php

include('..' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'templates.php');

$builder = new TextBuilders();


$infoPanel = "";
$infoPanel .= $builder->standardBlock(
    "What is OpenHamilton?",
    "We're a fast-growing group of Hamilton citizens who want to make life for everyone better through Open Data."
);


$dataPanels = "";
$dataPanels .= $builder->standardDivBlock(
    "Dowsing",
    "Find local simming places on a map."
);
$dataPanels .= $builder->standardDivBlock(
    "Dowsing Mobile",
    "Dowsing on the go"
);
$dataPanels .= $builder->standardDivBlock(
    "Skate",
    "Find  a place to skate"
);
$dataPanels .= $builder->standardDivBlock(
    "RepFinder",
    "Who's your rep?"
);


$page = new MainPage(array(
    "{PageTitle}"  => "Home | Open Hamilton",
    "{InfoPanel}"  => $infoPanel,
    "{DataPanels}" => $dataPanels
));

echo $page->mergeData();

<?php

include('..' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'templates.php');

$builder = new TextBuilders();


$infoPanel = "";
$infoPanel .= $builder->standardBlock(
    "Data Portal",
    "This area of the site is dedicated to Hamilton related Open Data sets."
);

$dataPanels = "";
$dataPanels .= $builder->standardAnchorDivBlock(
    "GTFS",
    "Google Transit Feed scraper for Hamilton Street Rail.",
    "https://github.com/Jasindros/Hamilton-Open-Data"
);
/*
$dataPanels .= $builder->standardDivBlock(
    "Placeholder",
    "More data sets will be accessable shortly."
);
*/

$page = new MainPage(array(
    "{PageTitle}"  => "Home | Open Hamilton",
    "{InfoPanel}"  => $infoPanel,
    "{DataPanels}" => $dataPanels
));

echo $page->mergeData();

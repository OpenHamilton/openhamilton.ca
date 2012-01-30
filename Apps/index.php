<?php

include('..' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'templates.php');

$builder = new TextBuilders();


$infoPanel = "";
$infoPanel .= $builder->standardBlock(
    "Apps built on OpenData",
    "Apps built with Hamilton based Open Data sets."
);

$dataPanels = "";
$dataPanels .= $builder->standardAnchorDivBlock(
    "Skate Hamilton",
    "Find  a place to go ice skate in Hamilton.",
    "/Apps/SkateHamilton"
);
$dataPanels .= $builder->standardAnchorDivBlock(
    "HammerSMS",
    "Bus schedules via Text message to your cellphone, not just for smartphones.",
    "/Apps/HammerSMS"
);
$dataPanels .= $builder->standardAnchorDivBlock(
    "Dowsing",
    "Find local simming spots in Hamilton.",
    "/Apps/Dowsing"
);
$dataPanels .= $builder->standardAnchorDivBlock(
    "Dowsing Mobile",
    "Find swimming spots from your smartphone.",
    "/Apps/DowsingMobile"
);


$page = new MainPage(array(
    "{PageTitle}"  => "Home | Open Hamilton",
    "{InfoPanel}"  => $infoPanel,
    "{DataPanels}" => $dataPanels
));

echo $page->mergeData();

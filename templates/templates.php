<?php

//Class that manages templates.
class Template {
    private $fileData;
    private $customData;

    public function Template($fileName, $data) {
        $this->loadFile($fileName);
        $this->setData($data);
    }

    //Load data from filename
    public function loadFile($fileName) {
        $this->fileData = file_get_contents($fileName);
    }

    //Set data to populate template
    public function setData($data) {
        $this->customData = $data;
    }
    
    //Merge template with data
    public function mergeData() {
        $template = $this->fileData;
        foreach ($this->customData as $key => $val) {
            $template = str_replace($key, $val, $template);
        }
        return $template;
    }

    //Set individual value after constructor call.
    public function setValue($value, $data) {
        $this->customData[$value] = $data;
    }
}

class MainPage extends Template {
    
    public function MainPage($data) {
        parent::Template( __DIR__ . DIRECTORY_SEPARATOR . "main.html", $data);
    }
    
}

class DetailsPage extends Template {
    
    public function DetailsPage($data) {
        parent::Template( __DIR__ . DIRECTORY_SEPARATOR . "details.html", $data);
    }
    
}

class TextBuilders {
    public function standardBlock($heading, $paragraph){
        $heading = htmlentities($heading);
        $paragraph = htmlentities($paragraph);
        $output = "";
        $output .= "<h2>{$heading}</h2>";
        $output .= "<p>{$paragraph}</p>";
        return $output;
    }

    public function standardAnchorDivBlock($heading, $paragraph, $link){
        $output = "";
        $output .= "<div>";
        $output .= "<a href=\"{$link}\">";
        $output .= $this->standardBlock($heading, $paragraph);
        $output .= "</a>";
        $output .= "</div>";
        return $output;
    }

    public function standardDivBlock($heading, $paragraph){
        $output = "";
        $output .= "<div>";
        $output .= $this->standardBlock($heading, $paragraph);
        $output .= "</div>";
        return $output;
    }
}
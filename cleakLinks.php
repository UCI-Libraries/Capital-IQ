<?php
//Load JSON file with log of links
$json_file = $_SERVER['DOCUMENT_ROOT'] 
           . '/capiqLinks.json';


$content = file_get_contents($json_file);
$capiqJson = json_decode($content, true);

//check and compare click timestamps, clear clicks older than 30min
foreach($capiqJson as $key => $value) {
  if(!empty($value)){
    $dateClicked = new DateTime($value);
    $dateNow = new DateTime("now");
    $timeDiff = date_diff($dateClicked, $dateNow);
    if($timeDiff->format("%I") >= 30){
      $capiqJson[$key] = "";
    } //fi
  }//fi
}//foreach

//Update file contents
file_put_contents($json_file, json_encode($capiqJson));

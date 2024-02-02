<?php

$total = 0;

$powerSum = 0;

foreach(file('2input.txt') as $line) {
    $gameID = substr($line,5,3);
    $gameID = preg_replace("/\s|:/","",$gameID);
    
    $line = preg_replace("/^G(.)*:/","",$line);
    
    $red = preg_replace("/...blue|...green|red|\,/","",$line);
    $red = preg_replace("/\s+/","",$red);
    $red = preg_replace("/;+/",";",$red);
    $red = preg_replace("/^;|;$/","",$red);
    $red = explode(";",$red);
    $maxred = max($red);

    $blue = preg_replace("/...red|...green|blue|\,/","",$line);
    $blue = preg_replace("/\s+/","",$blue);
    $blue = preg_replace("/;+/",";",$blue);
    $blue = preg_replace("/^;|;$/","",$blue);
    $blue = explode(";",$blue);
    $maxblue = max($blue);

    $green = preg_replace("/...blue|...red|green|\,/","",$line);
    $green = preg_replace("/\s+/","",$green);
    $green = preg_replace("/;+/",";",$green);
    $green = preg_replace("/^;|;$/","",$green);
    $green = explode(";",$green);
    $maxgreen = max($green);
   
    if ($maxred <= 12 & $maxblue <= 14 & $maxgreen <= 13) $total += $gameID;

    $powerSum += $maxred*$maxblue*$maxgreen;
}

echo $total." - ".$powerSum;

?>

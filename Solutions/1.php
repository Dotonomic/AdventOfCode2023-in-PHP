<?php

$total = 0;

foreach(file('1input.txt') as $line) {
    $line = str_ireplace('three','t3e',$line);
    $line = str_ireplace('eight','e8t',$line);
    $line = str_ireplace('two','t2o',$line);
    $line = str_ireplace('one','o1e',$line);
    $line = str_ireplace('four','f4r',$line);
    $line = str_ireplace('five','f5e',$line);
    $line = str_ireplace('six','s6x',$line);
    $line = str_ireplace('seven','s7n',$line);
    $line = str_ireplace('nine','n9e',$line);

   $line = preg_replace("/([a-z]|\n)*/i","",$line);
   
   $first = substr($line,0,1);
   $last = substr($line,-1,1);
   
   $line = $first.$last;
   
   $total += $line;
}

echo $total;

?>

<?php

$total = 0;

foreach(file('1input.txt') as $line) {
    $line = preg_replace("/three/i","t3e",$line);
    $line = preg_replace("/eight/i","e8t",$line);
    $line = preg_replace("/two/i","t2o",$line);
    $line = preg_replace("/one/i","o1e",$line);
    $line = preg_replace("/four/i","f4r",$line);
    $line = preg_replace("/five/i","f5e",$line);
    $line = preg_replace("/six/i","s6x",$line);
    $line = preg_replace("/seven/i","s7n",$line);
    $line = preg_replace("/nine/i","n9n",$line);

   $line = preg_replace("/([a-z]|\n)*/i","",$line);
   
   $first = substr($line,0,1);
   $last = substr($line,-1,1);
   
   $line = $first.$last;
   
   $total += $line;
}

echo $total;

?>

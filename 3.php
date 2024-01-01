<?php
$numbers = [];
//$sum = 0;
$sumGears = 0;
$gears = [];

foreach (file("3input.txt") as $lineKey => $line) {
	$char = substr($line,0,1);
	$pos = 0;
	parseLine($char,$pos,$lineKey,$line);
}

//foreach ($numbers as $n) addOrNot($n);

//echo $sum;

foreach ($numbers as $n) adjacent($n);

foreach ($gears as $gears1) {
    foreach ($gears1 as $gear) {
        if (count($gear) == 2) $sumGears += $gear[0]*$gear[1];
    }
}

echo $sumGears;

function adjacent($n) {
    //$adjacent = 0;
	foreach (file("3input.txt") as $lineKey => $line) {
		if ($lineKey == $n[0]-1 || $lineKey == $n[0]+1) checkAboveOrBelow($n,$line,$lineKey);
		if ($lineKey == $n[0]) checkThis($n,$line);
	}
	//return $adjacent;
}

function checkThis($n,$line) {
    global $gears;
	if (substr($line,$n[1]-1,1) == "*" & $n[1]-1>0) {
	    $gears[$n[0]][$n[1]-1][] = $n[2]; //return TRUE;
	} 
	if (substr($line,$n[1]+strlen($n[2]),1) == "*" & $n[1]+strlen($n[2])<140) {
	    $gears[$n[0]][$n[1]+strlen($n[2])][] = $n[2]; //return TRUE;
	}
	//else return FALSE;
}

function checkAboveOrBelow($n,$line,$l) {
    global $gears;
	for ($i=max($n[1]-1,0);$i<=min($n[1]+strlen($n[2]),139);$i++) {
		if (substr($line,$i,1) == "*") {
		    $gears[$l][$i][] = $n[2]; //return TRUE;
		}
	}
	//return FALSE;
}

function addOrNot($n) {
	global $sum;
	if (adjacent($n)) $sum += $n[2];
	echo $sum.'<br>';
}

function parseLine($char,$pos,$lineKey,$line) {
	global $numbers;
	
	while (!preg_match("/[0-9]/",$char) & $pos<strlen($line)) {
		$pos++;
		$char = substr($line,$pos,1);
	}
	
	$number='';
	while (preg_match("/[0-9]/",$char) & $pos<strlen($line)) {
		$number .= $char;
		$pos++;
		$char = substr($line,$pos,1);
	}
	if ($number) $numbers[] = [$lineKey,$pos-strlen($number),$number];

	if ($pos<strlen($line)) parseLine($char,$pos,$lineKey,$line);
}

?>

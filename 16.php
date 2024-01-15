<?php

define("Start",microtime(TRUE));

foreach (file("16.txt") as $row => $line) {
	$line = str_split(preg_replace("/\n|\s/","",$line));
	foreach ($line as $col => $element)
		$grid[$col][$row] = $element;
}

define("Grid",$grid);
define("Width",count(Grid[0]));
define("Height",count(Grid));

$maxEnergized = 0;

for ($x=0;$x<Width;$x++) {
	$energized = 0;
	$m = [];
	flow([$x,0],[0,1]);
	$maxEnergized = max($maxEnergized,$energized);
	
	$energized = 0;
	$m = [];
	flow([$x,Height-1],[0,-1]);
	$maxEnergized = max($maxEnergized,$energized);
}

for ($y=0;$y<Height;$y++) {
	$energized = 0;
	$m = [];
	flow([0,$y],[1,0]);
	$maxEnergized = max($maxEnergized,$energized);
	
	$energized = 0;
	$m = [];
	flow([Width-1,$y],[-1,0]);
	$maxEnergized = max($maxEnergized,$energized);
}

echo $maxEnergized."\n";
echo round(microtime(true)-Start,2). " seconds elapsed"; 

function flow($cell,$dir) {
	global $energized, $m;
	
	if(!isset(Grid[$cell[0]][$cell[1]]))
		return;
	
	if(!isset($m[$cell[0]][$cell[1]]))
		$energized++;
	elseif (isset($m[$cell[0]][$cell[1]][$dir[0]][$dir[1]]))
		return;
	
	$m[$cell[0]][$cell[1]][$dir[0]][$dir[1]] = 0;
	
	$type = Grid[$cell[0]][$cell[1]];
	
	switch ($type) {
		case ".":
			flow(add($cell,$dir),$dir);
			break;
		case "-": case "|":
			if (pointy($type,$dir))
				flow(add($cell,$dir),$dir);
			else
				splitBeam($cell,$dir);
			break;
		case "\\": case "/":
			flow(reflected($type,$cell,$dir)[0],reflected($type,$cell,$dir)[1]);	
	}
}

function pointy($type,$dir) {
	if ($type == "-") {
		if ($dir[0] == 0)
			return FALSE;
	}
	else
		if ($dir[1] == 0)
			return FALSE;
	
	return TRUE;
}

function splitBeam($cell,$dir) {
		$dir = array_reverse($dir);
		flow(add($cell,$dir),$dir);
		
		$dir = mult($dir,[-1,-1]);
		flow(add($cell,$dir),$dir);
}

function reflected($type,$cell,$dir) {
	if ($type == "\\")
		$sign = 1;
	else $sign = -1;
	
	$dir = mult(array_reverse($dir),[$sign,$sign]);
	
	$cell = add($cell,$dir);

	return [$cell,$dir];
}

function mult($array1,$array2) {
	$length = count($array1);
	for ($i=0;$i<$length;$i++)
		$array[] = $array1[$i] * $array2[$i];
	return $array;
}

function add($array1,$array2) {
	$length = count($array1);
	for ($i=0;$i<$length;$i++)
		$array[] = $array1[$i] + $array2[$i];
	return $array;
}

?>
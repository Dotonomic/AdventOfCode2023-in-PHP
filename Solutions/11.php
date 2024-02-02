<?php

define("Universe",file("11input.txt"));

define("numColumns",strlen(Universe[0]));

define("numRows",count(Universe));

foreach (Universe as $line) {
	$expandedUniverse[] = $line;
	if (!preg_match("/#/",$line))
		$expandedUniverse[] = str_repeat("*",numColumns);
}

$columnsAdded = 0;
for ($i=0;$i<numColumns;$i++) {
	$blank = TRUE;
	for ($j=0;$j<numRows & $blank;$j++)
		if (substr(Universe[$j],$i,1) == "#") {
			$blank = FALSE;
			break;
		}
	if ($blank) {
		addColumn($i+$columnsAdded);
		$columnsAdded++;
	}
}

define ("width",strlen($expandedUniverse[0]));

foreach ($expandedUniverse as $lineNumber => $line) {
	$firstColumn[] = substr($line,0,1);
	for ($i=0;$i<width;$i++)
		if (substr($line,$i,1) == "#")
			$galaxies[] = [$i,$lineNumber];
}

define ("firstRow",str_split($expandedUniverse[0]));

echo sumDist()." - ".sumDist1000000();


function addColumn($k) {
	global $expandedUniverse;
	foreach ($expandedUniverse as $lineNumber => $line) {
		$expandedUniverse[$lineNumber] = substr($line,0,$k+1)."*".substr($line,$k+1);
	}
}

function sumDist() {
	global $galaxies;
	$total = 0;
	
	for ($i=0;$i<count($galaxies)-1;$i++)
		for ($j=$i+1;$j<count($galaxies);$j++)
			$total += manhattanDist($galaxies[$i],$galaxies[$j]);
	return $total;
}

function sumDist1000000() {
	global $galaxies;
	$total = 0;
	
	for ($i=0;$i<count($galaxies)-1;$i++)
		for ($j=$i+1;$j<count($galaxies);$j++)
			$total += manhattanDist1000000($galaxies[$i],$galaxies[$j]);
	return $total;
}

function manhattanDist($point1,$point2) {
	return abs($point1[0] - $point2[0]) + abs($point1[1] - $point2[1]);
}

function manhattanDist1000000($point1,$point2) {
	global $firstColumn;
	$eCols = 0;
	$eRows = 0;
	
	for ($i=min($point1[0],$point2[0])+2;$i<max($point1[0],$point2[0]);$i++)
		if (firstRow[$i] == "*")
			$eRows++;
		
	for ($i=min($point1[1],$point2[1])+2;$i<max($point1[1],$point2[1]);$i++)
		if ($firstColumn[$i] == "*")
			$eCols++;

	return manhattanDist($point1,$point2) + 999998 * ($eCols + $eRows);
}

?>

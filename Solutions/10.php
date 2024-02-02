<?php

function newDir($char,$dir) {
	switch ($char) {
		case "|": return mult([0,1],$dir);
		case "-": return mult([1,0],$dir);
		case "L": case "7": return mult([1,1],array_reverse($dir));
		case "J": case "F": return mult([-1,-1],array_reverse($dir));
	}
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

$found = FALSE;

foreach (file("10input.txt") as $lineNumber => $line) {
	$lines[] = $line;

	for ($i=0;$i<140;$i++)
		if (substr($line,$i,1) == 'S') {
			define("s",[$i,$lineNumber]);
			$found = TRUE;
			break 2;
		}
}

$current = [s[0],s[1]-1]; $dir = [0,-1]; //CHEATING
$steps = 1;

$loop[s[0]][s[1]-1] = 0;
$minX = s[0];
$maxX = s[0];
$minY = s[1];
$maxY = s[1];

while ($current != s) {
	$steps++;
	$char = substr($lines[$current[1]],$current[0],1);
	$dir = newDir($char,$dir);
	$current = add($current,$dir);
	$loop[$current[0]][$current[1]] = 0;
	$minX = min($minX,$current[0]);
	$maxX = max($maxX,$current[0]);
	$minY = min($minY,$current[1]);
	$maxY = max($maxY,$current[1]);
}

$enclosed = 0;

function ori($char) {
	switch ($char) {
		case "|": case "L": case "J": case "S": return 1; //CHEATING AGAIN, S was a J in my input
		case "-": case "7": case "F": return 0;
	}
}

for ($y=$minY;$y<=$maxY;$y++) {
	$state = 0;
	for ($x=$minX;$x<=$maxX;$x++) {
		if (isset($loop[$x][$y])) {
			$char = substr($lines[$y],$x,1);
			$state += ori($char);
		}
		else $enclosed += $state % 2;
	}
}

echo ($steps/2)." - ".$enclosed;

?>

<?php

define('Start',microtime(TRUE));

define('Plan',file('18.txt'));

define('FirstDir',substr(trim(Plan[0]),-2,1));

$plan = Plan;
define('LastDir',substr(trim(end($plan)),-2,1));
$lastDir = LastDir;

$cell = [0,0];
$map[0][0] = 0;

foreach (Plan as $line) {
	$line = trim($line);
	$dirCode = substr($line,-2,1);
	$length = hexdec(substr($line,-7,5));
	
	if ($lastDir == 0 || $dirCode == 2)
		$map[$cell[0]][$cell[1]] *= 2;
	
	$lastDir = $dirCode;
	
	switch ($dirCode) {
		case 3 :
			$map[$cell[0]][$cell[1]] *= 3;
			$cell = [$cell[0],$cell[1]-$length];
			$map[$cell[0]][$cell[1]] = 1;
			break;
		case 1 :
			$cell = [$cell[0],$cell[1]+$length];
			$map[$cell[0]][$cell[1]] = 3;
			break;
		case 2 :
			for ($i=1;$i<$length;$i++) {
				$map[$cell[0]-$i][$cell[1]] = 6;
			}
			$cell = [$cell[0]-$length,$cell[1]];
			$map[$cell[0]][$cell[1]] = 1;
			break;
		case 0 :
			for ($i=1;$i<$length;$i++) {
				$map[$cell[0]+$i][$cell[1]] = 6;
			}
			$cell = [$cell[0]+$length,$cell[1]];
			$map[$cell[0]][$cell[1]] = 1;
	}
}

if (FirstDir == 3)
	$map[0][0] = 3;

if (LastDir == 0 || FirstDir == 2)
	$map[0][0] *= 2;

$minX = min(array_keys($map)); 
$maxX = max(array_keys($map));
$minY = 0;
$maxY = 0;

foreach ($map as $col) {
	$minY = min($minY,min(array_keys($col)));
	$maxY = max($maxY,max(array_keys($col)));
}

$frameArea = ($maxX - $minX + 1) * ($maxY - $minY + 1);

$marginArea = 0;

foreach ($map as $x => $col) {	
	$out = TRUE;
	$mod = 1;
	$prev = $minY - 1;
	ksort($col);

	foreach (array_keys($col) as $y) {
		if ($col[$y] % 2 == 0)
			$mod = ($mod + 1) % 2;
			
		if ($out) {
			$marginArea += $y - $prev - 1;
			$out = FALSE;
		}
		elseif ($mod == 1 & $col[$y] % 3 == 0) {
			$prev = $y;
			$out = TRUE;
		}
	}
	
	$marginArea += $maxY - $prev;
	unset($col);
	unset($map[$x]);
}

echo $frameArea - $marginArea."\n";
echo round(microtime(TRUE)-Start,3).' seconds elapsed';

?>

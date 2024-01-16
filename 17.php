<?php

define("Start",microtime(TRUE));

foreach (file("17.txt") as $row => $line) {
	$line = str_split(preg_replace("/\n|\s/","",$line));
	foreach ($line as $col => $element)
		$grid[$col][$row] = $element;
}

define("HeatLossMap",$grid);
define("Height",count(HeatLossMap[0]));
define("Width",count(HeatLossMap));

define("Manhattan",manhattan([0,0]) * 9);

move([4,0],[1,0],1,3,HeatLossMap[1][0]);
move([4,0],[1,0],3,3,HeatLossMap[1][0]);
move([0,4],[0,1],1,3,HeatLossMap[0][1]);
move([0,4],[0,1],3,3,HeatLossMap[0][1]);

echo round(microtime(true)-Start,3). " seconds elapsed";

function move($cell,$dir,$option,$count,$heatLoss) {
	global $leastHeatLoss,$m;

	$heatLoss += HeatLossMap[$cell[0]][$cell[1]];

	if ($count==3)
		for ($i=1;$i<3;$i++) {
			$backDir = mult($dir,[-$i,-$i]);
			$pCell = add($cell,$backDir);
			$heatLoss += HeatLossMap[$pCell[0]][$pCell[1]];
		}

	if (isset($leastHeatLoss))
		if ($heatLoss + manhattan($cell) >= $leastHeatLoss)
			return;

	if ($heatLoss + manhattan($cell) * 9 >= Manhattan)
		return;
		
	if ($cell == [Width-1,Height-1] & $count > 2) {
		$leastHeatLoss = $heatLoss;

		echo $leastHeatLoss."\n";
		return;
	}

	if (isset($m[$cell[0]][$cell[1]][$dir[0]][$dir[1]][$option][$count]))
		if ($heatLoss >= $m[$cell[0]][$cell[1]][$dir[0]][$dir[1]][$option][$count])
			return;
	
	$m[$cell[0]][$cell[1]][$dir[0]][$dir[1]][$option][$count] = $heatLoss;

	switch ($option) {
		case 1 :
			$newDir = array_reverse($dir);
			$count = 0;
			break;
		case 2 :
			$newDir = mult(array_reverse($dir),[-1,-1]);
			$count = 0;
			break;
		case 3 :
			$newDir = $dir;
			$count++;
			break;
		case 4 :
			$newDir = $dir;
			$count = 3;
			$cell = add(add($cell,$newDir),$newDir);
	}

	$cell = add($cell,$newDir);

	if (!isset(HeatLossMap[$cell[0]][$cell[1]]))
		return;

	if ($count < 2) {
		$option = 0;
		move($cell,$newDir,4,$count,$heatLoss);
	}
	elseif ($count < 9)
		$option = 3;
	else {
		$option = 2;
		$count = 0;
	}

	while ($option) {
		move($cell,$newDir,$option,$count,$heatLoss);
		$option--;
	}
}

function manhattan($cell) {
	return Width - $cell[0] - 1 + Height - $cell[1] - 1; 
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
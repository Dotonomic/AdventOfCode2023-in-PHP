<?php

define("Line",file_get_contents("15.txt"));

define("Sequence",explode(",",Line));

$sum = 0;

$focusPower = 0;

$boxes = array_fill(0,256,[]);

foreach (Sequence as $step) {
	$sum += algo($step);
	
	$label = preg_replace("/=|-|[1-9]/","",$step);
	$box = algo($label);
	$operation = preg_replace("/[a-z]|[1-9]/","",$step);
	
	if ($operation == '-') {
		if (in_array($label,$boxes[$box])) {
			$slot = array_search($label,$boxes[$box]);
			
			unset($lenses[$label]);
			
			$boxes[$box] = array_merge(array_slice($boxes[$box],0,$slot),array_slice($boxes[$box],$slot+1));
			
			for ($i=$slot;$i<count($boxes[$box]);$i++)
				$lenses[$boxes[$box][$i]][1]--;
		}
	}
	elseif ($operation == '=') {
		$focus = preg_replace("/=|-|[a-z]/","",$step);
		
		if (in_array($label,$boxes[$box])) {
			$slot = array_search($label,$boxes[$box]);
			$lenses[$label][2] = $focus;
		}
		else {
			$slot = count($boxes[$box]);
			$boxes[$box][] = $label;
			$lenses[$label] = [$box,$slot,$focus];
		}
	}
}

foreach ($lenses as $lens)
	$focusPower += ($lens[0] + 1) * ($lens[1] + 1) * $lens[2];

echo $sum."\n";
echo $focusPower;

function algo($string) {
	$array = str_split($string);
	$value = 0;
	
	foreach ($array as $char) {
		$value += ord($char);
		$value *= 17;
		$value %= 256;
	}
	
	return $value;
}

?>
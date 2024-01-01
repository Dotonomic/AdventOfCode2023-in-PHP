<?php

$instructions = "00101001101010011011101110110110101011100101101011101001101010011100101110101101101001101101010100100110110110110110100101011101110110101011011101011101010101110110110111000011011010111010111011000010101110111010110010101110101011101001110110101011101001100111011101110110101011101110111001111";

foreach (file("8input.txt") as $lineKey => $line) {
	$index[$lineKey] = substr($line,0,3);
	if (substr($line,2,1) == 'Z') $indexZ[$lineKey] = '';
}

function getKey($string,$index) {
	foreach ($index as $key => $i) if ($string == $i) return $key; 
}

foreach (file("8input.txt") as $lineKey => $line) {
	$arr[$lineKey][0] = getKey(substr($line,7,3),$index);
	$arr[$lineKey][1] = getKey(substr($line,12,3),$index);
    if (substr($line,2,1) == 'A') $current[] = $lineKey;
}

for ($k=0;$k<count($current);$k++) {
	
	$inst = 0;
	$steps = 0;
	$c = $current[$k];
	
	while (!isset($indexZ[$c])) {
	
		$c = $arr[$c][substr($instructions,$inst,1)];
		   
		$inst++;
		$steps++;
	
		if ($inst == strlen($instructions)) $inst = 0;
	}

	$psteps[] = $steps;
}

foreach ($psteps as $steps) echo $steps." "; //ANSWER = LEAST COMMON MULTIPLE

?>

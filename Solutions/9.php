<?php

$sum = 0;

foreach (file("9input.txt") as $line) {
	$levels[0] = $line;
	$line = explode(" ",$line);
	$l = 1;
	
	$not0 = TRUE;
	while ($not0) {
		$line = diff($line);
		$levels[$l] = implode(" ",$line);
		$l++;
		$not0 = FALSE;
		foreach ($line as $number) if ($number != 0) $not0 = TRUE;
	}
	
	print_r($levels);
	
	$extrapolated = extrapolate($levels);
	$sum += $extrapolated;
	unset($levels);
	
    echo $extrapolated."\n".$sum."\n\n";
}

echo 'SUM: '.$sum;

function diff($line) {
	for ($key=0;$key<=count($line)-2;$key++) {
		$line[$key] = $line[$key+1] - $line[$key];
	}
	array_pop($line);
	return $line;
}

function extrapolate($levels) {
    foreach ($levels as $levelKey => $level) $levels[$levelKey] = explode(" ",$level);
    
	$levels[count($levels)-1][] = $levels[count($levels)-1][0];

	for ($l=count($levels)-2;$l>=0;$l--) {
		$levels[$l][] = $levels[$l][0] - $levels[$l+1][count($levels[$l+1])-1];
	}
	return $levels[0][count($levels[0])-1];
}

?>

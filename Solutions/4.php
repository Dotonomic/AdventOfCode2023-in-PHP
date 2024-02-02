<?php
$total = 0;

$scratchies = file("4input.txt");

foreach ($scratchies as $key => $line) {
	$line = preg_replace("/.*\:\s+/","",$line);
	$line = preg_replace("/\s+\|\s+/",",",$line);
	$line = preg_replace("/\s+/",",",$line);
	$line = preg_replace("/,$/","",$line);
	$line = explode(",",$line);
	$scratchies[$key] = $line;
}

foreach ($scratchies as $key => $line) {
	$matches = 0;
	$exp = 0;
	for ($i=0;$i<10;$i++) {
		for ($j=10;$j<35;$j++) {
			if ($line[$i] == $line[$j]) {$matches++; $exp++;}
		}
	}
	
	$m[$key] = $matches;
	
	if ($exp) $total += pow(2,$exp-1);
}

for ($i=0;$i<199;$i++) $c[$i] = 1;

foreach ($m as $key => $matches) {
    for ($i=1;$i<=$matches;$i++) {
        $c[$key+$i] += $c[$key]; 
    }
}

echo $total." - ".array_sum($c);
?>

<?php

for ($K=0;$K<5;$K++) {
	$arrangements = 0;
	
	$start = microtime(true);
	
	foreach (file("12input.txt") as $lineNumber => $line) {
		echo "\nProcessing line ".$lineNumber."...\n";
	
		$line = explode(" ",$line);
		$line[0] = preg_replace("/\.{2,}/",".",$line[0]);
		$line[1] = preg_replace("/\n/","",$line[1]);
		$line[1] = explode(",",$line[1]);

		$line[0] = $line[0].str_repeat('?'.$line[0],$K);
		$line[1] = array_merge(...array_fill(0,$K+1,$line[1]));

		$line[0] = preg_replace("/^\.|\.$/","",$line[0]);
		$line[0] = str_split($line[0]);
	
		$arrangements += arrangements($line);
	
		echo round(microtime(true)-$start,2)." seconds elapsed.\n";
	}
	
	echo "\nTotal with ".$K." unfoldings: ".$arrangements."\n";
	readline("PRESS ANY KEY");
}

function arrangements($line) {
	global $m;
	
	if (isset($m[implode($line[0])][implode(" ",$line[1])]))
		return $m[implode($line[0])][implode(" ",$line[1])];
	
	$count = 0;
	
	$block = $line[1][0];
	
	if (!isset($line[0][$block-1])) {
		$m[implode($line[0])][implode(" ",$line[1])] = 0;
		return 0;
	}

	if (isset($line[0][$block])) {
		if ($line[0][0] == '?')
			if (isset($line[0][1])) {
				if ($line[0][1] == '.')
					$subLine[0] = array_slice($line[0],2);
				else
					$subLine[0] = array_slice($line[0],1);
		
				$subLine[1] = $line[1];
			
				$count += arrangements($subLine);
			}
		
		if ($line[0][$block] == '#') {
			$m[implode($line[0])][implode(" ",$line[1])] = $count;
			return $count;
		}
		
		for ($i=1;$i<$block;$i++)
			if ($line[0][$i] == '.') {
				$m[implode($line[0])][implode(" ",$line[1])] = $count;
				return $count;
		}		
			
		if (isset($line[1][1]) & isset($line[0][$block+1])) {
			if ($line[0][$block+1] == '.')
				$subLine[0] = array_slice($line[0],$block+2);
			else
				$subLine[0] = array_slice($line[0],$block+1);
		
			$subLine[1] = array_slice($line[1],1);
			
			$result = $count + arrangements($subLine);
			$m[implode($line[0])][implode(" ",$line[1])] = $result;
			return $result;
		}
	}
	 
	if (!isset($line[1][1])) {
		for ($i=1;$i<$block;$i++)
			if ($line[0][$i] == '.') {
				$m[implode($line[0])][implode(" ",$line[1])] = 0;
				return 0;
			}
		
		$block++;
		while (isset($line[0][$block])) {
			if ($line[0][$block] == '#') {
				$m[implode($line[0])][implode(" ",$line[1])] = $count;
				return $count;
			}
			
			$block++;
		}
		
		$count++;
	}
	
	$m[implode($line[0])][implode(" ",$line[1])] = $count;
	return $count;
}

?>

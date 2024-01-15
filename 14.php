<?php

foreach (file("14.txt") as $line)
	$lines[] = str_split(preg_replace("/\n|\s/","",$line));

$length = count($lines[0]);
$rows = count($lines);
$aux = count($lines);

$cycle = 0;

$periodNotFound = TRUE;

$m[0] = $lines;

while ($cycle < 1000000000) {
	for ($i=1;$i<=4;$i++) {
		
		for ($col=0;$col<$length;$col++) {	
			$free = 0;
	
			for ($row=0;$row<$rows;$row++)
				switch ($lines[$row][$col]) {
					case 'O':
						if ($free)
							move($row,$col,$free);
						break;
					case '.':
						$free++;
						break;
					case '#':
						$free = 0;
				}
		}
		
		$lines = transposed($lines,$length,$rows);
		$rows = $length;
		$length = $aux;
		$aux = $rows;
	}
	
	$cycle++;
	
	if ($periodNotFound) {
		for ($i=$cycle-1;$i>=0;$i--)
			if ($m[$i] === $lines) {
				$cycle = 1000000000 - (1000000000 - $cycle) % ($cycle - $i);
				$periodNotFound = FALSE;
				break;
			}
	
		$m[] = $lines;
	}
}

$load = 0;

for ($col=0;$col<$length;$col++) //{
	//$free = 0;
	
	for ($row=0;$row<$rows;$row++)
		switch ($lines[$row][$col]) {
			case 'O':
				$load += $rows - $row; /* + $free;
				break;
			case '.':
				$free++;
				break;
			case '#':
				$free = 0; */
		}
//}	

echo $load;

function transposed($lines,$length,$rows) {
	for ($col=0;$col<$length;$col++)
		for ($row=0;$row<$rows;$row++)
			$newLines[$col][$row] = $lines[$rows-$row-1][$col];
	return $newLines;
}

function move($row,$col,$free) {
	global $lines;
	$lines[$row][$col] = '.';
	$lines[$row-$free][$col] = 'O';
}

?>
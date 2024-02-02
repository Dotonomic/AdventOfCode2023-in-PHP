<?php

$y = 0;
$x = 0;

define("Input",file("13.txt"));

$p = 0;
$i = 0;

while (isset(Input[$i])) {
	$line = preg_replace("/\n|\s/","",Input[$i]);
	
	if (empty($line))
		$p++;
	else
		$patterns[$p][] = $line;
	
	$i++;
}

foreach ($patterns as $pattern) {
	$length = strlen($pattern[0]);
	
	for ($key=0;$key<$length;$key++)
		if (okY($key,$pattern)) {
			$y += $key + 1;
			break;
		}

	$length = count($pattern);
	
	for ($key=0;$key<$length;$key++)
		if (okX($key,$pattern)) {
			$x += $key + 1;
			break;
		}
}
		
echo $y + 100 * $x;
	
function okY($key,$pattern) {
	$smudges = 0;
	$i = 0;
	while (isset($pattern[$i]) & $smudges <= 2) {
		$smudges += smudges($key,str_split($pattern[$i]));
		$i++;
	}
	
	if ($smudges == 1)
		return TRUE;
	
	return FALSE;
}

function okX($key,$pattern) {
	foreach ($pattern as $lineKey => $line)
		foreach (str_split($line) as $col => $value)
			$arr[$col][$lineKey] = $value;
	
	$smudges = 0;
	$i = 0;
	while (isset($arr[$i]) & $smudges <= 2) {
		$smudges += smudges($key,$arr[$i]);
		$i++;
	}
	
	if ($smudges == 1)
		return TRUE;
	
	return FALSE;
}

function smudges($key,$arr) {
	$min = min($key,count($arr)-$key-2);
	
	$smudges = 0;
	
	for ($i=0;$i<=$min & $smudges<=2;$i++)
		if ($arr[$key+1+$i] != $arr[$key-$i])
			$smudges++;
	
	return $smudges;
}

?>
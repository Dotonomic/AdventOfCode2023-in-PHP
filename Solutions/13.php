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
	$array = str_split($pattern[0]);
	
	foreach ($array as $key => $value)
		if (isset($array[$key+1]))
			if ($value == $array[$key+1])
				if (okY($key,$pattern))
					$y += $key + 1;

	foreach ($pattern as $line)
		$arr[] = substr($line,0,1);

	foreach ($arr as $key => $value)
		if (isset($arr[$key+1]))
			if ($value == $arr[$key+1])
				if (okX($key,$pattern))
					$x += $key + 1;
	unset($arr);
}
		
echo $y + 100 * $x;
	
function okY($key,$pattern) {
	$i = 0;
	while (isset($pattern[$i])) {
		if (notSym($key,str_split($pattern[$i])))
			return FALSE;
		$i++;
	}
	
	return TRUE;
}

function okX($key,$pattern) {
	foreach ($pattern as $lineKey => $line)
		foreach (str_split($line) as $col => $value)
			$arr[$col][$lineKey] = $value;
	
	$i = 0;
	while (isset($arr[$i])) {
		if (notSym($key,$arr[$i]))
			return FALSE;
		$i++;
	}
	
	return TRUE;
}

function notSym($key,$arr) {
	$min = min($key,count($arr)-$key-2);
	for ($i=0;$i<=$min;$i++)
		if ($arr[$key+1+$i] != $arr[$key-$i])
			return TRUE;
	
	return FALSE;
}

?>
<?php

function waysToWin($time,$record) {
	$button = 1;
	$t = ceil($time/2);
	
	while ($button < $t) {
		if ($button*($time-$button) > $record) {
		    return 2*($t-$button) + 1 - $time%2;
		}
    $button++;
	}
}

echo waysToWin(/*TIME*/,/*RECORD*/);

?>

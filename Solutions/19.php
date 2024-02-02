<?php

define('AandR',
'<?php

function A($x,$m,$a,$s) {return $x + $m + $a + $s;}

function R() {return 0;}

'
);

file_put_contents('workflows.php',AandR);

define('Workflows',file('workflows.txt'));

foreach (Workflows as $workflow) {
	$workflow = trim($workflow);
	
	$workflow = 'function '.$workflow;
	
	$workflow = str_replace(',','($x,$m,$a,$s); case $',$workflow);
	
	$workflow = str_replace('{','($x,$m,$a,$s) { switch (TRUE) {case $',$workflow);
	
	$workflow = str_replace(':',':return ',$workflow);
	
	$lastSemicolon = strrpos($workflow,';') + 1;
	
	$workflow = substr_replace($workflow,' default:return ',$lastSemicolon,7);
	
	$workflow = str_replace('}','($x,$m,$a,$s);}}',$workflow);
	
	$workflow .= "\n\n";
	
	file_put_contents('workflows.php',$workflow,FILE_APPEND);
}

file_put_contents('workflows.php','?>',FILE_APPEND);

require 'workflows.php';

$total = 0;

define('Parts',file('parts.txt'));

foreach (Parts as $part) {
	$part = trim($part);
	
	$part = str_replace(array('x','m','a','s','=','{','}'), '', $part);
	
	$part = explode(',',$part);
	
	$total += in($part[0],$part[1],$part[2],$part[3]);
}

echo $total;

?>

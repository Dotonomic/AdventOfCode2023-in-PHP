<?php

define('MinRating',1);
define('MaxRating',4000);

function intersect($rangeUnion, $dir, $limit) {
	$result = [];
	
	if ($dir)
		$sieve = range($limit+1,MaxRating);
	else
		$sieve = range(MinRating,$limit-1);

	foreach ($rangeUnion as $range)
		if (!empty($range)) {
			$range = range($range[0],$range[1]);
			
			$intersection = array_intersect($range,$sieve);
		
			if (!empty($intersection))
				$result[] = [min($intersection),max($intersection)];
		}
	
	return $result;
}

function product($rangeUnions) {
	$result = 1;
	
	foreach ($rangeUnions as $rangeUnion) {
		$sum = 0;
		
		foreach ($rangeUnion as $range)
			if (!empty($range))
				$sum += $range[1] - $range[0] + 1;
			
		$result *= $sum;
	}
	
	return $result;
}

function subtract($rRangeUnion,$rangeUnion) {
	$result = [];
	
	foreach ($rRangeUnion as $rRange)
		foreach ($rangeUnion as $range)
			if (!empty($range) & !empty($rRange)) {
				$range = range($range[0],$range[1]);
			
				$rRangeRange = range($rRange[0],$rRange[1]);
			
				$difference = array_diff($range,$rRangeRange);
			
				if (!empty($difference))
					$result[] = [min($difference),max($difference)];
			}
	
	return $result;
}

define('AandR',
'<?php

function A($christmas) {return product($christmas);}

function R() {return 0;}

'
);

file_put_contents('workflows.php',AandR);

define('Workflows',file('workflows.txt'));

foreach (Workflows as $workflow) {
	$workflow = trim($workflow);
	
	$workflow = 'function '.$workflow;
	
	$workflow = str_replace(',', '($xmas); $xmas[$l] = subtract($xmas[$l],$aux); ', $workflow);
	
	$workflow = str_replace('x<', '$l = 0; $aux = $xmas[0]; $xmas[0] = intersect($aux, FALSE, ', $workflow);
	$workflow = str_replace('m<', '$l = 1; $aux = $xmas[1]; $xmas[1] = intersect($aux, FALSE, ', $workflow);
	$workflow = str_replace('a<', '$l = 2; $aux = $xmas[2]; $xmas[2] = intersect($aux, FALSE, ', $workflow);
	$workflow = str_replace('s<', '$l = 3; $aux = $xmas[3]; $xmas[3] = intersect($aux, FALSE, ', $workflow);
	
	$workflow = str_replace('x>', '$l = 0; $aux = $xmas[0]; $xmas[0] = intersect($aux, TRUE, ', $workflow);
	$workflow = str_replace('m>', '$l = 1; $aux = $xmas[1]; $xmas[1] = intersect($aux, TRUE, ', $workflow);
	$workflow = str_replace('a>', '$l = 2; $aux = $xmas[2]; $xmas[2] = intersect($aux, TRUE, ', $workflow);
	$workflow = str_replace('s>', '$l = 3; $aux = $xmas[3]; $xmas[3] = intersect($aux, TRUE, ', $workflow);
	
	$workflow = str_replace('{', '($christmas) {$xmas = $christmas; $c = 0; ', $workflow);
	
	$workflow = str_replace(':', '); $c += ', $workflow);
	
	$lastSemicolon = strrpos($workflow,';');
	
	$workflow = substr_replace($workflow, ' $c += ', $lastSemicolon + 1, 0);
	
	$workflow = str_replace('}', '($xmas); return $c;}', $workflow);
	
	$workflow .= "\n\n";
	
	file_put_contents('workflows.php',$workflow,FILE_APPEND);
}

file_put_contents('workflows.php','?>',FILE_APPEND);

require 'workflows.php';

echo in(array([[MinRating,MaxRating]],[[MinRating,MaxRating]],[[MinRating,MaxRating]],[[MinRating,MaxRating]]));

?>

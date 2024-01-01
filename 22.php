<?php

foreach (file("22input.txt") as $lineKey => $line) {
    $array[$lineKey] = explode("~" , $line);
    $array[$lineKey][0] = explode("," , $array[$lineKey][0]);
    $array[$lineKey][1] = explode("," , $array[$lineKey][1]);
    $array[$lineKey]['max_X'] = max($array[$lineKey][0][0],$array[$lineKey][1][0]);
    $array[$lineKey]['max_Y'] = max($array[$lineKey][0][1],$array[$lineKey][1][1]);
    $array[$lineKey]['max_Z'] = max($array[$lineKey][0][2],$array[$lineKey][1][2]);
    $array[$lineKey]['min_X'] = min($array[$lineKey][0][0],$array[$lineKey][1][0]);
    $array[$lineKey]['min_Y'] = min($array[$lineKey][0][1],$array[$lineKey][1][1]);
    $array[$lineKey]['min_Z'] = min($array[$lineKey][0][2],$array[$lineKey][1][2]);
    if ($array[$lineKey]['min_Z'] == $array[$lineKey][0][2]) $array[$lineKey]['bottom'] = 0;
    else $array[$lineKey]['bottom'] = 1;

    if ($array[$lineKey]['max_X'] != $array[$lineKey]['min_X']) $array[$lineKey]['ori'] = 'X';
    elseif ($array[$lineKey]['max_Y'] != $array[$lineKey]['min_Y']) $array[$lineKey]['ori'] = 'Y';
    else $array[$lineKey]['ori'] = 'Z';
}

usort($array, fn($a, $b) => $a['min_Z'] <=> $b['min_Z']);

for ($x=0;$x<=9;$x++) {
    for ($y=0;$y<=9;$y++) {
        $map[$x][$y] = 0;
    }
}

foreach ($array as $key => $brick) {
    $newZ = 1;
    if ($brick['ori'] == 'X') {
        for ($i=$brick['min_X'];$i<=$brick['max_X'];$i++) {
            $newZ = max($newZ,$map[$i][$brick['min_Y']]+1);
        }
        for ($i=$brick['min_X'];$i<=$brick['max_X'];$i++) {
            $map[$i][$brick['min_Y']] = $newZ;
        }
        $array[$key][0][2] = $newZ;
        $array[$key][1][2] = $newZ;
        $array[$key]['min_Z'] = $newZ;
        $array[$key]['max_Z'] = $newZ;
    }
    elseif ($brick['ori'] == 'Y') {
        for ($i=$brick['min_Y'];$i<=$brick['max_Y'];$i++) {
            $newZ = max($newZ,$map[$brick['min_X']][$i]+1);
        }
        for ($i=$brick['min_Y'];$i<=$brick['max_Y'];$i++) {
            $map[$brick['min_X']][$i] = $newZ;
        }
        $array[$key][0][2] = $newZ;
        $array[$key][1][2] = $newZ;
        $array[$key]['min_Z'] = $newZ;
        $array[$key]['max_Z'] = $newZ;
    }
    else {
        $minZ = $map[$brick['min_X']][$brick['min_Y']]+1;
        $array[$key][$array[$key]['bottom']][2] = $minZ;
        
        $newZ = $minZ + ($array[$key]['max_Z']-$array[$key]['min_Z']);
        $array[$key][($array[$key]['bottom']+1)%2][2] = $newZ;
        $map[$brick['min_X']][$brick['min_Y']] = $newZ;
        $array[$key]['min_Z'] = $minZ;
        $array[$key]['max_Z'] = $newZ;
    }    
}

$conn = mysqli_connect("localhost", "root", "", "") or die("Connection Error: " . mysqli_error($conn));

$free = 0;
$arrayCopy = $array;

foreach ($array as $key => $brick) {
    $support = false;
    foreach ($arrayCopy as $keyCopy => $brickCopy) {
        if ($key != $keyCopy & $brick['min_X'] >= $brickCopy['min_X'] & $brick['min_X'] <= $brickCopy['max_X'] & $brick['max_Z']+1 == $brickCopy['min_Z'] & $brickCopy['min_Y'] >= $brick['min_Y'] & $brickCopy['min_Y'] <= $brick['max_Y']) {
            mysqli_query($conn, "INSERT INTO graph VALUES (".$key.",".$keyCopy.")");
            $support = true;
        }
        elseif ($key != $keyCopy & $brick['min_Y'] >= $brickCopy['min_Y'] & $brick['min_Y'] <= $brickCopy['max_Y'] & $brick['max_Z']+1 == $brickCopy['min_Z'] & $brickCopy['min_X'] >= $brick['min_X'] & $brickCopy['min_X'] <= $brick['max_X']) {
            mysqli_query($conn, "INSERT INTO graph VALUES (".$key.",".$keyCopy.")");
            $support = true;
        }
    }
    if (!$support) $free++;
}

$ok = $free + mysqli_num_rows(mysqli_query($conn, "SELECT DISTINCT(below) FROM graph WHERE below NOT IN (SELECT below FROM graph GROUP BY above HAVING COUNT(*)=1)"));

$query = mysqli_query($conn, "SELECT * FROM graph");
while ($obj = mysqli_fetch_object($query))
	$graph[] = [$obj->below,$obj->above];

function disintegrate($arr,$graph) {
    global $count;
    
    for ($i=0;$i<count($arr);$i++) {
		$graph2 = $graph;
		foreach ($graph2 as $x => $node) {
			if ($node[0] == $arr[$i]) {
				$n = $node[1];
	            $gone = TRUE;
                unset($graph2[$x]);
                
                foreach ($graph2 as $nodeY) {if ($nodeY[1] == $n) $gone = FALSE;}
                
                if ($gone) {
                    $count++;
                    $arr2[] = $n;
                }
			}
		}
		if (isset($arr2)) {disintegrateP($arr2,$graph2); unset($arr2);}
    }
}

function disintegrateP($arr,$graph) {
    global $count;
	
    for ($i=0;$i<count($arr);$i++) {
		foreach ($graph as $x => $node) {
			if ($node[0] == $arr[$i]) {
				$n = $node[1];
	            $gone = TRUE;
                unset($graph[$x]);
                
                foreach ($graph as $nodeY) {if ($nodeY[1] == $n) $gone = FALSE;}
                
                if ($gone) {
                    $count++;
                    $arr2[] = $n;
                }
			}
		}
    }
	if (isset($arr2)) {disintegrateP($arr2,$graph); unset($arr2);}
}

$count = 0;

foreach ($array as $key => $value) $array[$key] = $key;

disintegrate($array,$graph);

echo $ok.' - '.$count;

?>

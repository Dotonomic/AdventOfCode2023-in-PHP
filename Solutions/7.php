<?php

function type($hand) {
	
	switch ($hand) {
		case fiveOfAKind($hand) : return 6;
		case fourOfAKind($hand) : return 5;
		case fullHouse($hand) : return 4;
		case threeOfAKind($hand) : return 3;
		case twoPair($hand) : return 2;
		case onePair($hand) : return 1;
	}
	return 0;
}

function fiveOfAKind($hand) {
	for ($i=0;$i<5;$i++)
		if (substr($hand,$i,1) != 'J') {
			$kind = substr($hand,$i,1);
			break;
		}

	if (isset($kind)) {
		$hand = str_replace('J',$kind,$hand);
		
		for ($i=0;$i<5;$i++)
			if (substr($hand,$i,1) != $kind)
				return FALSE;
	}
	
	return TRUE;
}

function fourOfAKind($hand) {
	
	for ($i=0;$i<5;$i++) {
		$card = substr($hand,$i,1);
		
		$wildHand = str_replace('J',$card,$hand);
		
		if (preg_match_all("/".$card."/",$wildHand) == 4)
			return TRUE;
	}
	
	return FALSE;
}

function fullHouse($hand) {

	for ($i=0;$i<5;$i++) {
		$card = substr($hand,$i,1);
		if (preg_match_all("/".$card."/",$hand) == 3)
			for ($j=0;$j<5;$j++) {
				$card = substr($hand,$j,1);
				
				$wildHand = str_replace('J',$card,$hand);
				
				if (preg_match_all("/".$card."/",$wildHand) == 2)
					return TRUE;
			}
		else {
			$wildHand = str_replace('J',$card,$hand);
			if (preg_match_all("/".$card."/",$wildHand) == 3)
				for ($j=0;$j<5;$j++) {
					$card = substr($wildHand,$j,1);
					if (preg_match_all("/".$card."/",$wildHand) == 2)
						return TRUE;
				}
		}
	}

	return FALSE;
}

function threeOfAKind($hand) {

	for ($i=0;$i<5;$i++) {
		$card = substr($hand,$i,1);
		
		$wildHand = str_replace('J',$card,$hand);
		
		if (preg_match_all("/".$card."/",$wildHand) == 3)
			return TRUE;
	}

	return FALSE;
}

function twoPair($hand) {

	for ($i=0;$i<5;$i++) {
		$card = substr($hand,$i,1);
		if (preg_match_all("/".$card."/",$hand) == 2)
			for ($j=0;$j<5;$j++) {
				$card2 = substr($hand,$j,1);
				
				$wildHand = str_replace('J',$card2,$hand);
				
				if ($card2 != $card & preg_match_all("/".$card2."/",$wildHand) == 2)
					return TRUE;
			}
		else {
			$wildHand = str_replace('J',$card,$hand);
			if (preg_match_all("/".$card."/",$wildHand) == 2)
				for ($j=0;$j<5;$j++) {
					$card2 = substr($wildHand,$j,1);
					if ($card2 != $card & preg_match_all("/".$card2."/",$wildHand) == 2)
						return TRUE;
				}
		}
	}

	return FALSE;
}

function onePair($hand) {

	for ($i=0;$i<5;$i++) {
		$card = substr($hand,$i,1);
		
		$wildHand = str_replace('J',$card,$hand);
		
		if (preg_match_all("/".$card."/",$wildHand) == 2)
			return TRUE;
	}

	return FALSE;
}

function compare($hand1,$hand2) {
	
	if ($hand1[2] != $hand2[2])
		return $hand1[2] <=> $hand2[2];
	
	return compareCards($hand1[0],$hand2[0]);
}

function compareCards($hand1,$hand2) {
	
	for ($i=0;$i<5;$i++) {
		$card1 = substr($hand1,$i,1);
		$card2 = substr($hand2,$i,1);
		if ($card1 != $card2)
			return cardStrength($card1) <=> cardStrength($card2);
	}
}

function cardStrength($card) {
	switch ($card) {
		case "A" : return 14;
		case "K" : return 13;
		case "Q" : return 12;
		case "J" : return 1;
		case "T" : return 10;
		default : return $card;
	}
}

foreach (file("7input.txt") as $line) {
	$hand = substr($line,0,5);
	$bid = substr($line,6);
	$hands[] = [$hand,$bid,type($hand)];
}

usort($hands,"compare");

$winnings = 0;
foreach ($hands as $key => $hand)
	$winnings += $hand[1]*($key+1);

echo $winnings;

?>

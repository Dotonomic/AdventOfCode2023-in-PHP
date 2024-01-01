<?php

foreach (file('humidity-to-location.txt') as $map) {
	$humidity_location[] = explode(' ',$map);
}

foreach (file('temperature-to-humidity.txt') as $map) {
	$temperature_humidity[] = explode(' ',$map);
}

foreach (file('light-to-temperature.txt') as $map) {
	$light_temperature[] = explode(' ',$map);
}

foreach (file('water-to-light.txt') as $map) {
	$water_light[] = explode(' ',$map);
}

foreach (file('fertilizer-to-water.txt') as $map) {
	$fertilizer_water[] = explode(' ',$map);
}

foreach (file('soil-to-fertilizer.txt') as $map) {
	$soil_fertilizer[] = explode(' ',$map);
}

foreach (file('seed-to-soil.txt') as $map) {
	$seed_soil[] = explode(' ',$map);
}

$seedInfo = explode(' ',file_get_contents('seeds.txt'));
for ($i=0;$i<count($seedInfo);$i=$i+2)
	$seeds[] = [$seedInfo[$i],$seedInfo[$i+1]];

$lowestLocation = seedLocation($seeds[0][0]);

foreach ($seeds as $seedRange)
	foreach ($seed_soil as $seedSoilRange)
			if (intersects($seedRange,$seedSoilRange)) {
				$soilRange = aRange($seedSoilRange,lowerBound($seedRange,$seedSoilRange),upperBound($seedRange,$seedSoilRange));
				foreach ($soil_fertilizer as $soilFertilizerRange)
					if (intersects($soilRange,$soilFertilizerRange)) {
						$fertilizerRange = aRange($soilFertilizerRange,lowerBound($soilRange,$soilFertilizerRange),upperBound($soilRange,$soilFertilizerRange));
						foreach ($fertilizer_water as $fertilizerWaterRange)
							if (intersects($fertilizerRange,$fertilizerWaterRange)) {
								$waterRange = aRange($fertilizerWaterRange,lowerBound($fertilizerRange,$fertilizerWaterRange),upperBound($fertilizerRange,$fertilizerWaterRange));
								foreach ($water_light as $waterLightRange)
									if (intersects($waterRange,$waterLightRange)) {
										$lightRange = aRange($waterLightRange,lowerBound($waterRange,$waterLightRange),upperBound($waterRange,$waterLightRange));
										foreach ($light_temperature as $lightTemperatureRange)
											if (intersects($lightRange,$lightTemperatureRange)) {
												$temperatureRange = aRange($lightTemperatureRange,lowerBound($lightRange,$lightTemperatureRange),upperBound($lightRange,$lightTemperatureRange));
												foreach ($temperature_humidity as $temperatureHumidityRange)
													if (intersects($temperatureRange,$temperatureHumidityRange)) {
														$humidityRange = aRange($temperatureHumidityRange,lowerBound($temperatureRange,$temperatureHumidityRange),upperBound($temperatureRange,$temperatureHumidityRange));
														foreach ($humidity_location as $humidityLocationRange)
															if (intersects($humidityRange,$humidityLocationRange)) {
																$locationRange = aRange($humidityLocationRange,lowerBound($humidityRange,$humidityLocationRange),upperBound($humidityRange,$humidityLocationRange));
																$lowestLocation = min($lowestLocation,$locationRange[0]); echo $lowestLocation."\n";
															}
													}
											}
									}
							}
					}
			}
	
echo "\n".$lowestLocation;

function intersects($range,$range1) {
	return $range[0]<=$range1[1]+$range1[2]-1 & $range[0]>=$range1[1] || $range[0]+$range[1]-1<=$range1[1]+$range1[2]-1 & $range[0]+$range[1]-1>=$range1[1];
}

function lowerBound($range,$range1) {
	return max($range1[1],$range[0]);
}

function upperBound($range,$range1) {
	return min($range[0]+$range[1]-1,$range1[1]+$range1[2]-1);
}

function aRange($range,$lowerBound,$upperBound) {
	return [$range[0]+$lowerBound-$range[1],$upperBound-$lowerBound+1];
}

function seedLocation($seed) {
	return humidityLocation(seedHumidity($seed));
}

function seedHumidity($seed) {
	return temperatureHumidity(seedTemperature($seed));
}

function seedTemperature($seed) {
	return lightTemperature(seedLight($seed));
}

function seedLight($seed) {
	return waterLight(seedWater($seed));
}

function seedWater($seed) {
	return fertilizerWater(seedFertilizer($seed));
}

function seedFertilizer($seed) {
	return soilFertilizer(seedSoil($seed));
}

function seedSoil($seed) {
	global $seed_soil;
	foreach ($seed_soil as $map) {
		$value = map($seed,$map);
		if ($value) return $value;
	}
	return $seed;
} 

function soilFertilizer($soil) {
	global $soil_fertilizer;
	foreach ($soil_fertilizer as $map) {
		$value = map($soil,$map);
		if ($value) return $value;
	}
	return $soil;
}

function fertilizerWater($fertilizer) {
	global $fertilizer_water;
	foreach ($fertilizer_water as $map) {
		$value = map($fertilizer,$map);
		if ($value) return $value;
	}
	return $fertilizer;
}

function waterLight($water) {
	global $water_light;
	foreach ($water_light as $map) {
		$value = map($water,$map);
		if ($value) return $value;
	}
	return $water;
}

function lightTemperature($light) {
	global $light_temperature;
	foreach ($light_temperature as $map) {
		$value = map($light,$map);
		if ($value) return $value;
	}
	return $light;
}

function temperatureHumidity($temperature) {
	global $temperature_humidity;
	foreach ($temperature_humidity as $map) {
		$value = map($temperature,$map);
		if ($value) return $value;
	}
	return $temperature;
}

function humidityLocation($humidity) {
	global $humidity_location;
	foreach ($humidity_location as $map) {
		$value = map($humidity,$map);
		if ($value) return $value;
	}
	return $humidity;
}

function map($value,$map) {
	if ($value >= $map[1] & $value < $map[1]+$map[2])
			return $map[0] + $value - $map[1];
	else return FALSE;
}

?>
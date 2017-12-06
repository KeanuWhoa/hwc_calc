<?php

date_default_timezone_set("America/New_York");
ini_set('serialize_precision', -1);
$file = file_get_contents('stockJSON/NYSE/'.$_POST['symbol'].'.json');
$beGoneJSON = json_decode($file, true);
/*echo "<pre>";
print_r($beGoneJSON);
echo "</pre>";*/

$latestDate = $beGoneJSON['Meta Data']['3. Last Refreshed'];
//$52Weeks = strtotime('$latestDate -260 days');
$date = DateTime::createFromFormat('Y-m-d',$latestDate);
$date->modify('-52 weeks');
$lowValue = $beGoneJSON['Time Series (Daily)'][''.$latestDate.'']['3. low'];
$highValue = $beGoneJSON['Time Series (Daily)'][''.$latestDate.'']['2. high'];
$historicOpen = array();
$historicHigh = array();
$historicLow = array();
$historicClose = array();
foreach($beGoneJSON['Time Series (Daily)'] as $i => $value){
	$historicOpen[] = $value['1. open'];
	$historicHigh[] = $value['2. high'];
	$historicLow[] = $value['3. low'];
	$historicClose[] = $value['5. adjusted close'];
	if($i > $date->format('Y-m-d')){
		if($lowValue > $value['3. low']){
			$lowValue = $value['3. low'];
		}
		if($highValue < $value['2. high']){
			$highValue = $value['2. high'];
		}
	}
}

$historicClose = array_slice($historicClose, 0, 720);
$historicClose = array_reverse($historicClose);
/*$lengthPeriod = $lengthClose / 50;
$closeFiftyTwo = array_chunk($historicClose, 50);*/
/*echo "<pre>";
print_r($closeFiftyTwo);
echo "</pre>";die();*/
/*if($lengthPeriod % 50 != 0){
	$remove = $lengthPeriod;
	unset($closeFiftyTwo[$remove]);
}*/
/*foreach($closeFiftyTwo as $i => $value){
	$period = sizeof($value);
	$averages[] = array_sum($value) / $period;
}*/

/*function buySell($closeData){

}*/

$twoHundredDay = movingAvg($historicClose, 200);
$twoHundredLength = sizeof($twoHundredDay);
$eightTeenDay = array_reverse(movingAvg($historicClose, 18));
$eightTeenDay = array_reverse(array_slice($eightTeenDay, 0, $twoHundredLength));
$fiftyDay = array_reverse(movingAvg($historicClose, 50));
$fiftyDay = array_reverse(array_slice($fiftyDay, 0, $twoHundredLength));
$historicClose2 = array_reverse(array_slice(array_reverse($historicClose), 0, $twoHundredLength));
$historicCloseTest[] = $historicClose2;
//$testing = [255, 500, 450];

echo json_encode(${$_POST['type']}, JSON_NUMERIC_CHECK);

/* FUNCTIONS */

function movingAvg($data, $period){
	$AVG_WINDOW = $period;
	$sum = 0;
	for ($i = 0; $i < $AVG_WINDOW; $i++){
	  $sum = $sum + $data[$i];
	}

	$last_i = count ($data);
	for ($i = $AVG_WINDOW; $i < $last_i; $i++){
	  $averages[$i]=$sum / ($AVG_WINDOW);
	  $sum = $sum - $data[$i-$AVG_WINDOW] + $data[$i];
	} 
	foreach($averages as $value){
		$movingAvg[] = round($value, 2);
	}
	return $movingAvg;
}

/* ------- */

?>

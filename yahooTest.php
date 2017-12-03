<?php

date_default_timezone_set("America/New_York");
ini_set('serialize_precision', -1);
/*ini_set('max_execution_time', 0);
$url = 'https://www.alphavantage.co/query?function=';//?function=TIME_SERIES_DAILY_ADJUSTED&symbol=MSFT&outputsize=full&apikey=demo;
$type = 'TIME_SERIES_DAILY_ADJUSTED';
$file = file_get_contents('stockList/NYSE/nyse.json');
$beGoneJSON = json_decode($file, true);
foreach($beGoneJSON as $i => $value){
	$symbols[] = $value;
}

$apiKey = 'C6XVMO1YZMS1PYJ0';
$symbol = 'MSFT';
foreach($symbols as $symbol){
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url.$type."&symbol=".$symbol."&outputsize=full&apikey=".$apiKey);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-API-Key: ' . $apiKey));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

$json = curl_exec($ch);

curl_close($ch);
	
$csvTest = 'stockJSON/NYSE/'.$symbol.'.json';
$fp = fopen($csvTest, 'w');
fwrite($fp, $json);
fclose($fp);
	
}*/


/*$csvTest = $symbol.'.json';
$fp = fopen($csvTest, 'w');
fwrite($fp, $json);
$fp = fopen($csvTest, 'w');
foreach($json as $row){
	fputcsv($fp, $row);
}

fclose($fp);*/

/*foreach(glob('stockJSON/NYSE/*') as $file){
	if (is_writable($file) && filesize($file) < (1024)) {
        unlink($file);
    }
}*/

$file = file_get_contents('stockJSON/NYSE/MSFT.json');
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

$twoHundredDay = movingAvg($historicClose, 200);
$twoHundredLength = sizeof($twoHundredDay);
$eightTeenDay = array_reverse(movingAvg($historicClose, 18));
$eightTeenDay = array_reverse(array_slice($eightTeenDay, 0, $twoHundredLength));
$fiftyDay = array_reverse(movingAvg($historicClose, 50));
$fiftyDay = array_reverse(array_slice($fiftyDay, 0, $twoHundredLength));
$historicClose2 = array_reverse(array_slice(array_reverse($historicClose), 0, $twoHundredLength));

$testing = [255, 500, 450];

echo json_encode(${$_POST['type']}, JSON_NUMERIC_CHECK);

//echo "<pre>";
//print_r($eightTeenDay);
//echo "</pre>";

//so now we want to graph the 4 items, Close/18/50/200

?>
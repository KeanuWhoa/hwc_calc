<?php

date_default_timezone_set("America/New_York");
ini_set('serialize_precision', -1);
ini_set('max_execution_time', 300);
/*ini_set('max_execution_time', 0);
$url = 'https://www.alphavantage.co/query?function=';//?function=TIME_SERIES_DAILY_ADJUSTED&symbol=MSFT&outputsize=full&apikey=demo;
$type = 'TIME_SERIES_DAILY_ADJUSTED';
$file = file_get_contents('stockList/NYSE/nyse.json');
$beGoneJSON = json_decode($file, true);
foreach($beGoneJSON as $i => $value){
	$symbols[] = $value;
}

$apiKey = 'C6XVMO1YZMS1PYJ0';
//$symbol = 'GE';
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

}

die();*/

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
}
die();*/

foreach(glob('stockJSON/A/*') as $file){
	if (is_writable($file) && filesize($file) > (70000)) {
	$file = file_get_contents($file);
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

	//echo json_encode(${$_POST['type']}, JSON_NUMERIC_CHECK);

	$stockAmt = 0;
	$stockValue = 0 * $stockAmt;
	$cash = 0;
	$portStart = ($stockValue + $cash);
	$purchases = array();
	$sell = array();
	$moneyEarned = 0;
	//echo "Stock: ".$stockValue."<br />Cash: ".$cash."<br/>Portfolio: ".$portStart."<br/>";
	$bought = true;
	$count = 0;
	foreach($historicClose2 as $i => $value){
		if($bought == false){
			if($eightTeenDay[$i] > $twoHundredDay[$i] && $eightTeenDay[$i] > $fiftyDay[$i]){
				$moneyEarned = $value;
				$bought = true;
				//echo $i."  <br />Buy<br />".$eightTeenDay[$i]."<br />".$twoHundredDay[$i]."<br /><br />";
				$purchases[$count] = $value;
				$count++;
			}
		}
		if($bought == true){
			if($eightTeenDay[$i] < $twoHundredDay[$i] && $eightTeenDay[$i] < $fiftyDay[$i]){
				$moneyEarned = $value;
				$bought = false;
				//echo $i."  <br />Sell<br />".$eightTeenDay[$i]."<br />".$twoHundredDay[$i]."<br /><br />";
				if(!empty($purchases)){
					$sell[$count] = $value;	
					$count++;
				}
			}
		}
	}
/*	echo "<pre>";
	print_r($purchases);
	echo "</pre><br />";
	echo "<pre>";
	print_r($sell);
	echo "</pre><br />";die();*/
	$investment = 0;
	$test = array();
	$cashBalance = 0;
	foreach($purchases as $i => $value){
		if($sell[$i] == ""){
			$sell[$i] = $sell[$i - 1];
		}
		if($i == 0){
			$test[] = $value;
		}else if($i % 2 == 0 ){
			$test[] = $test[$i - 1] - ($value - $sell[$i]);
		}else{
			if($value > $sell[$i]){
				$cashBalance = $cashBalance - ($value - $sell[$i]);
			}
			$test[] = $test[$i - 1] + ($value - $sell[$i]);
		}
	}
	if($bought == true){
		if(empty($purchases)){
			$bought = "N/A";
			$moneyEnd = 0;
		}else{
			$moneyEnd = $historicClose2[$twoHundredLength - 1];
			$bought = "own";	
		}
	}else if ($bought == false){
		$moneyEnd = $moneyEarned;
		$bought = "sold";
	}
	if(!empty($test[0])){
		$purchValue = $test[0];
	}else{
		$purchValue = 0;
	}
	if($purchValue != 0){
		$perf = round((($moneyEnd / $purchValue) - 1) * 100, 2)."%";
	}else{
		$perf = 0;
	}
	$stratStats[] = array("performance"=>$perf, "purchaseValue"=>$purchValue, "currentValue"=>$moneyEnd, "position"=>$bought, "cashBalance"=>$cashBalance);
	}
}
/*echo "<pre>";
print_r($stratStats);
echo "</pre>";*/

$totalPerformance = 0;
$totalPurchaseValue = 0;
$totalCurrentValue = 0;
foreach($stratStats as $i => $value){
	//$totalPerformance = $totalPerformance + $value['performance'];
	$totalPurchaseValue = $totalPurchaseValue + $value['purchaseValue'];
	$totalCurrentValue = $totalCurrentValue + $value['currentValue'];
}

echo "Total Performance: ".round(((($totalCurrentValue / $totalPurchaseValue) - 1) * 100), 2)."%<br />";
echo "Total Purchase: ".round($totalPurchaseValue, 2)."<br />";
echo "Total Current Value: ".round($totalCurrentValue, 2)."<br />";

/*echo "<br />Stock: ".($moneyEnd * $stockAmt)."<br/>";
echo "Cash Loss/Gain: ".($cashBalance * $stockAmt);
echo "<br />Current Portfolio Value: ".$portValue;
echo "<br /> Strategy Performance: ".$perf;*/

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

//so now we want to graph the 4 items, Close/18/50/200

?>
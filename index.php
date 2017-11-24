<?php

date_default_timezone_set("America/New_York");
require_once($_SERVER['DOCUMENT_ROOT'].'/destiny/smarty/libs/Smarty.class.php');
$smarty = new Smarty();

include('dataCleanup.php');

$excelData = new dataCleanup('returns.csv');

$fundPeriod = array_reverse($excelData->getYears());
$fundReturns = array_reverse($excelData->getReturns());
$fundBenchmark = array_reverse($excelData->getBenchmark());

$historicalReturns = array();
foreach($fundPeriod as $i => $val){
	$historicalReturns[$i]["period"] = $val;
	$historicalReturns[$i]["returns"] = $fundReturns[$i];
}

$fundYears = array();
foreach($fundPeriod as $val){
	$fundYears[] = date("Y", $val);
}
$fundYears = array_unique($fundYears);

$smarty->assign('fundPeriod', $fundPeriod);
$smarty->assign('fundReturns', $fundReturns);
$smarty->assign('fundBenchmark', $fundBenchmark);
$smarty->assign('historicalReturns', $historicalReturns);
$smarty->assign('fundYears', $fundYears);

$pageID = trim($_SERVER['REQUEST_URI'], 'hwcalc/hwc_calc');
if($pageID == ""){
	$pageID = "home";
}
$smarty->display($pageID.'.html');

?>
<!--
/* Get data */
$file = fopen("returns.csv","r");

if ( !$file ) {
	echo "you done messed up";
}else {
	while( ($row = fgetcsv($file) ) !== FALSE) {
		$year[] = $row[0];
		$returns[] = $row[1];
		$sp[] = $row[2];
	}
}
fclose($file);	

/* Data Setup */
$length = sizeof($returns);
$length_bm = sizeof($sp);

/* setup for geomean */
foreach($returns as $i){
	$returns_adj[] = round(1 + ((str_replace('%', '', $i)) / 100), 3);
}

foreach($sp as $i){
	$sp_adj[] = round(1 + ((str_replace('%', '', $i)) / 100), 3);
}
/* end setup for geomean */

/* setup for cagr */
$returns_growth_100 = $returns;
$length_growth = sizeof($returns_growth_100) - 1;
foreach ($returns_growth_100 as $i => $val) {
	$returns_growth_100[$i] = ($returns_growth_100[$i] / 100);
}   
foreach ($returns_growth_100 as $i => $val) {
	if ($i == 0){
		$returns_growth_100[$i] = 1 * (1 + $val);
	}
	if ($i > 0) {
		$returns_growth_100[$i] = $returns_growth_100[$i - 1] * ($val + 1);
	}
}
foreach($returns_growth_100 as $i => $val){
	$returns_growth_100[$i] = round(($returns_growth_100[$i] * 100), 2);
}

$bm_growth_100 = $sp;
foreach ($bm_growth_100 as $i => $val) {
	$bm_growth_100[$i] = ($bm_growth_100[$i] / 100);
}   
foreach ($bm_growth_100 as $i => $val) {
	if ($i == 0){
		$bm_growth_100[$i] = 1 * (1 + $val);
	}
	if ($i > 0) {
		$bm_growth_100[$i] = $bm_growth_100[$i - 1] * ($val + 1);
	}
}
foreach($bm_growth_100 as $i => $val){
	$bm_growth_100[$i] = round(($bm_growth_100[$i] * 100), 2);
}
/* end setup for cagr */

/* Geomean */
$returns_product = array_product($returns_adj);
$geomean = ((pow($returns_product, (1 / $length))) - 1) * 100;
echo "<br/>Geometric Mean (Fund):".$geomean;

$sp_product = array_product($sp_adj);
$geomean_bm = ((pow($sp_product, (1 / $length_bm))) - 1) * 100;
echo "<br/>Geometric Mean (Benchmark): ".$geomean_bm;
/* End Geomean */

echo "<br/>";

/* CAGR */
$cagr_period = ($length) / 12;
$cagr = ((pow(($returns_growth_100[$length - 1] / 100), 1 / $cagr_period)) - 1) * 100;
$cagr_bm = ((pow(($bm_growth_100[$length - 1] / 100), 1 / $cagr_period)) - 1) * 100;
echo "<br/>CAGR: ".round($cagr,2)."%";
echo "<br/>CAGR (BM): ".round($cagr_bm,2)."%";
/* End CAGR */

echo "<br/>";

/* Jensen's Alpa */
$rfr = .29;
$beta = 0;
$jAlpha = ($cagr - (3.5 + $beta * ($cagr_bm - 3.5)));
$jAlphaM = ($geomean - ($rfr + $beta * ($geomean_bm - $rfr)));
echo "<br/>Jensen's Alpha: ".$jAlpha;
echo "<br/>Jensen's Alpha (Monthly): ".$jAlphaM;
/* End Jensen's Alpha */

echo "<br/>";

/* Sharp Ratio */
$standard_deviation = 15.43;
$sharp_ratio = ($cagr - 3.5) / $standard_deviation; //monthly
$sharp_ratio_ann = ((($geomean - .29) / 4.45)) * sqrt(12); //annualized
echo "<br/>Sharp Ratio: ".$sharp_ratio;
echo "<br/>Sharp Ratio (Annualized): ".$sharp_ratio_ann;
/* End Sharp Ratio */

echo "<br/>";

/* Downside Deviation */
$returns_average = array_sum($returns) / count($returns);
$p_t = $returns;
foreach($p_t as $i => $val){
	if ($val < $returns_average){
		$p_t[$i] = $val;
	}else{
		$p_t[$i] = "";
	}
}
$p_t = array_filter($p_t);
function standard_deviation($sample){
	if(is_array($sample)){
		$mean = array_sum($sample) / count($sample);
		foreach($sample as $key => $num) $devs[$key] = pow($num - $mean, 2);
		return sqrt(array_sum($devs) / (count($devs) - 1));
	}
}
$dwndv = standard_deviation($p_t);
$dwndv_ann = $dwndv * sqrt(12);
echo "<br/> Downside Deviation: ".$dwndv;
echo "<br/> Downside Deviation (Annualized): ".$dwndv_ann;
/* End Downside Deviation */

echo "<br/>";

/* Sortino Ratio */
$sortino = ($cagr - 3.5) / $dwndv_ann;
$sortino_ann = (($geomean - .29) / $dwndv) * sqrt(12);
echo "<br/>Sortino Ratio: ".$sortino;
echo "<br/>Sortino Ratio (Annualized): ".$sortino_ann;
/* End Sortino Ratio */

?>-->
<?php

date_default_timezone_set("America/New_York");
require_once('./smarty/libs/Smarty.class.php');
$smarty = new Smarty();

include('./calculator/dataCleanup.php');
include('./calculator/calculator.php');

/* Excel Data being used for the calculator */
$excelData = new dataCleanup('returns.csv');
/* ----------------- */

/* Returning the data we need */
$fundPeriod = $excelData->getYears();
$fundReturns = $excelData->getReturns();
$fundBenchmark = $excelData->getBenchmark();

$jsonDatesReturns = $excelData->getJSON(); // json from HWC
$fundReturnsTest = json_decode($jsonDatesReturns); // now we undo that
echo "<pre>";
print_r($fundReturnsTest);echo "</pre>";die();

/* ----------------- */

/* Girth measuring */
$length = sizeof($fundReturns);
$length_bm = sizeof($fundBenchmark);
/* ----------------- */

/* Calculator Things */
/* TODO: user RFR control */
$rfr = '.035'; //this is what the client would set
$rfrMn = pow((1 + $rfr),(1/12)) - 1; //one month
$fundRfr = $rfr * 100;
$fundRfrMn = $rfrMn * 100;
/* ---------------------- */
	/* Fund Returns Calc */
	$fundCalc = new calculator($fundReturns);
	$fundGeoMean = $fundCalc->getGeoMean($length);
	$fundCAGR = $fundCalc->getCAGR($length);
	$fundStdDev = $fundCalc->getStdDev();
	$fundStdDevAnn = $fundStdDev * sqrt(12);
	$fundSharpe = $fundCalc->getSharpe($fundCAGR, $fundRfr, $fundStdDevAnn);
	$fundSharpeAnn = $fundCalc->getSharpeAnn($fundGeoMean, $fundRfrMn, $fundStdDev);
	$fundDwnsideDev = $fundCalc->getDwnsideDev();
	$fundDwnsideDevAnn = $fundDwnsideDev * sqrt(12);
	$fundSortino = $fundCalc->getSortino($fundCAGR, $fundRfr, $fundDwnsideDevAnn);
	$fundSortinoAnn = $fundCalc->getSortinoAnn($fundGeoMean, $fundRfrMn, $fundDwnsideDev);
	$fundCumulativeReturn = $fundCalc->getCumulativeReturn('total', true);
	$fundCumulativeReturnOneMoving = $fundCalc->getCumulativeReturn('12', true); //moving period is in months, static is years
	$fundCumulativeReturnOne = $fundCalc->getCumulativeReturn('1', false);
	$fundCumulativeReturnTwoMoving = $fundCalc->getCumulativeReturn('24', true);
	$fundCumulativeReturnTwo = $fundCalc->getCumulativeReturn('2', false);

	/* BM Returns Calc */
	$bmCalc = new calculator($fundBenchmark);
	$bmCAGR = $bmCalc->getCAGR($length_bm);
	$bmGeoMean = $bmCalc->getGeoMean($length_bm);

	/* Fund + BM Calc */
	$fundJenAlpha = $fundCalc->getJenAlpha($fundRfr, '0', $fundCAGR, $bmCAGR);
	$fundJenAlphaMn = $fundCalc->getJenAlphaMn($fundGeoMean, $fundRfrMn, '0', $bmGeoMean);

	/* Setup for SMARTY */
	$values = array(
		$fundGeoMean, $fundCAGR, $fundJenAlpha, $fundJenAlphaMn, $fundStdDev, $fundStdDevAnn, $fundSharpe, $fundSharpeAnn, $fundDwnsideDev, $fundDwnsideDevAnn,
		$fundSortino, $fundSortinoAnn, $fundCumulativeReturn, $fundCumulativeReturnOneMoving, $fundCumulativeReturnOne,
		$fundCumulativeReturnTwoMoving, $fundCumulativeReturnTwo
	);
	$keys = array(
		'geomean', 'cagr', 'jenAlpha', 'jenAlphaMn', 'stdDev', 'stdDevAnn', 'sharpe', 'sharpeAnn', 'dwnsideDev', 'dwnsideDevAnn', 'sortino', 'sortinoAnn', 'cumulativeReturn', 'cumulativeReturnOneMoving', 'cumulativeReturnOne', 
		'cumulativeReturnTwoMoving', 'cumulativeReturnTwo'
	);
	$calc = array_combine($keys, $values);
/* ----------------- */

/* Combining the data for easy Smarty use */
$historicalReturns = array();
foreach($fundPeriod as $i => $val){
	$historicalReturns[$i]["period"] = $val;
	$historicalReturns[$i]["returns"] = $fundReturns[$i];
}
/* ----------------- */

/* Getting the set of years from the data */
$fundYears = array();
foreach($fundPeriod as $val){
	$fundYears[] = date("Y", $val);
}
$fundYears = array_unique($fundYears); // don't want dupes
/* ----------------- */

/* SMARTY VARIABLE THINGS */
$smarty->assign('fundPeriod', $fundPeriod);
$smarty->assign('fundReturns', $fundReturns);
$smarty->assign('fundBenchmark', $fundBenchmark);
$smarty->assign('historicalReturns', $historicalReturns);
$smarty->assign('fundYears', $fundYears);
$smarty->assign('calc', $calc);
/* ----------------- */

/* Setting up Smarty page system, nothing cool here */
$pageID = trim($_SERVER['REQUEST_URI'], '/hcocalc/hwc_calc/');
if($pageID == ""){
	$pageID = "home";
}
/* ----------------- */

$smarty->display('./templates/'.$pageID.'.html'); //this is why we can't have nice things

?>
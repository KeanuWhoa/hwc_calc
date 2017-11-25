<?php

class geoCalculator{
	var $returnsAdj;
	var $growthHundred;
	function __construct($data){
		foreach($data as $i){
			$returns_adj[] = round(1 + ((str_replace('%', '', $i)) / 100), 3);
		}
		$returns_growth_100 = $data;
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
		
		$this->returnsAdj = $returns_adj;
		$this->growthHundred = $returns_growth_100;
	}
	
	function getGeoMean($length){
		$returns_product = array_product($this->returnsAdj);
		$geomean = ((pow($returns_product, (1 / $length))) - 1) * 100;
		return $geomean;
	}
	
	function getCAGR($length){
		$cagr_period = ($length) / 12;
		$cagr = ((pow(($this->growthHundred[$length - 1] / 100), 1 / $cagr_period)) - 1) * 100;
		return $cagr;
	}
	
	function getJenAlpha($rfr, $beta, $fundCagr, $bmCagr){
		$jAlpha = ($fundCagr - ($rfr + $beta * ($bmCagr - $rfr))); //rfr is client choice
		return $jAlpha;
	}
	
}

?>
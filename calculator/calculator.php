<?php

class calculator{
	var $returns;
	var $returnsAdj;
	var $growthHundred;
	function __construct($data){
		foreach($data as $i){
			$returns_adj[] = 1 + ((str_replace('%', '', $i[1])) / 100);
		}
		$returns_growth_100 = $data;
		$length_growth = sizeof($returns_growth_100) - 1;
		foreach ($returns_growth_100 as $i => $val) {
			$returns_growth_100[$i] = ($returns_growth_100[$i][1] / 100);
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
			$returns_growth_100[$i] = $returns_growth_100[$i] * 100;
		}
		
		foreach($data as $i){
			$returns[] = $i[1];
		}
		
		$this->returns = $returns;
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
	
	function getJenAlphaMn($fundGeo, $rfr, $beta, $bmGeo){
		$jenAlphaMn = ($fundGeo - ($rfr + $beta * ($bmGeo - $rfr)));
		return $jenAlphaMn;
	}
	
	function getStdDev(){
		$data = $this->returnsAdj;
		$mean = array_sum($data) / count($data);
		foreach($data as $key => $num) $devs[$key] = pow($num - $mean, 2);
		$stdDev = (sqrt(array_sum($devs) / (count($devs) - 1))) * 100;
		return $stdDev;
	}
	
	function getSharpe($cagr, $rfr, $stdDev){
		//$standard_deviation = 15.43;
		$sharpe = ($cagr - $rfr) / $stdDev; //monthly
		return $sharpe;
		//$sharp_ratio_ann = ((($geomean - .29) / 4.45)) * sqrt(12); //annualized
	}
	
	function getSharpeAnn($geomean, $rfr, $stdDev){
		$sharpeAnn = ((($geomean - $rfr) / $stdDev)) * sqrt(12); //annualized
		return $sharpeAnn;
	}
	
	function getDwnsideDev(){
		$returns_average = array_sum($this->returnsAdj) / count($this->returnsAdj);
		$p_t = $this->returnsAdj;
		foreach($p_t as $i => $val){
			if ($val < $returns_average){
				$p_t[$i] = $val;
			}else{
				$p_t[$i] = "";
			}
		}
		$p_t = array_filter($p_t);
		/* TODO: Connect this with the other Std Dev method above, maybe add as parameter, run p_t formula through stdDev? */
		function standard_deviation($data){
			if(is_array($data)){
				$mean = array_sum($data) / count($data);
				foreach($data as $key => $num) $devs[$key] = pow($num - $mean, 2);
				return sqrt(array_sum($devs) / (count($devs) - 1));
			}
		}
		/* ---------------------------------------- */
		$dwnsideDev = (standard_deviation($p_t)) * 100;
		return $dwnsideDev;
	}
	
	function getSortino($cagr, $rfr, $dwnsideDevAnn){
		$sortino = ($cagr - $rfr) / $dwnsideDevAnn;
		return $sortino;
	}
	
	function getSortinoAnn($geomean, $rfr, $dwnsideDev){
		$sortino_ann = (($geomean - $rfr) / $dwnsideDev) * sqrt(12);
		return $sortino_ann;
	}
	
	function getCumulativeReturn($period, $moving){
		$growth100 = $this->growthHundred;
		$length = (sizeof($growth100)) - 1;
		if($moving == true){
			if($period == 'total'){
				$cumulativeReturn = (($growth100[$length] / 100) - 1) * 100;
			}else if($period > $length){
				$cumulativeReturn = "-";
			}else{
				$start = $length - $period;
				$cumulativeReturn = (($growth100[$length] / $growth100[$start]) - 1) * 100;
			}
		}else{
			$year = $length - 10;
			$cumulativeReturn = (($growth100[$length] / $growth100[$year]) - 1) * 100;
		}

		return $cumulativeReturn;
	}
	
	// TODO: Do we separate Variance and CoVariance into their own methods?
	function getBeta($bm){
		/* COVARIANCE SETUP */
		$length = sizeof($this->returns);
		$lengthBM = sizeof($bm);
		if($length != $lengthBM){
			return "you messed up!";
		}
			/* RETURNS SETUP */
			$fundAvg = array_sum($this->returns) / $length;
			foreach($this->returns as $i => $value){
				$returns[$i] = ($value - $fundAvg);
			}	
			/* ------------ */
			/* Benchmark SETUP */
			foreach($bm as $value){
				$beGoneJSON[] = $value[1];
			}
			$bmAvg = array_sum($beGoneJSON) / $length;
			foreach($beGoneJSON as $i => $value){
				$benchM[$i] = ($value - $bmAvg); // will be used for variance
			}
			/* ------------ */
			
			/* Combine */
			foreach($returns as $i => $value){
				$combine[] = $value * $benchM[$i];
			}
			$combineSum = array_sum($combine);
			$covariance = $combineSum / ($length - 1);
			/* ------- */
		/* --------------- */
		/* VARIANCE SETUP */
		foreach($benchM as $i => $value){
			$squared[] = pow($value, 2);
		}
		$squaredSum = array_sum($squared);
		$variance = $squaredSum / ($length - 1);
		/* -------------- */
		
	/* BETA */
		$beta = $covariance / $variance;
	/* ---- */
		return $beta;
	}
	
	function getTraynorRatio($cagr, $rfr, $beta){
		$traynorRatio = (($cagr - $rfr) / 100) / $beta;
		return $traynorRatio;
	}
	
}

?>
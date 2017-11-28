<?php

// TODO: label each column in excel and use the labels to loop through and name accordingly
// ^ will give flexibility to clients

class dataCleanup{
	var $excelOutput;
	function __construct($data){
		$file = fopen($data,"r");
		if ( !$file ) {
			echo "you done messed up a a ron";
		}else {
			while( ($row = fgetcsv($file) ) !== FALSE) {
				$year[] = $row[0];
				$returns[] = $row[1];
				$sp[] = $row[2];
			}
		}
		fclose($file);
		$excelOutput = array($year, $returns, $sp);
		$this->excelOutput = array($year, $returns, $sp);
	}
	
	function getYears(){
		foreach($this->excelOutput[0] as $yearNoFormat){
			$years[] = strtotime($yearNoFormat);
		}
		return $years;
	}
	
	function getReturns(){
		$returns = $this->excelOutput[1];
		return $returns;
	}
	
	function getBenchmark(){
		$benchmark = $this->excelOutput[2];
		return $benchmark;
	}
	
	function getJSON(){
		foreach($this->excelOutput[0] as $yearNoFormat){
			$years[] = date("Y-m", strtotime($yearNoFormat));
		}
		$return = $this->excelOutput[1];
		
		$jsonReady = array();
		foreach($years as $i => $val){
			$jsonReady[$i][0] = $val;
			$jsonReady[$i][1] = $return[$i];
		}
		return json_encode($jsonReady);
	}
	
}

?>
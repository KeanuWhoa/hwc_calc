<?php

// TODO: label each column in excel and use the labels to loop through and name accordingly
// ^ will give flexibility to clients

class dataCleanup{
	var $excelOutput;
	function __construct($data){
		$file = fopen($data,"r");
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
		$excelOutput = array($year, $returns, $sp);
		$this->excelOutput = array($year, $returns, $sp);
	}
	
	function getYears(){
		foreach($this->excelOutput[0] as $yearNoFormat){
			//for this data, need to remove first value for some reason
			$years[] = strtotime($yearNoFormat);
		}
		$weird = array_shift($years);
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
}

?>
<?php
require_once('utils.php');
require_once('news-utils.php');

class AnnualreportsModel {
	function AnnualreportsModel(){
	}
	
	function get($labels = NULL) {
   	   	$data = Array();
   	   	
   	   	$reports = getPostsByType('annual_report', array(
   	   		'meta-box-date',
   	   		'meta-box-url'
   	   	));
   	   	
   	   	usort($reports, array('AnnualreportsModel', 'cmpReports'));
   	   	
   	   	$data['reports'] = $reports;

		return $data;
	}
	
	function cmpReports($a, $b) {
		$a_date = $a["meta-box-date"];
		$b_date = $b["meta-box-date"];

		if ($a_date == $b_date)
			return 0;
		
		return ($a_date < $b_date) ? 1 : -1;
	}
}
?>

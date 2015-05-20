<?php
require_once('utils.php');

class OurworkModel {
	function OurworkModel(){
	}
	
	function get($labels = NULL) {
   	   	$data = Array();
	   	
   	   	$data["our-work"] = getGoalData();

		return $data;
	}
	
	
}
?>

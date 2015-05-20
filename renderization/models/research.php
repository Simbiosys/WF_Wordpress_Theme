<?php
require_once('news-utils.php');

class ResearchModel {
	function ResearchModel(){
	}
	
	function get($labels = NULL) {
		$util = new NewsUtil();
		
   	   	return $util->get('research', $labels);
	}
}
?>

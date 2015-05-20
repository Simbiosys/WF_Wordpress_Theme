<?php
require_once('news-utils.php');

class ResearchdetailModel {
		function ResearchdetailModel(){
	}
	
	function get($labels = NULL) {
   	   	$util = new NewsUtil();
	
		return array (
		 "post" => $util->getPost()
		);
	}
}
?>

<?php
require_once('utils.php');
require_once('news-utils.php');

class ThewebtemplateModel {
	function ThewebtemplateModel(){
	}
	
	function get($labels = NULL) {
   	   	$data = Array();
   	   	
   	   	$util = new NewsUtil();
   	   	
		$data["web"] = array(
			"content" => $util->getPost()
		);

		return $data;
	}
}
?>

<?php
require_once('utils.php');
require_once('news-utils.php');

class TemplateModel {
	function TemplateModel(){
	}
	
	function get($labels = NULL) {
   	   	$data = Array();
   	   	
   	   	$util = new NewsUtil();
   	   	
		$data["content"] = $util->getPost();

		return $data;
	}
}
?>

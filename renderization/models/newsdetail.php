<?php
require_once('news-utils.php');

class NewsdetailModel {
		function NewsdetailModel(){
	}
	
	function get($labels = NULL) {
		$util = new NewsUtil();
	
		return array (
		 "post" => $util->getPost()
		);
	}
}
?>

<?php
require_once('news-utils.php');

class NewsModel {
	function NewsModel(){
	}
	
	function get($labels = NULL) {
		$util = new NewsUtil();
		
   	   	return $util->get('news', $labels, 'general');
	}
}
?>

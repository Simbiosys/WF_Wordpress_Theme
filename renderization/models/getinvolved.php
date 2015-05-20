<?php
require_once('utils.php');
require_once('news-utils.php');

class GetinvolvedModel {
	function GetinvolvedModel(){
	}
	
	function get($labels = NULL) {
   	   	$data = Array();
   	   	
   	   	$util = new NewsUtil();
   	   	
		$data["support-us"] = array(
			"content" => $util->getPost(),
			"credits" => get_post_meta($post->ID, 'meta-box-credits', true)
		);

		return $data;
	}
}
?>

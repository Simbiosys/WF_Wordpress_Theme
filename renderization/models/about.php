<?php
require_once('utils.php');
require_once('news-utils.php');

class AboutModel {
	function AboutModel(){
	}
	
	function get($labels = NULL) {
   	   	$data = Array();
   	   	
   	   	$util = new NewsUtil();
   	   	
		$data["about"] = array(
			"content" => $util->getPost(),
			"post" => $this->getLastPost()
		);

		return $data;
	}
	
	function getLastPost() {
        $featured = getPostsByCategory(NULL);
        
        return count($featured) > 0 ? $featured[0] : array();
	}
}
?>

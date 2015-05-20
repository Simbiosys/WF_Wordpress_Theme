<?php
require_once('utils.php');

class MediaModel {
	function MediaModel(){
	}
	
	function get($labels = NULL) {
   	   	$data = Array();
   	   	
		$data["media"] = array(
			"content" => $this->getContent()
		);
		
		return $data;
	}
	
	function getContent() {
		return getStaticPage('media');
	}
}
?>

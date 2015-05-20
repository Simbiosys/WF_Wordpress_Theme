<?php
require_once('utils.php');

class ContactModel {
	function ContactModel(){
	}
	
	function get($labels = NULL) {
   	   	$data = Array();
   	   	
		$data["contact"] = array(
			"content" => $this->getContent()
		);
		
		return $data;
	}
	
	function getContent() {
		return getStaticPage('contact');
	}
}
?>

 <?php
require_once('utils.php');
require_once('news-utils.php');

class ContactustemplateModel {
	function ContactustemplateModel(){
	}
	
	function get($labels = NULL) {
   	   	$data = Array();
   	   	
   	   	$util = new NewsUtil();
   	   	
		$data["contact"] = array(
			"content" => $util->getPost()
		);

		return $data;
	}
}
?>

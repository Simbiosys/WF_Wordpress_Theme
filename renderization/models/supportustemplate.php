 <?php
require_once('utils.php');
require_once('news-utils.php');

class SupportustemplateModel {
	function SupportustemplateModel(){
	}
	
	function get($labels = NULL) {
   	   	$data = Array();
   	   	
   	   	$util = new NewsUtil();
   	   	
		$data["support"] = array(
			"content" => $util->getPost()
		);

		return $data;
	}
}
?>

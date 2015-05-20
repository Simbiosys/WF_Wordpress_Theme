 <?php
require_once('utils.php');
require_once('news-utils.php');

class AboutustemplateModel {
	function AboutustemplateModel(){
	}
	
	function get($labels = NULL) {
   	   	$data = Array();
   	   	
   	   	$util = new NewsUtil();
   	   	
		$data["about"] = array(
			"content" => $util->getPost(),
			"credits" => get_post_meta($post->ID, 'meta-box-credits', true)
		);

		return $data;
	}
}
?>

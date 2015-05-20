<?php
require_once('utils.php');

class BoardmemberdetailModel {
	function BoardmemberdetailModel(){
	}
	
	function get($labels = NULL) {
   	   	$data = Array();
	   	
   	   	$data["person"] = $this->getPersonInfo();
   	   	
		return $data;
	}
	
	function getPersonInfo() {
		$info = array();
		
        $post = array();
		
        while (have_posts()) { 
        	the_post();
			
			$id = get_the_ID();
			
			$content = apply_filters('the_content', get_the_content());
			$info["description"] = $content;
			$info["image"] = wp_get_attachment_url(get_post_thumbnail_id($id));
			$info["name"] = html_entity_decode(get_the_title(), ENT_COMPAT, 'UTF-8');
			$info["position"] = get_post_meta($id, 'meta-box-position', true);
			$info["email"] = get_post_meta($id, 'meta-box-email', true);
			
			$info["linkedin"] = get_post_meta($id, 'meta-box-linkedin', true);
			$info["twitter"] = get_post_meta($id, 'meta-box-twitter', true);
			$info["google"] = get_post_meta($id, 'meta-box-google', true);
			$info["facebook"] = get_post_meta($id, 'meta-box-facebook', true);
		}
		
		return $info;
	}
}
?>

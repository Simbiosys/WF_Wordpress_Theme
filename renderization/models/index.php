<?php
require_once('utils.php');

class IndexModel {
	function IndexModel(){
	}
	
	function get($labels = NULL) {
		$post = getWholeStaticPage('home');
		$highlighted = get_post_meta($post->ID, "meta-box-highlighted", true);

		$twitter = new Twitter();

   	   	$data = Array();
   	   	
   	   	$data["index-content"] = array(
   	   		"image" => wp_get_attachment_url(get_post_thumbnail_id($post->ID)),
   	   		"content" => apply_filters('the_content', $post->post_content),
   	   		"title" => apply_filters('the_content', $highlighted),
   	   		"credits" => get_post_meta($post->ID, 'meta-box-credits', true)
   	   	);
		//$data["news"] = getPostsByCategoryExcludingCategories('general', array('featured'));
	    $data["tweets"] = $twitter->loadDefaultAccountTweets();
		$data["featured"] = $this->getFeaturedPost();
		$data["news_and_blogs"] = $this->getNewsAndBlogs();
		$data["research"] = $this->getResearch();
		
		$data["register_url"] = site_url('wp-login.php?action=register', 'login_post');
	
		return $data;
	}
	
	function getFeaturedPost() {
        $featured = getPostsByCategory('Featured');
        
        return count($featured) > 0 ? $featured[0] : array();
	}
	
	function getNewsAndBlogs() {
		return getPostsByCategoryExcludingCategories('general', array('featured'));
	}
	
	function getResearch() {
		return getPostsByType('research');
	}
}
?>

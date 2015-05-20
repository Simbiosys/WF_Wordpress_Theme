<?php
require_once('utils.php');

class JobsModel {
	function JobsModel(){
	}
	
	function get($page_name = "news", $labels = NULL) {
		$post = array();
		
        while (have_posts()) { 
        	the_post();
			
			$content = apply_filters('the_content', get_the_content());
	
			$post["title"] = html_entity_decode(get_the_title(), ENT_COMPAT, 'UTF-8');
			$post["date"] = get_the_date();
			$post["url"] = get_permalink();
			
			$post["author"] = array(
				"name" => get_the_author(),
				"url" => get_author_posts_url(get_the_author_meta('ID'))
			);
			
			$processed_post = str_get_html($content);
			
			$images = $processed_post->find('img');
			
			foreach ($images as $image) {
				$src = $image->src;
				
				if ($src && $src[0] == "/") {
					$src = get_home_url() . $src;
					
					$image->src = $src;
				}
			}
			
			$post["content"] = (string) $processed_post->outertext;
		}

		return array(
			"jobs" => $post
		);
	}
}
?>

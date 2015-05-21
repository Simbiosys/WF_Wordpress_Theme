<?php
require_once('utils.php');

class FundraisingteamModel {
	function FundraisingteamModel(){
	}
	
	function get($labels = NULL) {
   	   	$data = Array();
	   	
   	   	$data["fundraising"] = $this->getInfo();

		return $data;
	}
	
	function getInfo() {
		$info = array();
		
        $post = array();
		
        while (have_posts()) { 
        	the_post();
			
			$id = get_the_ID();
			
			$content = apply_filters('the_content', get_the_content());
			$info["description"] = get_post_meta($id, 'meta-box-caption', true);
			$info["content"] = $content;
			$info["image"] = wp_get_attachment_url(get_post_thumbnail_id($id));
			$info["title"] = html_entity_decode(get_the_title(), ENT_COMPAT, 'UTF-8');
			
			$info["credits"] = get_post_meta($id, 'meta-box-credits', true);
			
			$info["highlight"] = get_post_meta($id, 'meta-box-highlighted', true);
			
			// People
			
			$people = getPostsByTypeAndMeta('team_member', "meta-box-group", "fundraising", array(
				'meta-box-position',
				'meta-box-email',
				"meta-box-project-star-$id"
			));

			$people_list = array();
			
			foreach ($people as $person) {
				array_push($people_list, array(
					"name" => $person['title'],
					"position" => $person['meta-box-position'],
					"email" => $person['meta-box-email'],
					"description" => $person['short_content'],
					"image" => $person['image'],
					"star" => $person["meta-box-project-star-$id"],
					"lastname" => get_last_word($person['title']),
					"url" => $person['url'],
				));
			}
			
			usort($people_list, array("FundraisingteamModel", "member_sort"));
			
			$info["people"] = $people_list;
			$info["people_length"] = count($people_list);
		}
	
		return $info;
	}
	
	function member_sort($a, $b) {	
		$a_star = $a["star"];
		$b_star = $b["star"];
		
		$a_star = $a_star == "true" ? 1 : 0;
		$b_star = $b_star == "true" ? 1 : 0;
	
		$a_lastname = $a["lastname"];
		$b_lastname = $b["lastname"];
	
    	if ($a_star == $b_star) {
    		if ($a_lastname == $b_lastname)
        		return 0;
        		
        	return ($a_lastname < $b_lastname) ? -1 : 1;
    	}
    	
    	return ($a_star < $b_star) ? 1 : -1;
	}
}
?>

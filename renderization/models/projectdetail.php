<?php
require_once('utils.php');

class ProjectdetailModel {
	function ProjectdetailModel(){
	}
	
	function get($labels = NULL) {
   	   	$data = Array();
	   	
   	   	$data["project"] = $this->getInfo();

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
			$info["name"] = html_entity_decode(get_the_title(), ENT_COMPAT, 'UTF-8');
			
			$info["credits"] = get_post_meta($id, 'meta-box-credits', true);
			
			$goal_identifier = get_post_meta($id, 'meta-box-goal', true);
			
			$goal = getPostsByTypeAndMeta('goal', 'meta-box-identifier', $goal_identifier);
			
			if ($goal && count($goal) > 0)
				$goal = $goal[0];
				
			$info["goal"] = array(
				"name" => $goal["title"],
				"identifier" => $goal_identifier
			);
			
			$info["highlight"] = get_post_meta($id, 'meta-box-highlighted', true);
			
			// People
			
			$people = getPostsByTypeAndMeta('team_member', "meta-box-project-$id", "true", array(
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
			
			usort($people_list, array("ProjectdetailModel", "member_sort"));
			
			$info["people"] = $people_list;
			$info["people_length"] = count($people_list);
			
			$url = get_post_meta($id, 'meta-box-url', true);
		
			$start_date = str_replace('-', '.', get_post_meta($id, 'meta-box-start', true));
			$end_date = str_replace('-', '.', get_post_meta($id, 'meta-box-end', true));
			
			$calendar = !empty($start_date) && !empty($end_date);
			$further = $calendar;
		
			$info["start_date"] = $start_date;
			$info["end_date"] = $end_date;
			$info["calendar"] = $calendar;
			
			$info["url"] = $url;
			$info["further"] = $further;
			
			$info["only_url"] = !$calendar;
			
			// News
			
			$all_news = getPostsByTypeAndMeta('post', "meta-box-project-star-$id", "true", array(

			));

			$news = $all_news;
			$more_news = null;
			
			if (count($all_news) > 2) {
				$news = array_slice($all_news, 0, 2);
				$more_news = array_slice($all_news, 2);
			}
			
			$info["news"] = $news;
			$info["more_news"] = $more_news;
			
			// Research
			
			$research = getPostsByTypeAndMeta('research', "meta-box-project-$id", "true", array(
			));
			
			$research = array_slice($research, 0, 2);
			
			$info["research"] = $research;
			$info["has_research"] = count($research) > 0;
			
			// Funders
			
			$funders = getPostsByType('funder', array('meta-box-identifier'));
			
			$funder_list = array();
			$secondary_funder_list = array();
			
			foreach ($funders as $funder) {
				$f_id = $funder["id"]; 
				$f_title = $funder["title"];
		
				$value = get_post_meta($id, "meta-box-primary-funder-$f_id", true);

				if ($value == "true") {
					$f = get_post($f_id);
					
					$f_image = wp_get_attachment_url(get_post_thumbnail_id($f_id));
					$f_name = html_entity_decode(get_the_title($f_id), ENT_COMPAT, 'UTF-8');
					$f_url = get_post_meta($f_id, "meta-box-url", true);
					
					array_push($funder_list, array(
						"image" => $f_image,
						"name" => $f_name,
						"url" => $f_url
					));
				}
				
				// Secondary
				
				$value = get_post_meta($id, "meta-box-secondary-funder-$f_id", true);
				
				if ($value == "true") {
					$f = get_post($f_id);
					
					$f_image = wp_get_attachment_url(get_post_thumbnail_id($f_id));
					$f_name = html_entity_decode(get_the_title($f_id), ENT_COMPAT, 'UTF-8');
					$f_url = get_post_meta($f_id, "meta-box-url", true);
					
					array_push($secondary_funder_list, array(
						"image" => $f_image,
						"name" => $f_name,
						"url" => $f_url
					));
				}
			}
			
			$info["funders"] = $funder_list;
			$info["secondary_funders"] = $secondary_funder_list;
			
			// Goals
			
			$info["goals"] = getGoalData();
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

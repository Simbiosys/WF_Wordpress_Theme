<?php
require_once('utils.php');

class NewsUtil {
	function NewsUtil(){
	}
	
	function get($page_name = "news", $labels = NULL, $category = NULL, $show_filter = TRUE) {
   	   	$data = Array();
   	
   	   	$page  = get_query_var('paged', 1);
   	   	$project_id = get_query_var('pr', NULL);
   	   	$starts = get_query_var('starts', NULL);
   	   	$ends = get_query_var('ends', NULL);
   	   	
   	   	$search = get_query_var('s', NULL);
   	   	
   	   	if (empty($search)) {
			$args = NULL;
		
			$starts_initial = $starts;
			$ends_initial = $ends;
		
			$post_type = $page_name == 'news' ? 'post' : $page_name;
		
			if (!empty($starts) && !empty($ends)) {
				$starts = strtotime($starts);
				$ends = strtotime($ends);
			
				$args = array (
					'date_query' => array(
						array(
							'after'     => array(
								'year'  => date('Y', $starts),
								'month' => date('n', $starts),
								'day'   => date('j', $starts),
							),
							'before'    => array(
								'year'  => date('Y', $ends),
								'month' => date('n', $ends),
								'day'   => date('j', $ends),
							),
						'inclusive' => true,
						),
					)
				);
			
				$starts = date('F', $starts) . ", " . date('Y', $starts);
				$ends = date('F', $ends) . ", " . date('Y', $ends);
			}
		
			if ($category) {
				$category_id = getCategoryIdByName($category);
				$args['category'] = $category_id;
			}
			
			if (empty($project_id)) {
				$news = getPostsByType($post_type, NULL, -1, $args);
			}
			else {
				if (is_int($project_id))
					$news = getPostsByTypeAndMeta($post_type, "meta-box-project-$project_id", "true", NULL, $args);
				else {
					$news = array();
					
					$projects = getPostsByTypeAndMeta('project', "meta-box-goal", $project_id);
					
					foreach ($projects as $project) {
						$id = $project["id"];
						
						$project_news = getPostsByTypeAndMeta($post_type, "meta-box-project-$id", "true", NULL, $args);
						$news = array_merge($news, $project_news);
					}
				}
			}
   	   	}
   	   	else {
   	   		$ids = array();
   	   		$count = 0;
   	   		
   	   		if (have_posts()) {
   				while ( have_posts() ) {
   					if ($count == 100)
   						break;
   						
   					$post = get_the_ID();

   					array_push($ids, $post);	
   					
   					$count++;
   				}	
   			}
   			
   			$args = array('post__in' => $ids);
   			$news = getPostsByArgs($args);
   	   	}

  		if ($page_name == 'research' && $page < 1) {
  			usort($news, array('NewsUtil', 'cmpNews'));
  		}
  		 	   	
   	   	$highlighted_news = 4;
   	   	$rest_news = 9;
   	   	
   	   	$count = count($news);
   	   	$empty_filters = empty($starts) && empty($ends) && empty($project_id);

   	   	if ($page > 1) {
   	   		$highlighted = NULL;
   	   		$inc = $empty_filters ? $highlighted_news : 0;
   	   		$start = $inc + $rest_news * ($page - 1);
   	   		$rest = array_slice($news, $start, $rest_news);
   	   		$previous = $page - 1;
   	   		$next = $page + 1;
   	   	}
   	   	else {
   	   		if ($empty_filters && $count >= $highlighted_news) {
   	   			$highlighted = array_slice($news, 0, $highlighted_news);
   	   			$start = $highlighted_news;
   	   		}
   	   		else {
   	   			$highlighted = NULL;
   	   			$start = 0;
   	   		}
   	   		
   	   		$rest = array_slice($news, $start, $rest_news);
   	   		$previous = NULL;
   	   		$next = 2;
   	   	}	
 
		$posts_in_next_page = count($news) - ($start + $rest_news); 
		
		if ($posts_in_next_page <= 0)
			$next = NULL;
   	
   	   	if (!empty($next))
   	   		$next = array(
   	   			"value" => $next,
   	   			"host" => get_site_url(),
   	   			"starts" => $starts_initial,
				"ends" => $ends_initial,
				"page" => $page_name
   	   		);
   	   		
   	   	if (!empty($previous))
   	   		$previous = array(
   	   			"value" => $previous,
   	   			"host" => get_site_url(),
   	   			"starts" => $starts_initial,
				"ends" => $ends_initial,
				"page" => $page_name
   	   		);
   	   		
   	   	// Projects
   	   	
   	   	$goals = get_projects();
   	   	
   	   	for ($i = 0; $i < count($goals); $i++) {
   	   		$goal = $goals[$i];
   	  		$goal_id = $goal["id"];
   	  			
   	  		if ($goal_id == $project_id)
   	   			$goals[$i]["selected"] = "selected";
   	  		
   	   		$projects = $goal["projects"];
   	   		
   	   		for ($j = 0; $j < count($projects); $j++) {
   	   			$project = $projects[$j];
   	   			$id = $project["id"];
   	   			
   	   			if ($id == $project_id)
   	   				$goals[$i]["projects"][$j]["selected"] = "selected";
   	   		}
   	   	}
 	
		$data["news-index"] = array(
			"page" => $page_name,
			"title" => $labels[$page_name . "_title"],
			"highlighted" => $highlighted,
			"rest" => $rest,
			"next" => $next,
			"previous" => $previous,
			"projects" => $goals,
			"starts" => $starts,
			"ends" => $ends,
			"filter" => $show_filter
		);
		return $data;
	}
	
	function getFeaturedPost() {
        $featured = getPostsByCategory('Featured');
        
        return count($featured) > 0 ? $featured[0] : array();
	}
	
	function getNewsAndBlogs() {
		return getPostsExcludingCategories(array('Featured'));
	}
	
	function getResearch() {
		return getPostsByType('research');
	}
	
	function getPostByName($name) {
		global $wpdb;
		
		$post = array();
		
		$id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = '$name'");
		
		$this->processPostContent($post, $id);
		
		return $post;
	}
	
	function getPost() {
		$post = array();
		
        while (have_posts()) { 
        	the_post();
			
			$id = get_the_ID();
			
			$this->processPostContent($post, $id);
			
			$prev_post = $this->getPreviousPost();
			
			if ($prev_post)
				$post["previous"] = $prev_post;
				
			$next_post = $this->getNextPost();
			
			if ($next_post)
				$post["next"] = $next_post;
		}
		
		return $post;
	}
	
	function processPostContent(&$post, $id) {
		$content_post = get_post($id);
		$content = apply_filters('the_content', $content_post->post_content);

		$post["title"] = html_entity_decode(get_the_title($id), ENT_COMPAT, 'UTF-8');
		$post["date"] = get_the_date('F j, Y', $id);
		$post["url"] = get_permalink($id);
		
		$post["author"] = array(
			"name" => get_the_author($id),
			"url" => get_author_posts_url(get_the_author_meta($id))
		);
		
		$post["image"] = wp_get_attachment_url(get_post_thumbnail_id($id));
		$post["highlighted"] = get_post_meta($id, 'meta-box-highlighted', true);
		$post["credits"] = get_post_meta($id, 'meta-box-credits', true);
		$post["short_content"] = $this->get_the_excerpt($id); 
		
		$processed_post = str_get_html($content);
		
		if (!empty($processed_post)) {
			$images = $processed_post->find('img');
		
			foreach ($images as $image) {
				$src = $image->src;
			
				if ($src && $src[0] == "/") {
					$src = get_home_url() . $src;
				
					$image->src = $src;
				}
			}
		}
		
		$post["content"] = (string) $processed_post->outertext;
		
		// Projects
   	   	
   	   	$goals = get_projects();
   	   	$tags = array();
   	   	
   	   	for ($i = 0; $i < count($goals); $i++) {
   	   		$goal = $goals[$i];
   	  		$goal_id = $goal["id"];
   	  		
   	   		$projects = $goal["projects"];
   	   		
   	   		foreach ($projects as $project) {
   	   			$project_id = $project["id"];
   	   			$project_title = $project["title"];
   	   			$project_url = get_permalink($project_id);
   	  	
   	   			$linked_to_project = get_post_meta($id, "meta-box-project-$project_id", true);
   	   			
   	   			$goal = get_post_meta($project_id, "meta-box-goal", true);
   	   		
   	   			if ($linked_to_project == 'true')
   	   				array_push($tags, array(
   	   					"id" => $project_id,
   	   					"title" => $project_title,
   	   					"url" => $project_url,
   	   					"goal" => $goal
   	   				));
   	   		}
   	   	}
   	   	
   	   	$post["tags"] = $tags;
	}
	
	function get_the_excerpt($post_id) {
  		global $post;  
  		$save_post = $post;
  		$post = get_post($post_id);
  		$output = apply_filters('the_content', get_the_excerpt());
  		$post = $save_post;

  		return $output;
	}
	
	function getPreviousPost() {
		$prev_post = get_previous_post();
		return $this->getPostFields($prev_post);
	}
	
	function getNextPost() {
		$prev_post = get_next_post();
		return $this->getPostFields($prev_post);
	}
	
	function getPostFields($post) {
		$obj = NULL;
		
		if (!empty($post)) {
			$obj = array();
				
			$obj["title"] = html_entity_decode($post->post_title, ENT_COMPAT, 'UTF-8');
			$obj["date"] = $date = date("M j, Y", strtotime($post->post_date));
			$obj["author"] = $post->post_author_name;
			$obj["url"] = get_permalink($post->ID);
			
			return $obj;
		}
		
		return $obj;
	}
	
	function cmpNews($a, $b) {
		$a_date = $a["date"];
		$b_date = $b["date"];

		$a_tag = in_array("Featured", $a["tags"]) ? 1 : 0;
		$b_tag = in_array("Featured", $b["tags"]) ? 1 : 0;

		if ($a_tag == $b_tag) {
			if ($a_date == $b_date)
				return 0;
		
			return ($a_date < $b_date) ? 1 : -1;
		}

		return ($a_tag < $b_tag) ? 1 : -1;
	}
}
?>

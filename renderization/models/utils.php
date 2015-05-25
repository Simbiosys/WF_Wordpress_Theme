<?php

function getPostsByCategory($category, $posts_per_page = 5) {
	$category_id = getCategoryIdByName($category);
	
	$args = array('posts_per_page' => $posts_per_page);
	
	if ($category)
		$args['category'] = $category_id;
	
	return getPostsByArgs($args);
}

function getPostsExcludingCategories($categories) {
	$args = array('category' => getCategoryIds($categories));

	return getPostsByArgs($args);
}

function getCategoryIds($categories) {
	$category_ids = array();
	
	foreach ($categories as $category) {
		$category_id = getCategoryIdByName($category);
		array_push($category_ids, "-$category_id");
	}
	
	return implode(",", $category_ids);
}

function getPostsByCategoryExcludingCategories($category, $categories,  $posts_per_page = 5) {
	$args = array('category' => getCategoryIds($categories), 'posts_per_page' => $posts_per_page);
	
	$category_id = getCategoryIdByName($category);
	$args['category'] .= "," . $category_id;

	return getPostsByArgs($args);
}

function getCategoryIdByName($name) {
	return get_category_by_slug($name)->term_id;
}

function getPostsByArgs($args, $meta = NULL) {
	$myposts = get_posts($args);
	
	return loopPosts($myposts, $meta);
}

function loopPosts($myposts, $meta = NULL) {
	//global $post;
	
	$posts = Array();
	
	foreach ($myposts as $post) {
		$news = getPostInfo($post, $meta);

		array_push($posts, $news);
	} 
	
	return $posts;
}

function getExcerpt($str, $permalink, $startPos = 0, $maxLength = 100) {
	if (strlen($str) > $maxLength) {
		$excerpt = substr($str, $startPos, $maxLength-3);
		$lastSpace = strrpos($excerpt, ' ');
		$excerpt = substr($excerpt, 0, $lastSpace);
		
		$excerpt .= "<a class='more-link text-right m-left' href='$permalink'>Read more</a>";
	} else {
		$excerpt = $str;
	}
	
	return $excerpt;
}

function getPostInfo($post, $meta = NULL) {
	$news = Array();

	$post_id = $post->ID;
	$url = get_permalink($post);
	
	$content = apply_filters('the_content', get_post_field('post_content', $post));
	$processed_post = str_get_html($content);
	
	$excerpt = get_post_field('post_excerpt', $post);
	
	if (empty($excerpt)) {
		$excerpt = $processed_post->plaintext;
		
		$excerpt = getExcerpt($excerpt, $url, 0, 200);
	}
	
	$news['id'] = $post_id;
	$news['title'] = html_entity_decode(get_the_title($post), ENT_COMPAT, 'UTF-8');
	$news['date'] = get_the_date('F j, Y', $post);
	$news['url'] = $url;
	$news['content'] = $content;
	$news['short_content'] = $excerpt;
	
	$image = wp_get_attachment_url(get_post_thumbnail_id($post_id));

	//if (!$image && $processed_post)
	//	$image = $processed_post->find('img', 0)->src;
		
	if ($image && $image[0] == "/")
		$image = get_home_url() . $image;

	$news["image"] = $image;
	
	$author = get_post_field('post_author', $post);
	
	$news["author"] = array(
		"name" => get_the_author_meta('display_name', $author),
		"url" => get_author_posts_url($author)
	);
	
	if ($processed_post)
		$h1 = $processed_post->find('h1', 0);
	
	if ($h1)
		$news["subtitle"] = $h1->innertext;
		
	$categories = get_the_category();
	$news["categories"] = array();
	
	foreach ($categories as $category) {
		$cat = array(
			"url" => get_category_link($category->term_id),
			"name" => $category->name
		);
		
		array_push($news["categories"], $cat);
	}
	
	// Tags
	
	$tags = wp_get_post_terms($post_id, 'tag', array("fields" => "all"));
	$news["tags"] = array();
	
	foreach ($tags as $tag) {
		array_push($news["tags"], $tag->name);
	}
		
	// Meta
	
	if ($meta) {
		foreach ($meta as $field) {
			$news[$field] = get_post_meta($post->ID, $field, true);
		}
	}
		
	return $news;
}

function getWholeStaticPage($name) {
	global $wpdb;
	
	// Obtain the post id through its name
	$id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = '$name'");
		
	// Obtain the post through its id
	$post = get_post($id); 
		
	return $post;
}

function getStaticPage($name) {
	$post = getWholeStaticPage($name); 
		
	// Filter the post content
	return apply_filters('the_content', $post->post_content);
}

function getPageIdByName($name) {
	global $wpdb;
	return $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = '$name'");
}

function getPageChildrenById($id) {
	// Set up the objects needed
	$my_wp_query = new WP_Query();
	$all_wp_pages = $my_wp_query->query(array('post_type' => 'page', 
		'posts_per_page' => -1,
		'post_status' => array('publish')
		));

	// Filter through all pages and find Executive-Team's children
	return get_page_children($id, $all_wp_pages);
}

function getPageChildrenByName($name) {
	// Get the page as an Object
	$page_id = getPageIdByName($name);
	
	return getPageChildrenById($page_id);
}

function getPostsByType($type, $meta = NULL, $posts_per_page = -1, $args = NULL) {
	if (empty($args)) {
		$args = array();
	}
	
	$args['post_type'] = $type;
	$args['posts_per_page'] = $posts_per_page;

	return getPostsByArgs($args, $meta);
}

function getPostsByTypeAndMeta($type, $key, $value, $meta = NULL, $args = NULL) {
	if (empty($args)) {
		$args = array();
	}
	
	$args['post_type'] = $type;
	$args['meta_key'] = $key;
	$args['meta_value'] = $value;
	$args['posts_per_page'] = -1;
 
	return getPostsByArgs($args, $meta);
}

//////////////////////////////////////////////////////////////////////////////////////////

function getGoalData() {
	$data = array();

	$goals = getPostsByType('goal', array(
		'meta-box-identifier', 
		'meta-box-order',
		'meta-box-highlighted',
		'meta-box-credits'
	));

	$data = array();

	foreach ($goals as $goal) {
		$goal_title = $goal['title'];
		$goal_content = $goal['content'];
		$goal_order = $goal['meta-box-order'];
		$goal_identifier = $goal['meta-box-identifier'];
		$goal_highlighted = $goal['meta-box-highlighted'];
		$goal_credits = $goal['meta-box-credits'];
		$goal_image = $goal['image'];

		// Projects

		$projects = array();

		$projects_data = getPostsByTypeAndMeta("project", "meta-box-goal", $goal_identifier);
		$projects_ids = array();
	
		foreach ($projects_data as $project) {
			$p_title = $project["title"];
			$p_subtitle = $project["content"];
			$p_url = $project["url"];
			$p_id = $project["id"];
	
			array_push($projects, array(
				"title" => $p_title,
				"subtitle" => $p_subtitle,
				"url" => $p_url
			));
		
			if (!in_array($p_id, $projects_ids))
				array_push($projects_ids, $p_id);
		}
	
		// News
	
		$news_array = array();
	
		foreach ($projects_ids as $id) {
			$news = getPostsByTypeAndMeta('post', "meta-box-project-star-$id", "true");
	
			foreach ($news as $new) {
				if (!in_array($new, $news_array))
					array_push($news_array, $new);
			}
		}
		
		usort($news_array, 'cmpNews');
		$news_array = array_slice($news_array, 0, 4);
	
		array_push($data, array(
			"identifier" => $goal_identifier,
			"name" => $goal_title,
			"image" => $goal_image,
			"title" => $goal_highlighted,
			"content" => $goal_content,
			"projects" => $projects,
			"news" => $news_array,
			"order" => $goal_order,
			"credits" => $goal_credits
		));
	}

	usort($data, 'cmpGoals');
	
	for ($i = 0; $i < count($data); $i++)
		$data[$i]["index"] = $i + 1;

	return $data;
}

function cmpGoals($a, $b) {
	$a_order = $a["order"];
	$b_order = $b["order"];

	if ($a_order == $b_order) {
		return 0;
	}

	return ($a_order < $b_order) ? -1 : 1;
}

function cmpNews($a, $b) {
	$a_date = $a["date"];
	$b_date = $b["date"];

	if ($a_date == $b_date) {
		return 0;
	}

	return ($a_date < $b_date) ? 1 : -1;
}

//////////////////////////////////////////////////////////////////////////////////////////

function getTeamInfo($type) {
	$meta = array('meta-box-position', 'meta-box-order');
	$team_children = getPostsByType($type, $meta);

	$info = array();

	foreach($team_children as $member) {
		$title = $member["title"];
		$position = $member["meta-box-position"];
		$order = $member["meta-box-order"];
		$image = $member["image"];
		$url = $member["url"];

		array_push($info, array(
			"name" => $title,
			"position" => $position,
			"order" => $order ? $order : 100,
			"image" => $image,
			"url" => $url
		));
	}

	usort($info, 'cmpTeam');

	return $info;
}

function get_last_word($string) {
	$pieces = explode(' ', $string);
	return array_pop($pieces);
}

function cmpTeam($a, $b) {
	$a_order = $a["order"];
	$b_order = $b["order"];

	$a_name = get_last_word($a["name"]);
	$b_name = get_last_word($b["name"]);

	if ($a_order == $b_order) {
		if ($a_name == $b_name)
			return 0;
		
		return ($a_name < $b_name) ? -1 : 1;
	}

	return ($a_order < $b_order) ? -1 : 1;
}
?>

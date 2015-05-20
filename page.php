<?php
/**
 * Template Name: Web Foundation Page
 *
 * @package WordPress
 * @subpackage WF
 * @since 1.0
 */

	require_once(get_stylesheet_directory().'/renderization/controller.php');

	$controller = Controller::getInstance();
	$renderer = $controller->renderer;

	global $post;
	$post_slug = $post->post_name;

	$type = $post->post_type;

	get_header();
	$html = $renderer->renderTemplate($post_slug, $type);

	if (!$html) {
		echo '<main class="content">';
		echo '<div class="container">';

		$id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = '$post_slug'");

		if ($id) {
			echo apply_filters('the_content', get_post($id)->post_content);
		} else {
			echo '404';
		}
		echo '</div></main>';
	}
	else {
		echo $html;
	}

	get_footer();
	die();
?>

<?php
/**
 * The template for displaying all single posts and attachments
 *
 * @package WordPress
 * @subpackage WF
 */
?><?php
  require_once(get_stylesheet_directory().'/renderization/controller.php');

  $controller = Controller::getInstance();
  $renderer = $controller->renderer;
  
  global $post;
  
  $type = $post->post_type;

  get_header();

  echo $renderer->renderTemplate($type . "-detail", "news-detail");

  get_footer();
?>
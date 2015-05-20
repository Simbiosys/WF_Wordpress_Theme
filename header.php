<?php
/**
 * The template for displaying the header
 *
 * Displays all of the head element and everything up until the "site-content" div.
 *
 * @package WordPress
 * @subpackage WF
 * @since Twenty Fifteen 1.0
 */
?><?php
	require_once(__DIR__.'/renderization/controller.php');

	$controller = Controller::getInstance();
	$renderer = $controller->renderer;
    
	echo $renderer->renderTemplate("header");
	wp_head();
?>
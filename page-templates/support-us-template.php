<?php
/**
 * Template Name: Support Us Template
 *
 * @package WordPress
 * @subpackage WF
 */
?><?php
 	require_once(get_stylesheet_directory().'/renderization/controller.php');

        $controller = Controller::getInstance();
        $renderer = $controller->renderer;

	get_header();
	
	echo $renderer->renderTemplate("support-us-template");

	get_footer(); 
?>

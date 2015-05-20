<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the "site-content" div and all content after.
 *
 * @package WordPress
 * @subpackage WF
 */
?><?php
 	require_once(__DIR__.'/renderization/controller.php');

        $controller = Controller::getInstance();
        $renderer = $controller->renderer;
	
	echo $renderer->renderTemplate("footer");
?>


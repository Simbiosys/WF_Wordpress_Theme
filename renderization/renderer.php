<?php

/**
 * Test function to render specific pages.
 *
 */

require_once(get_stylesheet_directory()."/inc/twitter/Twitter.php");
require_once(get_stylesheet_directory().'/inc/simplehtmldom/simple_html_dom.php');

class Renderer {

	private static $instance;

	private $settings;

	private $compiledTemplatesPath;
	private $labelsPath;

	private function __construct($settings) {
		$this->settings = $settings;

		$this->compiledTemplatesPath = get_stylesheet_directory().$this->settings['compiledTemplatesPath'];
		$this->labelsPath = get_stylesheet_directory().$this->settings['labelsPath'];
	}

	public static function getInstance($settings) {
		if (!self::$instance) {
			self::$instance = new static($settings);
		}

		return self::$instance;
	}

	public function renderTemplate($templateName, $postType = NULL, $parent = NULL) {
		$fileCompiledTemplate = $this->compiledTemplatesPath . $templateName;

		if (!file_exists($fileCompiledTemplate) && $postType) {
			$templateName = $postType;
			$fileCompiledTemplate = $this->compiledTemplatesPath . $templateName;
		}

		if (file_exists($fileCompiledTemplate)) {
			$renderer = include($fileCompiledTemplate);

			$navigation = wp_nav_menu(array('theme_location' => 'primary', 'echo' => 0));
			$navigation_about_us = wp_nav_menu(array('menu' => 'Abous-Us-Menu', 'echo' => 0));
			$navigation_contact_us = wp_nav_menu(array('menu' => 'Contact-Us-Menu', 'echo' => 0));
			$navigation_the_web = wp_nav_menu(array('menu' => 'The-Web-Menu', 'echo' => 0));
			$navigation_support_us = wp_nav_menu(array('menu' => 'Support-Us-Menu', 'echo' => 0));

			$pageContent = Array();

			$pageContent["navigation"] = $navigation;
			$pageContent["navigation-about-us"] = $navigation_about_us;
			$pageContent["navigation-contact-us"] = $navigation_contact_us;
			$pageContent["navigation-the-web"] = $navigation_the_web;
			$pageContent["navigation-support-us"] = $navigation_support_us;
			
			$labels = $this->loadLabels("en");
			
			$pageContent["data"] = $this->loadData($templateName, "newsdetail", $labels);

			$pageContent["labels"] = $labels;
			$pageContent["path"] = get_stylesheet_directory_uri();
			$pageContent["host"] = get_site_url();
			$pageContent["title"] = wp_title('|', false, 'right');
			$pageContent["url"] = get_page_link();

			return $renderer($pageContent, true);
		}
		else {
			return false;
		}
	}

	private function loadData($templateName, $default, $labels) {
		$templateName = preg_replace("/[^A-Za-z]/", '', $templateName);

		$path = __DIR__ . '/models/' . $templateName . '.php';

		if (file_exists($path)) {
			require_once($path);
		}
		else {
			require_once(__DIR__ . '/models/' . $default . '.php');
		}

		try {
			$className = ucfirst($templateName) . "Model";
			$modelClass = new ReflectionClass($className);
			$modelObj = $modelClass->newInstanceArgs();

			return $modelObj->get($labels);
		}
		catch (ReflectionException $e) {
			return array();
		}
	}

	private function loadLabels($lang) {
		$labelsFile = $this->labelsPath . $lang . ".json";
		$defaultLabelsFile = $this->labelsPath . "en.json";

		if (file_exists($labelsFile)) {
			return json_decode(file_get_contents($labelsFile), true);
		}
		else if (file_exists($defaultLabelsFile)) {
			return json_decode(file_get_contents($defaultLabelsFile), true);
		}
		else {
			return null;
		}
	}
}
?>

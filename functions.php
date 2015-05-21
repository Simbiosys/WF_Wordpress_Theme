<?php
/**
 * @package WordPress
 * @subpackage WF
 * @since Twenty Fifteen 1.0
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * @since Twenty Fifteen 1.0
 */
if ( ! isset( $content_width ) ) {
	$content_width = 660;
}

/**
 * Twenty Fifteen only works in WordPress 4.1 or later.
 */
if ( version_compare( $GLOBALS['wp_version'], '4.1-alpha', '<' ) ) {
	require get_template_directory() . '/inc/back-compat.php';
}

if ( ! function_exists( 'webfoundation_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 *
 * @since Twenty Fifteen 1.0
 */
function webfoundation_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on twentyfifteen, use a find and replace
	 * to change 'twentyfifteen' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'twentyfifteen', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * See: https://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 825, 510, true );

	// This theme uses wp_nav_menu() in two locations.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu',      'twentyfifteen' ),
		'social'  => __( 'Social Links Menu', 'twentyfifteen' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
	) );

	/*
	 * Enable support for Post Formats.
	 *
	 * See: https://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside', 'image', 'video', 'quote', 'link', 'gallery', 'status', 'audio', 'chat'
	) );

	$color_scheme  = twentyfifteen_get_color_scheme();
	$default_color = trim( $color_scheme[0], '#' );

	// Setup the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'twentyfifteen_custom_background_args', array(
		'default-color'      => $default_color,
		'default-attachment' => 'fixed',
	) ) );

	/*
	 * This theme styles the visual editor to resemble the theme style,
	 * specifically font, colors, icons, and column width.
	 */
	add_editor_style( array( 'css/editor-style.css', 'genericons/genericons.css', twentyfifteen_fonts_url() ) );
}
endif; // webfoundation_setup
add_action( 'after_setup_theme', 'webfoundation_setup' );

/**
 * Register widget area.
 *
 * @since Twenty Fifteen 1.0
 *
 * @link https://codex.wordpress.org/Function_Reference/register_sidebar
 */
function twentyfifteen_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Widget Area', 'twentyfifteen' ),
		'id'            => 'sidebar-1',
		'description'   => __( 'Add widgets here to appear in your sidebar.', 'twentyfifteen' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'twentyfifteen_widgets_init' );

if ( ! function_exists( 'twentyfifteen_fonts_url' ) ) :
/**
 * Register Google fonts for Twenty Fifteen.
 *
 * @since Twenty Fifteen 1.0
 *
 * @return string Google fonts URL for the theme.
 */
function twentyfifteen_fonts_url() {
	$fonts_url = '';
	$fonts     = array();
	$subsets   = 'latin,latin-ext';

	/* translators: If there are characters in your language that are not supported by Noto Sans, translate this to 'off'. Do not translate into your own language. */
	if ( 'off' !== _x( 'on', 'Noto Sans font: on or off', 'twentyfifteen' ) ) {
		$fonts[] = 'Noto Sans:400italic,700italic,400,700';
	}

	/* translators: If there are characters in your language that are not supported by Noto Serif, translate this to 'off'. Do not translate into your own language. */
	if ( 'off' !== _x( 'on', 'Noto Serif font: on or off', 'twentyfifteen' ) ) {
		$fonts[] = 'Noto Serif:400italic,700italic,400,700';
	}

	/* translators: If there are characters in your language that are not supported by Inconsolata, translate this to 'off'. Do not translate into your own language. */
	if ( 'off' !== _x( 'on', 'Inconsolata font: on or off', 'twentyfifteen' ) ) {
		$fonts[] = 'Inconsolata:400,700';
	}

	/* translators: To add an additional character subset specific to your language, translate this to 'greek', 'cyrillic', 'devanagari' or 'vietnamese'. Do not translate into your own language. */
	$subset = _x( 'no-subset', 'Add new subset (greek, cyrillic, devanagari, vietnamese)', 'twentyfifteen' );

	if ( 'cyrillic' == $subset ) {
		$subsets .= ',cyrillic,cyrillic-ext';
	} elseif ( 'greek' == $subset ) {
		$subsets .= ',greek,greek-ext';
	} elseif ( 'devanagari' == $subset ) {
		$subsets .= ',devanagari';
	} elseif ( 'vietnamese' == $subset ) {
		$subsets .= ',vietnamese';
	}

	if ( $fonts ) {
		$fonts_url = add_query_arg( array(
			'family' => urlencode( implode( '|', $fonts ) ),
			'subset' => urlencode( $subsets ),
		), '//fonts.googleapis.com/css' );
	}

	return $fonts_url;
}
endif;

/**
 * Enqueue scripts and styles.
 *
 * @since Twenty Fifteen 1.0
 */
function twentyfifteen_scripts() {
	// Add custom fonts, used in the main stylesheet.
	wp_enqueue_style( 'twentyfifteen-fonts', twentyfifteen_fonts_url(), array(), null );

	// Add Genericons, used in the main stylesheet.
	wp_enqueue_style( 'genericons', get_template_directory_uri() . '/genericons/genericons.css', array(), '3.2' );

	// Load our main stylesheet.
	wp_enqueue_style( 'twentyfifteen-style', get_stylesheet_uri() );

	// Load the Internet Explorer specific stylesheet.
	wp_enqueue_style( 'twentyfifteen-ie', get_template_directory_uri() . '/css/ie.css', array( 'twentyfifteen-style' ), '20141010' );
	wp_style_add_data( 'twentyfifteen-ie', 'conditional', 'lt IE 9' );

	// Load the Internet Explorer 7 specific stylesheet.
	wp_enqueue_style( 'twentyfifteen-ie7', get_template_directory_uri() . '/css/ie7.css', array( 'twentyfifteen-style' ), '20141010' );
	wp_style_add_data( 'twentyfifteen-ie7', 'conditional', 'lt IE 8' );

	wp_enqueue_script( 'twentyfifteen-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20141010', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( is_singular() && wp_attachment_is_image() ) {
		wp_enqueue_script( 'twentyfifteen-keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array( 'jquery' ), '20141010' );
	}

	wp_enqueue_script( 'twentyfifteen-script', get_template_directory_uri() . '/js/functions.js', array( 'jquery' ), '20141212', true );
	wp_localize_script( 'twentyfifteen-script', 'screenReaderText', array(
		'expand'   => '<span class="screen-reader-text">' . __( 'expand child menu', 'twentyfifteen' ) . '</span>',
		'collapse' => '<span class="screen-reader-text">' . __( 'collapse child menu', 'twentyfifteen' ) . '</span>',
	) );
}
add_action( 'wp_enqueue_scripts', 'twentyfifteen_scripts' );

/**
 * Add featured image as background image to post navigation elements.
 *
 * @since Twenty Fifteen 1.0
 *
 * @see wp_add_inline_style()
 */
function twentyfifteen_post_nav_background() {
	if ( ! is_single() ) {
		return;
	}

	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );
	$css      = '';

	if ( is_attachment() && 'attachment' == $previous->post_type ) {
		return;
	}

	if ( $previous &&  has_post_thumbnail( $previous->ID ) ) {
		$prevthumb = wp_get_attachment_image_src( get_post_thumbnail_id( $previous->ID ), 'post-thumbnail' );
		$css .= '
			.post-navigation .nav-previous { background-image: url(' . esc_url( $prevthumb[0] ) . '); }
			.post-navigation .nav-previous .post-title, .post-navigation .nav-previous a:hover .post-title, .post-navigation .nav-previous .meta-nav { color: #fff; }
			.post-navigation .nav-previous a:before { background-color: rgba(0, 0, 0, 0.4); }
		';
	}

	if ( $next && has_post_thumbnail( $next->ID ) ) {
		$nextthumb = wp_get_attachment_image_src( get_post_thumbnail_id( $next->ID ), 'post-thumbnail' );
		$css .= '
			.post-navigation .nav-next { background-image: url(' . esc_url( $nextthumb[0] ) . '); }
			.post-navigation .nav-next .post-title, .post-navigation .nav-next a:hover .post-title, .post-navigation .nav-next .meta-nav { color: #fff; }
			.post-navigation .nav-next a:before { background-color: rgba(0, 0, 0, 0.4); }
		';
	}

	wp_add_inline_style( 'twentyfifteen-style', $css );
}
add_action( 'wp_enqueue_scripts', 'twentyfifteen_post_nav_background' );

/**
 * Display descriptions in main navigation.
 *
 * @since Twenty Fifteen 1.0
 *
 * @param string  $item_output The menu item output.
 * @param WP_Post $item        Menu item object.
 * @param int     $depth       Depth of the menu.
 * @param array   $args        wp_nav_menu() arguments.
 * @return string Menu item with possible description.
 */
function twentyfifteen_nav_description( $item_output, $item, $depth, $args ) {
	if ( 'primary' == $args->theme_location && $item->description ) {
		$item_output = str_replace( $args->link_after . '</a>', '<div class="menu-item-description">' . $item->description . '</div>' . $args->link_after . '</a>', $item_output );
	}

	return $item_output;
}
add_filter( 'walker_nav_menu_start_el', 'twentyfifteen_nav_description', 10, 4 );

/**
 * Add a `screen-reader-text` class to the search form's submit button.
 *
 * @since Twenty Fifteen 1.0
 *
 * @param string $html Search form HTML.
 * @return string Modified search form HTML.
 */
function twentyfifteen_search_form_modify( $html ) {
	return str_replace( 'class="search-submit"', 'class="search-submit screen-reader-text"', $html );
}
add_filter( 'get_search_form', 'twentyfifteen_search_form_modify' );

/**
 * Implement the Custom Header feature.
 *
 * @since Twenty Fifteen 1.0
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 *
 * @since Twenty Fifteen 1.0
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Customizer additions.
 *
 * @since Twenty Fifteen 1.0
 */
require get_template_directory() . '/inc/customizer.php';

//////////////////////////////////////////////////////////////////////////////////////////
//                                       SITE URL
//////////////////////////////////////////////////////////////////////////////////////////

//update_option('siteurl', "http://".$_SERVER['HTTP_HOST']."/webfoundation/");
//update_option('home', "http://".$_SERVER['HTTP_HOST']."/webfoundation/");

//////////////////////////////////////////////////////////////////////////////////////////
//                                     Read more
//////////////////////////////////////////////////////////////////////////////////////////

add_filter('the_content_more_link', 'modify_read_more_link');
add_filter('excerpt_more', 'new_excerpt_more');

function modify_read_more_link() {
	return '<a class="more-link text-right m-left" href="' . get_permalink() . '">Read more</a>';
}

function new_excerpt_more($more) {
    global $post;
	return '<a class="more-link text-right m-left" href="'. get_permalink($post->ID) . '">Read more</a>';
}

//////////////////////////////////////////////////////////////////////////////////////////
//                                   Research taxonomy
//////////////////////////////////////////////////////////////////////////////////////////

add_action( 'init', 'create_research_taxonomies', 0 );

function create_research_taxonomies() {

	$labels = array(
		'name'              => _x( 'Tags', 'taxonomy general name' ),
		'singular_name'     => _x( 'Tag', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Tags' ),
		'all_items'         => __( 'All Tags' ),
		'parent_item'       => __( 'Parent Tag' ),
		'parent_item_colon' => __( 'Parent Tag:' ),
		'edit_item'         => __( 'Edit Tag' ),
		'update_item'       => __( 'Update Tag' ),
		'add_new_item'      => __( 'Add New Tag' ),
		'new_item_name'     => __( 'New Genre Tag' ),
		'menu_name'         => __( 'Tag' ),
	);

	$args = array(
		'labels'            => $labels,
		'hierarchical'      => true,
        'show_ui'           => true,
        'how_in_nav_menus'  => true,
        'public'            => true,
        'show_admin_column' => true,
        'query_var'         => true,
		'rewrite'           => array('slug' => 'tag'),
	);

	register_taxonomy('tag', array('research'), $args );
}

//////////////////////////////////////////////////////////////////////////////////////////
//                                   Custom post type
//////////////////////////////////////////////////////////////////////////////////////////

add_action('init', 'custom_post_types');

function custom_post_types() {
	// Team members

	register_post_type( 'team_member',
		array(
			'public' => true,
			'labels' => array(
				'name' => __( 'Team Members' ),
				'singular_name' => __( 'Team Member' ),
				'add_new' => __( 'Add New' ),
				'add_new_item' => __( 'Add New Team Member' ),
				'edit' => __( 'Edit' ),
				'edit_item' => __( 'Edit Team Member' ),
				'new_item' => __( 'New Team Member' ),
				'view' => __( 'View Team Member' ),
				'view_item' => __( 'View Team Member' ),
				'search_items' => __( 'Search Team Members' ),
				'not_found' => __( 'No Team Members found' ),
				'not_found_in_trash' => __( 'No Team Members found in Trash' ),
				'parent' => __( 'Parent Team Member' ),
				'all_items' => __( 'All Team Members' ),
			),
			'menu_icon' => get_stylesheet_directory_uri() . '/img/member.png',
			'hierarchical' => false,
			'supports' => array( 'title', 'editor', 'excerpt', 'custom-fields', 'thumbnail', 'revisions' ),
			'rewrite' => array( 'slug' => 'about/executive-team', 'with_front' => false ),
		)
	);
	
	register_post_type( 'board_member',
		array(
			'public' => true,
			'labels' => array(
				'name' => __( 'Board Members' ),
				'singular_name' => __( 'Board Member' ),
				'add_new' => __( 'Add New' ),
				'add_new_item' => __( 'Add New Board Member' ),
				'edit' => __( 'Edit' ),
				'edit_item' => __( 'Edit Board Member' ),
				'new_item' => __( 'New Board Member' ),
				'view' => __( 'View Board Member' ),
				'view_item' => __( 'View Board Member' ),
				'search_items' => __( 'Search Board Members' ),
				'not_found' => __( 'No Board Members found' ),
				'not_found_in_trash' => __( 'No Board Members found in Trash' ),
				'parent' => __( 'Parent Board Member' ),
				'all_items' => __( 'All Board Members' ),
			),
			'menu_icon' => get_stylesheet_directory_uri() . '/img/board.png',
			'hierarchical' => false,
			'supports' => array( 'title', 'editor', 'excerpt', 'custom-fields', 'thumbnail', 'revisions' ),
			'rewrite' => array( 'slug' => 'about/board', 'with_front' => false ),
		)
	);
	
	// Goals

	register_post_type( 'goal',
		array(
			'public' => false,
			'show_ui' => true,
			'labels' => array(
				'name' => __( 'Goals' ),
				'singular_name' => __( 'Goal' ),
				'add_new' => __( 'Add New' ),
				'add_new_item' => __( 'Add New Goal' ),
				'edit' => __( 'Edit' ),
				'edit_item' => __( 'Edit Goal' ),
				'new_item' => __( 'New Goal' ),
				'view' => __( 'View Goal' ),
				'view_item' => __( 'View Goal' ),
				'search_items' => __( 'Search Goals' ),
				'not_found' => __( 'No Goals found' ),
				'not_found_in_trash' => __( 'No Goals found in Trash' ),
				'parent' => __( 'Parent Goal' ),
				'all_items' => __( 'All Goals' ),
			),
			'menu_icon' => get_stylesheet_directory_uri() . '/img/goal.png',
			'hierarchical' => false,
			'supports' => array( 'title', 'editor', 'excerpt', 'custom-fields', 'thumbnail', 'revisions' ),
			'rewrite' => array( 'slug' => 'our-work/goals', 'with_front' => false ),
		)
	);

	// Projects

	register_post_type( 'project',
		array(
			'public' => true,
			'labels' => array(
				'name' => __( 'Projects' ),
				'singular_name' => __( 'Project' ),
				'add_new' => __( 'Add New' ),
				'add_new_item' => __( 'Add New Project' ),
				'edit' => __( 'Edit' ),
				'edit_item' => __( 'Edit Project' ),
				'new_item' => __( 'New Project' ),
				'view' => __( 'View Project' ),
				'view_item' => __( 'View Project' ),
				'search_items' => __( 'Search Projects' ),
				'not_found' => __( 'No Projects found' ),
				'not_found_in_trash' => __( 'No Projects found in Trash' ),
				'parent' => __( 'Parent Project' ),
				'all_items' => __( 'All Projects' ),
			),
			'menu_icon' => get_stylesheet_directory_uri() . '/img/project.png',
			'hierarchical' => false,
			'supports' => array( 'title', 'editor', 'excerpt', 'custom-fields', 'thumbnail', 'revisions' ),
			'rewrite' => array( 'slug' => 'our-work/projects', 'with_front' => false ),
		)
	);

	// Funders

	register_post_type( 'funder',
		array(
			'public' => true,
			'labels' => array(
				'name' => __( 'Funders' ),
				'singular_name' => __( 'Funder' ),
				'add_new' => __( 'Add New' ),
				'add_new_item' => __( 'Add New Funder' ),
				'edit' => __( 'Edit' ),
				'edit_item' => __( 'Edit Funder' ),
				'new_item' => __( 'New Funder' ),
				'view' => __( 'View Funder' ),
				'view_item' => __( 'View Funder' ),
				'search_items' => __( 'Search Funders' ),
				'not_found' => __( 'No Funders found' ),
				'not_found_in_trash' => __( 'No Funders found in Trash' ),
				'parent' => __( 'Parent Funder' ),
				'all_items' => __( 'All Funders' ),
			),
			'menu_icon' => get_stylesheet_directory_uri() . '/img/funder.png',
			'hierarchical' => false,
			'supports' => array( 'title', 'editor', 'excerpt', 'custom-fields', 'thumbnail', 'revisions' ),
			'rewrite' => array( 'slug' => 'about/funding-partners', 'with_front' => false ),
		)
	);
	
	// Research

	register_post_type( 'research',
		array(
			'public' => true,
			'labels' => array(
				'name' => __( 'Research' ),
				'singular_name' => __( 'Research' ),
				'add_new' => __( 'Add New' ),
				'add_new_item' => __( 'Add New Research' ),
				'edit' => __( 'Edit' ),
				'edit_item' => __( 'Edit Research' ),
				'new_item' => __( 'New Research' ),
				'view' => __( 'View Research' ),
				'view_item' => __( 'View Research' ),
				'search_items' => __( 'Search Research' ),
				'not_found' => __( 'No Research found' ),
				'not_found_in_trash' => __( 'No Research found in Trash' ),
				'parent' => __( 'Parent Research' ),
				'all_items' => __( 'All Research' ),
			),
			'menu_icon' => get_stylesheet_directory_uri() . '/img/research.png',
			'hierarchical' => false,
			'supports' => array( 'title', 'editor', 'excerpt', 'custom-fields', 'thumbnail', 'revisions' ),
			'rewrite' => array( 'slug' => 'about/research', 'with_front' => false ),
		)
	);
	
	// Annual reports

	register_post_type( 'annual_report',
		array(
			'public' => true,
			'labels' => array(
				'name' => __( 'Annual Reports' ),
				'singular_name' => __( 'Annual Report' ),
				'add_new' => __( 'Add New' ),
				'add_new_item' => __( 'Add Annual Report' ),
				'edit' => __( 'Edit' ),
				'edit_item' => __( 'Edit Annual Report' ),
				'new_item' => __( 'New Annual Report' ),
				'view' => __( 'View Annual Report' ),
				'view_item' => __( 'View Annual Report' ),
				'search_items' => __( 'Search Annual Reports' ),
				'not_found' => __( 'No Annual Reports found' ),
				'not_found_in_trash' => __( 'No Annual Reports found in Trash' ),
				'parent' => __( 'Parent Annual Report' ),
				'all_items' => __( 'All Annual Reports' ),
			),
			'menu_icon' => get_stylesheet_directory_uri() . '/img/report.png',
			'hierarchical' => false,
			'supports' => array( 'title', 'editor', 'thumbnail', 'revisions' ),
			'rewrite' => array( 'slug' => 'about/annual-reports', 'with_front' => false ),
		)
	);

	flush_rewrite_rules();
}

//////////////////////////////////////////////////////////////////////////////////////////
//                                   Auxiliary functions
//////////////////////////////////////////////////////////////////////////////////////////

function get_projects() {
	include_once('inc/simplehtmldom/simple_html_dom.php');
	include_once('renderization/models/utils.php');	
	
	$goals = getPostsByType('goal', array('meta-box-identifier'));
	
	$top_areas = array();
	
	foreach ($goals as $goal) {
		$g_title = $goal['title'];
		$g_identifier = $goal['meta-box-identifier'];
		
		$projects = getPostsByTypeAndMeta('project', "meta-box-goal", $g_identifier);
		
		$projects_array = array();
		
		foreach ($projects as $project) {
			$project_title = $project['title'];
			$project_id = $project['id'];
			
			array_push($projects_array, array(
				"title" => $project_title,
				"id" => $project_id
			));
		}
		
		array_push($top_areas, array(
			"title" => $g_title,
			"id" => $g_identifier,
			"projects" => $projects_array
		));
	}

	return $top_areas;
}

function show_projects($object) {
	$projects = get_projects();

	foreach ($projects as $area) {
		$area_title = $area["title"];
		$area_projects = $area["projects"];
		
		$path = get_stylesheet_directory_uri();
		
		echo "	<style>
					.star_unset, .star_set {
						display: inline-block;
						width: 15px;
						height: 15px;
						background: url(" . $path . "/img/star.png);
						background-repeat: no-repeat;
						background-size: cover;
						cursor: pointer;
						margin-top: -2px;
  						margin-right: 4px;
  						margin-left: 2px;
					}
					
					 .star_set {
						background: url(" . $path . "/img/star_c.png);
						background-size: cover;
					}
					
					.hidden {
						display: none !important;
					}
				</style>";
		
		echo "<p class='area-name'>$area_title</p>";

		foreach ($area_projects as $project) {
			$p_id = $project["id"]; 
			$p_title = $project["title"];

			$value = get_post_meta($object->ID, "meta-box-project-$p_id", true);
			$disabled = "";

			echo "<p>";

			if ($value == "true") {
				echo "<input name='meta-box-project-$p_id' id='meta-box-project-$p_id' type='checkbox' value='true' data-set='$p_id' checked />";
			}
			else {
				echo "<input name='meta-box-project-$p_id' id='meta-box-project-$p_id' type='checkbox' value='true' data-set='$p_id' />";
				$disabled = "disabled";
			}
			
			$star_value = get_post_meta($object->ID, "meta-box-project-star-$p_id", true);

			if ($star_value == "true") {
				echo "<input name='meta-box-project-star-$p_id' id='meta-box-project-star-$p_id' type='checkbox' value='true' data-star='$p_id' class='hidden' checked $disabled />";
				echo " <label for='meta-box-project-star-$p_id' class='star_set'></label>";
			}
			else {
				echo "<input name='meta-box-project-star-$p_id' id='meta-box-project-star-$p_id' type='checkbox' value='true' data-star='$p_id' class='hidden' $disabled />";
				echo " <label for='meta-box-project-star-$p_id' class='star_unset'></label>";
			}
			
			echo " <label for='meta-box-project-$p_id'>$p_title</label>";
			echo "</p>";
		}
		
		echo "<script>
			var checks = document.querySelectorAll('[data-star]');
			var length = checks.length;
			
			for (var i = 0; i < length; i++) {
				var check = checks[i];
				
				check.onchange = function() {
					var id = this.id;
					var label = document.querySelector('label[for=\'' + id + '\']');
					
					if (label)
						label.className = this.checked ? 'star_set' : 'star_unset';
				}
			}
			
			var checks = document.querySelectorAll('[data-set]');
			var length = checks.length;
			
			for (var i = 0; i < length; i++) {
				var check = checks[i];
				
				check.onchange = function() {
					var id = this.getAttribute('data-set');
					var input = document.querySelector('[data-star=\'' + id + '\']');
				
					if (input) {
						input.disabled = !this.checked;
						
						if (!this.checked)
							input.checked = false;	
					}
				}
			}
			
		</script>";
	}
}

function get_funders() {
	include_once('inc/simplehtmldom/simple_html_dom.php');
	include_once('renderization/models/utils.php');	
	
	$funders = getPostsByType('funder', array('meta-box-identifier'));
	usort($funders, 'cmpFunders');
	
	return $funders;
}

function cmpFunders($a, $b) {
	$a_title = $a["title"];
	$b_title = $b["title"];
		
	if ($a_title == $b_title)
		return 0;
		
	return ($a_title < $b_title) ? -1 : 1;
}

function show_funders($object) {
	$funders = get_funders();
	
	echo "<fieldset><legend>Primary funders</legend>";
	
	foreach ($funders as $funder) {
		$f_id = $funder["id"]; 
		$f_title = $funder["title"];
		
		$value = get_post_meta($object->ID, "meta-box-primary-funder-$f_id", true);
		
		echo "<p>";

		if ($value == "true") {
			echo "<input name='meta-box-primary-funder-$f_id' type='checkbox' value='true' checked />";
		}
		else {
			echo "<input name='meta-box-primary-funder-$f_id' type='checkbox' value='true' />";
		}

		echo " <label for='meta-box-primary-funder-$f_id'>$f_title</label></p>";
	}
	
	echo "</fieldset>";
	
	echo "<fieldset><legend>Secondary funders</legend>";
	
	foreach ($funders as $funder) {
		$f_id = $funder["id"]; 
		$f_title = $funder["title"];
		
		$value = get_post_meta($object->ID, "meta-box-secondary-funder-$f_id", true);
		
		echo "<p>";

		if ($value == "true") {
			echo "<input name='meta-box-secondary-funder-$f_id' type='checkbox' value='true' checked />";
		}
		else {
			echo "<input name='meta-box-secondary-funder-$f_id' type='checkbox' value='true' />";
		}

		echo " <label for='meta-box-secondary-funder-$f_id'>$f_title</label></p>";
	}
	
	echo "</fieldset>";
}

//////////////////////////////////////////////////////////////////////////////////////////
//                                   Project custom meta info
//////////////////////////////////////////////////////////////////////////////////////////

function get_meta_style() {
	return "<style>
        	.w100 {
        		width: 100%;
        		margin-bottom: 10px;
        	}

        	.area-name {
        		border-bottom: 1px solid #ccc;
        		color: #999;
        	}

        	fieldset p {
        		padding: 0 5px;
        	}

        	legend {
        		width: 100%;
        		font-weight: bold;
        		color: #888;
        		border-bottom: 1px solid #888;
        	}
        	
        	textarea {
        		height: 150px;
        	}
        </style>";
}

function get_goals() {
	include_once('inc/simplehtmldom/simple_html_dom.php');
	include_once('renderization/models/utils.php');
	
	$goals = getPostsByType('goal', array('meta-box-identifier'));
	
	$goal_array = array();
	
	foreach ($goals as $goal) {
		$title = $goal['title'];
		$identifier = $goal['meta-box-identifier'];
		
		array_push($goal_array, array(
			"title" => $title,
			"id" => $identifier
		));
	}

	return $goal_array;
}

function project_meta_box_markup($object)
{
    wp_nonce_field(basename(__FILE__), "meta-box-nonce");

    ?>
        <?= get_meta_style() ?>
        <div>
            <label for="meta-box-url">URL</label>
            <input name="meta-box-url" type="url" value="<?php echo get_post_meta($object->ID, "meta-box-url", true); ?>" placeholder="http://thewebindex.org" class="w100" />

 			<label for="meta-box-start">Start date</label>
            <input name="meta-box-start" type="date" value="<?php echo get_post_meta($object->ID, "meta-box-start", true); ?>" class="w100" />

            <label for="meta-box-end">End date</label>
            <input name="meta-box-end" type="date" value="<?php echo get_post_meta($object->ID, "meta-box-end", true); ?>" class="w100" />
            
            <?php $selected_goal = get_post_meta($object->ID, "meta-box-goal", true); ?>
            
            <label for="meta-box-goal">Goal</label>
            <?php $goals = get_goals(); ?>
            <select name="meta-box-goal" class="w100">
            	<?php foreach ($goals as $goal): 
            		$identifier = $goal['id'];
            		$title = $goal['title'];
            		$selected = $selected_goal == $identifier ? 'selected' : '';
            	?>
            	<option value="<?= $identifier ?>" <?= $selected ?>><?= $title ?></option>
            	<?php endforeach; ?>
            </select>
            
            <?php show_funders($object); ?>
        </div>
    <?php
}

function project_extra_meta_box_markup($object)
{
    wp_nonce_field(basename(__FILE__), "meta-box-nonce");

    ?>
        <?= get_meta_style() ?>
        <div>
        	<label for="meta-box-caption">Caption</label>
            <textarea name="meta-box-caption" class="w100"><?php echo get_post_meta($object->ID, "meta-box-caption", true); ?></textarea>
        </div>
    <?php
    
    $field_value = get_post_meta($object->ID, 'meta-box-highlighted', true);
  	
  	wp_editor($field_value, 'meta-box-highlighted');
}

function project_credits_extra_meta_box_markup($object) {
	wp_nonce_field(basename(__FILE__), "meta-box-nonce");

    ?>
        <?= get_meta_style() ?>
        <div>
            <textarea name="meta-box-credits" type="text" class="w100"><?= get_post_meta($object->ID, "meta-box-credits", true) ?></textarea>
        </div>
    <?php
}

function project_add_custom_meta_box() {
    add_meta_box("project-meta-box", "Project Extra Info", "project_meta_box_markup", "project", "side", "high", null);
    add_meta_box("project-extra-meta-box", "Highlighted text", "project_extra_meta_box_markup", "project", "normal", "high", null);
    add_meta_box("project-credits-extra-meta-box", "Main picture credits", "project_credits_extra_meta_box_markup", "project", "normal", "high", null);
}

add_action("add_meta_boxes", "project_add_custom_meta_box");

function save_project_meta_box($post_id, $post, $update) {
    if (!isset($_POST["meta-box-nonce"]) || !wp_verify_nonce($_POST["meta-box-nonce"], basename(__FILE__)))
        return $post_id;

    if (!current_user_can("edit_post", $post_id))
        return $post_id;

    if (defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
        return $post_id;

    $slug = "project";

    if ($slug != $post->post_type)
        return $post_id;

    save_meta_box_field($post_id, "meta-box-url");
    save_meta_box_field($post_id, "meta-box-start");
    save_meta_box_field($post_id, "meta-box-end");
    save_meta_box_field($post_id, "meta-box-goal");
    save_meta_box_field($post_id, "meta-box-highlighted");
    save_meta_box_field($post_id, "meta-box-caption");
    
    save_meta_box_field($post_id, "meta-box-credits");
    
    $funders = get_funders();
	

	foreach ($funders as $funder) {
		$f_id = $funder["id"]; 
		
		save_meta_box_field($post_id, "meta-box-primary-funder-$f_id");
		save_meta_box_field($post_id, "meta-box-secondary-funder-$f_id");
	}
}

add_action("save_post", "save_project_meta_box", 10, 3);

//////////////////////////////////////////////////////////////////////////////////////////
//                                   People custom meta info
//////////////////////////////////////////////////////////////////////////////////////////

function person_meta_box_markup($object)
{
    wp_nonce_field(basename(__FILE__), "meta-box-nonce");

    ?>
        <?= get_meta_style() ?>
        <div>
            <label for="meta-box-email">Email</label>
            <input name="meta-box-email" type="email" value="<?php echo get_post_meta($object->ID, "meta-box-email", true); ?>" placeholder="info@webfoundation.org" class="w100" />

 			<label for="meta-box-position">Position</label>
            <input name="meta-box-position" type="text" value="<?php echo get_post_meta($object->ID, "meta-box-position", true); ?>" placeholder="Member position" class="w100" />

            <label for="meta-box-order">Order</label>
            <input name="meta-box-order" type="number" value="<?php echo get_post_meta($object->ID, "meta-box-order", true); ?>" placeholder="3" class="w100" />
            
            <?php $selected_group = get_post_meta($object->ID, "meta-box-group", true); ?>
            
            <label for="meta-box-group">Group</label>
            <select name="meta-box-group" class="w100">
            	<option value="other" <?= $selected_group == 'other' || $selected_group == '' ? 'selected' : '' ?>>Other</option>
            	<option value="fundraising" <?= $selected_group == 'fundraising' ? 'selected' : '' ?>>Fundraising</option>
            </select>
            
            <fieldset>
            	<legend>Social networks</legend>
				<label for="meta-box-linkedin">Linkedin</label>
				<input name="meta-box-linkedin" type="text" value="<?php echo get_post_meta($object->ID, "meta-box-linkedin", true); ?>" placeholder="username" class="w100" />
			
				<label for="meta-box-twitter">Twitter</label>
				<input name="meta-box-twitter" type="text" value="<?php echo get_post_meta($object->ID, "meta-box-twitter", true); ?>" placeholder="username" class="w100" />
			
				<label for="meta-box-google">Google+</label>
				<input name="meta-box-google" type="text" value="<?php echo get_post_meta($object->ID, "meta-box-google", true); ?>" placeholder="username" class="w100" />
			
				<label for="meta-box-facebook">Facebook</label>
				<input name="meta-box-facebook" type="text" value="<?php echo get_post_meta($object->ID, "meta-box-facebook", true); ?>" placeholder="username" class="w100" />
			</fieldset>
			
            <fieldset>
    			<legend>Related projects</legend>
    			<?php show_projects($object); ?>
    		</fieldset>
        </div>
    <?php
}

function add_custom_meta_box() {
    add_meta_box("person-meta-box", "Person Extra Info", "person_meta_box_markup", "team_member", "side", "high", null);
    add_meta_box("person-meta-box", "Person Extra Info", "person_meta_box_markup", "board_member", "side", "high", null);
}

add_action("add_meta_boxes", "add_custom_meta_box");

function save_meta_box_field($post_id, $name) {
	$value = "";

	if (isset($_POST[$name])) {
        $value = $_POST[$name];
    }

    update_post_meta($post_id, $name, $value);
}

function save_person_meta_box($post_id, $post, $update) {
    if (!isset($_POST["meta-box-nonce"]) || !wp_verify_nonce($_POST["meta-box-nonce"], basename(__FILE__)))
        return $post_id;

    if (!current_user_can("edit_post", $post_id))
        return $post_id;

    if (defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
        return $post_id;
        
    $slugs = array("team_member", "board_member");

    if (!in_array($post->post_type, $slugs))
        return $post_id;

    save_meta_box_field($post_id, "meta-box-email");
    save_meta_box_field($post_id, "meta-box-position");
    save_meta_box_field($post_id, "meta-box-order");
    save_meta_box_field($post_id, "meta-box-linkedin");
    save_meta_box_field($post_id, "meta-box-twitter");
    save_meta_box_field($post_id, "meta-box-google");
    save_meta_box_field($post_id, "meta-box-facebook");
    save_meta_box_field($post_id, "meta-box-group");

    $projects = get_projects();

    foreach ($projects as $area) {
    	$area_projects = $area["projects"];

    	foreach ($area_projects as $project) {
    		$project_id = $project["id"];
    		save_meta_box_field($post_id, "meta-box-project-$project_id");
    		save_meta_box_field($post_id, "meta-box-project-star-$project_id");
    	}
    }
}

add_action("save_post", "save_person_meta_box", 10, 3);

//////////////////////////////////////////////////////////////////////////////////////////
//                               Research/Post custom meta info
//////////////////////////////////////////////////////////////////////////////////////////

function custom_meta_box_markup($object)
{
    wp_nonce_field(basename(__FILE__), "meta-box-nonce");

    ?>
        <?= get_meta_style() ?>
        <div>
            <fieldset>
    			<legend>Related projects</legend>
    			<?php show_projects($object); ?>
    		</fieldset>
        </div>
    <?php
}

function post_credits_extra_meta_box_markup($object) {
	wp_nonce_field(basename(__FILE__), "meta-box-nonce");

    ?>
        <?= get_meta_style() ?>
        <div>
            <textarea name="meta-box-credits" type="text" class="w100"><?= get_post_meta($object->ID, "meta-box-credits", true) ?></textarea>
        </div>
    <?php
}

function custom_add_custom_meta_box() {
    add_meta_box("research-meta-box", "Research Extra Info", "custom_meta_box_markup", "research", "side", "high", null);
    add_meta_box("post-meta-box", "Post Extra Info", "custom_meta_box_markup", "post", "side", "high", null);
    add_meta_box("post-credits-extra-meta-box", "Main picture credits", "post_credits_extra_meta_box_markup", "post", "normal", "high", null);
    add_meta_box("research-credits-extra-meta-box", "Main picture credits", "post_credits_extra_meta_box_markup", "research", "normal", "high", null);
}

add_action("add_meta_boxes", "custom_add_custom_meta_box");

function save_custom_meta_box($post_id, $post, $update) {
    if (!isset($_POST["meta-box-nonce"]) || !wp_verify_nonce($_POST["meta-box-nonce"], basename(__FILE__)))
        return $post_id;

    if (!current_user_can("edit_post", $post_id))
        return $post_id;

    if (defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
        return $post_id;

    $slugs = array("post", "research");

    if (!in_array($post->post_type, $slugs))
        return $post_id;
        
    save_meta_box_field($post_id, "meta-box-credits");

    $projects = get_projects();

    foreach ($projects as $area) {
    	$area_projects = $area["projects"];

    	foreach ($area_projects as $project) {
    		$project_id = $project["id"];
    		save_meta_box_field($post_id, "meta-box-project-$project_id");
    		save_meta_box_field($post_id, "meta-box-project-star-$project_id");
    	}
    }
}

add_action("save_post", "save_custom_meta_box", 10, 3);

//////////////////////////////////////////////////////////////////////////////////////////
//                               Funder custom meta info
//////////////////////////////////////////////////////////////////////////////////////////

function funder_meta_box_markup($object)
{
    wp_nonce_field(basename(__FILE__), "meta-box-nonce");

    ?>
        <?= get_meta_style() ?>
        <div>
        	<label for="meta-box-url">URL</label>
            <input name="meta-box-url" type="url" value="<?php echo get_post_meta($object->ID, "meta-box-url", true); ?>" placeholder="http://www.google.com" class="w100" />
           <!-- 
            <fieldset>
    			<legend>Related projects</legend>
    			<?php show_projects($object); ?>
    		</fieldset> -->
        </div>
    <?php
}

function funder_add_custom_meta_box() {
    add_meta_box("funder-meta-box", "Funder Extra Info", "funder_meta_box_markup", "funder", "side", "high", null);
}

add_action("add_meta_boxes", "funder_add_custom_meta_box");

function save_funder_meta_box($post_id, $post, $update) {
    if (!isset($_POST["meta-box-nonce"]) || !wp_verify_nonce($_POST["meta-box-nonce"], basename(__FILE__)))
        return $post_id;

    if (!current_user_can("edit_post", $post_id))
        return $post_id;

    if (defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
        return $post_id;

    $slug = "funder";

    if ($slug != $post->post_type)
        return $post_id;
        
    save_meta_box_field($post_id, "meta-box-url");
/*
    $projects = get_projects();

    foreach ($projects as $area) {
    	$area_projects = $area["projects"];

    	foreach ($area_projects as $project) {
    		$project_id = $project["id"];
    		save_meta_box_field($post_id, "meta-box-project-$project_id");
    		save_meta_box_field($post_id, "meta-box-project-star-$project_id");
    	}
    }*/
}

add_action("save_post", "save_funder_meta_box", 10, 3);

//////////////////////////////////////////////////////////////////////////////////////////
//                                   Goal custom meta info
//////////////////////////////////////////////////////////////////////////////////////////

function goal_extra_meta_box_markup($object)
{
    wp_nonce_field(basename(__FILE__), "meta-box-nonce");

    ?>
        <?= get_meta_style() ?>
        
        <div>
            <textarea name="meta-box-highlighted" class="w100"><?php echo get_post_meta($object->ID, "meta-box-highlighted", true); ?></textarea>
        </div>
    <?php
}

function goal_meta_box_markup($object) {
    wp_nonce_field(basename(__FILE__), "meta-box-nonce");

    ?>
        <?= get_meta_style() ?>
        
        <div>
        	<label for="meta-box-identifier">Identifier</label>
            <input name="meta-box-identifier" type="text" value="<?php echo get_post_meta($object->ID, "meta-box-identifier", true); ?>" class="w100" />
            <label for="meta-box-order">Order</label>
            <input name="meta-box-order" type="number" value="<?php echo get_post_meta($object->ID, "meta-box-order", true); ?>" class="w100" />
        </div>
    <?php
}

function goal_credits_extra_meta_box_markup($object) {
	wp_nonce_field(basename(__FILE__), "meta-box-nonce");

    ?>
        <?= get_meta_style() ?>
        <div>
            <textarea name="meta-box-credits" type="text" class="w100"><?= get_post_meta($object->ID, "meta-box-credits", true) ?></textarea>
        </div>
    <?php
}

function goal_add_custom_meta_box() {
    add_meta_box("goal-meta-box", "Goal Extra Info", "goal_meta_box_markup", "goal", "side", "high", null);
    add_meta_box("goal-extra-meta-box", "Highlighted text", "goal_extra_meta_box_markup", "goal", "normal", "high", null);
    add_meta_box("goal-credits-extra-meta-box", "Main picture credits", "goal_credits_extra_meta_box_markup", "goal", "normal", "high", null);
}

add_action("add_meta_boxes", "goal_add_custom_meta_box");

function save_goal_meta_box($post_id, $post, $update) {
    if (!isset($_POST["meta-box-nonce"]) || !wp_verify_nonce($_POST["meta-box-nonce"], basename(__FILE__)))
        return $post_id;

    if (!current_user_can("edit_post", $post_id))
        return $post_id;

    if (defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
        return $post_id;

    $slug = "goal";

    if ($slug != $post->post_type)
        return $post_id;

    save_meta_box_field($post_id, "meta-box-identifier");
    save_meta_box_field($post_id, "meta-box-order");
    save_meta_box_field($post_id, "meta-box-highlighted");
    save_meta_box_field($post_id, "meta-box-credits");
}

add_action("save_post", "save_goal_meta_box", 10, 3);

//////////////////////////////////////////////////////////////////////////////////////////
//                              Annual Report custom meta info
//////////////////////////////////////////////////////////////////////////////////////////

function annual_report_meta_box_markup($object) {
    wp_nonce_field(basename(__FILE__), "meta-box-nonce");

    ?>
        <?= get_meta_style() ?>
        
        <div>
        	<label for="meta-box-date">Date</label>
            <input name="meta-box-date" type="text" value="<?php echo get_post_meta($object->ID, "meta-box-date", true); ?>" class="w100" />
            <label for="meta-box-url">URL</label>
            <input name="meta-box-url" type="text" value="<?php echo get_post_meta($object->ID, "meta-box-url", true); ?>" class="w100" />
        </div>
    <?php
}

function annual_report_add_custom_meta_box() {
    add_meta_box("annual_report-meta-box", "Annual Report Extra Info", "annual_report_meta_box_markup", "annual_report", "side", "high", null);
}

add_action("add_meta_boxes", "annual_report_add_custom_meta_box");

function save_annual_report_meta_box($post_id, $post, $update) {
    if (!isset($_POST["meta-box-nonce"]) || !wp_verify_nonce($_POST["meta-box-nonce"], basename(__FILE__)))
        return $post_id;

    if (!current_user_can("edit_post", $post_id))
        return $post_id;

    if (defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
        return $post_id;

    $slug = "annual_report";

    if ($slug != $post->post_type)
        return $post_id;

    save_meta_box_field($post_id, "meta-box-date");
    save_meta_box_field($post_id, "meta-box-url");
}

add_action("save_post", "save_annual_report_meta_box", 10, 3);

//////////////////////////////////////////////////////////////////////////////////////////
//                                   Page custom meta info
//////////////////////////////////////////////////////////////////////////////////////////

function page_extra_meta_box_markup($object) {
    wp_nonce_field(basename(__FILE__), "meta-box-nonce");
    
    $field_value = get_post_meta($object->ID, 'meta-box-highlighted', true);
  	
  	wp_editor($field_value, 'meta-box-highlighted');
}

function page_credits_extra_meta_box_markup($object) {
	wp_nonce_field(basename(__FILE__), "meta-box-nonce");

    ?>
        <?= get_meta_style() ?>
        <div>
            <textarea name="meta-box-credits" type="text" class="w100"><?= get_post_meta($object->ID, "meta-box-credits", true) ?></textarea>
        </div>
    <?php
}

function page_add_custom_meta_box() {
    add_meta_box("page-extra-meta-box", "Highlighted text", "page_extra_meta_box_markup", "page", "normal", "high", null);
    add_meta_box("page-credits-extra-meta-box", "Main picture credits", "page_credits_extra_meta_box_markup", "page", "normal", "high", null);
}

add_action("add_meta_boxes", "page_add_custom_meta_box");

function save_page_meta_box($post_id, $post, $update) {
    if (!isset($_POST["meta-box-nonce"]) || !wp_verify_nonce($_POST["meta-box-nonce"], basename(__FILE__)))
        return $post_id;

    if (!current_user_can("edit_post", $post_id))
        return $post_id;

    if (defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
        return $post_id;

    $slug = "page";

    if ($slug != $post->post_type)
        return $post_id;

    save_meta_box_field($post_id, "meta-box-highlighted");
    save_meta_box_field($post_id, "meta-box-credits");
}

add_action("save_post", "save_page_meta_box", 10, 3);

//////////////////////////////////////////////////////////////////////////////////////////
//                                       Query String
//////////////////////////////////////////////////////////////////////////////////////////

function add_query_vars($aVars) {
	$aVars[] = "pr";
	$aVars[] = "starts";
	$aVars[] = "ends";
	return $aVars;
}

add_filter('query_vars', 'add_query_vars');

//////////////////////////////////////////////////////////////////////////////////////////
//                                       Navigation
//////////////////////////////////////////////////////////////////////////////////////////

add_filter('nav_menu_css_class', 'special_nav_class', 10, 2);

function special_nav_class($classes, $item) {
	$pagename = get_query_var('pagename');
	$menu_item = $item->title;
	$p = get_post();
	$ancestors = get_post_ancestors($p);
	$post_type = $p->post_type;

	if ($pagename == 'sir-tim-berners-lee' || $pagename == 'history-of-the-web') {
		if ($menu_item == 'The Web') {
			$classes[] = 'current-page-ancestor';
		}
		else  if ($menu_item == 'About Us') {
			$key = array_search('current-page-ancestor', $classes); 
			
			if ($key)
				unset($classes[$key]);
		}
	}
	else if ($menu_item == 'About Us') {
		$menu_item_id = $item->object_id;
		
		if ($pagename == 'news' || in_array($menu_item_id, $ancestors) || $post_type == 'research')
			$classes[] = 'current-page-ancestor';
	}
	else if ($menu_item == 'Our Team' || $menu_item == 'Our Board') {
		$menu_item_id = $item->object_id;
		
		if (in_array($menu_item_id, $ancestors))
			$classes[] = 'current-page-ancestor';
	}
	else if ($menu_item == 'Our Work') {
		if ($post_type == 'project')
			$classes[] = 'current-page-ancestor';
	}
	else if ($menu_item == 'News and Blogs') {
		if ($post_type == 'post')
			$classes[] = 'current-page-ancestor';
	}
	else if ($menu_item == 'Our Research and Publications') {
		if ($post_type == 'research')
			$classes[] = 'current-page-ancestor';
	}
	
    return $classes;
}
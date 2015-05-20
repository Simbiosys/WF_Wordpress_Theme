<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * e.g., it puts together the home page when no home.php file exists.
 *
 * Learn more: {@link https://codex.wordpress.org/Template_Hierarchy}
 *
 * @package WordPress
 * @subpackage WF
 */
?><?php
 /*	require_once(get_stylesheet_directory().'/renderization/controller.php');

        $controller = Controller::getInstance();
        $renderer = $controller->renderer;

	get_header();
	
	echo $renderer->renderTemplate("searchpage");

	get_footer(); */
?>
<?php
/**
 * The template for displaying search results pages.
 *
 * @package WordPress
 * @subpackage WF
 */

get_header(); ?>

	<div class="container-max-height">
		<div class="container-fluid pos-content p-xxl-bottom main-content-st search-results">

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
			<h1 class="page-title"><?php printf( __( 'Search Results for: %s', 'WF' ), get_search_query() ); ?></h1>
			</header><!-- .page-header -->

			<?php
			// Start the loop.
			while ( have_posts() ) : the_post(); ?>

				<?php
				/*
				 * Run the loop for the search to output the results.
				 * If you want to overload this in a child theme then include a file
				 * called content-search.php and that will be used instead.
				 */
				get_template_part( 'content', 'search' );

			// End the loop.
			endwhile;

			// Previous/next page navigation.
			the_posts_pagination( array(
				'prev_text'          => __( 'Previous page', 'WF' ),
				'next_text'          => __( 'Next page', 'WF' ),
				'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'WF' ) . ' </span>',
			) );

		// If no content, include the "No posts found" template.
		else :
			get_template_part( 'content', 'none' );

		endif;
		?>

		</div><!-- .site-main -->
	</div><!-- .content-area -->

<?php get_footer(); ?>

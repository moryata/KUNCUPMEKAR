<?php
/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package TP Education
 * @since 1.0
 */

get_header(); ?>
<div id="content" class="site-content background-image-properties">
	<div class="container page-section">
		<div id="primary" class="content-area os-animation" data-os-animation="fadeIn">
			<main id="main" class="site-main" role="main">

			<?php while ( have_posts() ) : the_post(); 

				/**
				 * tp_education_single_content_action hook
				 *
				 * @hooked tp_education_single_content -  10
				 *
				 */
				do_action( 'tp_education_single_content_action' );

				/**
				 * tp_education_related_posts_content_action hook
				 *
				 * @hooked tp_education_related_posts_content -  10
				 *
				 */
				do_action( 'tp_education_related_posts_content_action' );

				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;

			endwhile; // End of the loop.

			/**
			 * Hook kids_education_pro_action_post_pagination
			 *  
			 * @hooked kids_education_pro_post_pagination 
			 */
			if ( has_action( 'kids_education_pro_action_post_pagination' ) ) :
				do_action( 'kids_education_pro_action_post_pagination' );
			else :
				the_post_navigation();
			endif;
			?>

			</main><!-- #main -->
		</div><!-- #primary -->

		<?php 
		if ( function_exists( 'kids_education_pro_is_sidebar_enable' ) ) :
			if ( kids_education_pro_is_sidebar_enable() ) :
				get_sidebar();
			endif;
		else :
			get_sidebar();
		endif;
		?>
	</div><!--end .page-section-->
</div><!--end .site-content-->
<?php
get_footer();

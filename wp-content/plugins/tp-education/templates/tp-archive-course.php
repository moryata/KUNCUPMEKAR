<?php
/**
 * The template for displaying archive pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package TP Education
 * @since 1.0
 */

get_header(); ?>
<div id="content" class="site-content background-image-properties">
		<main id="main" class="site-main" role="main">

			<?php
			if ( have_posts() ) : ?>
			<section id="search-course-tab" class="page-section no-padding-bottom os-animation" data-os-animation="fadeIn">
				<div class="container">
					<div class="entry-content">
						<ul id="two-column" class="course-lists three-columns os-animation">
							<?php  
							/* Start the Loop */
							$i = 1;
							while ( have_posts() ) : the_post();
								/**
								 * Hook - tp_education_archive_course_content_action.
								 *
								 * @hooked tp_education_archive_course_content -  10
								 */
								do_action( 'tp_education_archive_course_content_action' );
								if ( $i % 3 == 0 ) echo '<div class="clear"></div>';
								$i++;
							endwhile; ?>
						</ul><!-- .course-lists -->
					</div><!-- .entry-content -->
					<?php  
					if ( has_action( 'kids_education_pro_action_pagination' ) ) :
						/**
						* Hook - kids_education_pro_action_pagination.
						*
						* @hooked kids_education_pro_pagination 
						*/
						do_action( 'kids_education_pro_action_pagination' );
					else :
						the_posts_navigation();
					endif;
					?>
				</div><!-- .container -->
			</section><!-- .search-course-tab -->

		<?php
		else :

			get_template_part( 'template-parts/content', 'none' );

		endif; 
		?>

		</main><!-- #main -->

	<?php //get_sidebar(); ?>
</div><!--end .site-content-->
<?php
get_footer();

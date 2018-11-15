<?php
/**
 * Course Archive Content.
 *
 * @package TP Education
 * @since 1.0
 */

function tp_education_archive_course_content() {
	?>
	<li class="column-wrapper">
		<div class="course-item">
			<div class="image-wrapper">
				<a href="<?php the_permalink(); ?>">
					<?php  
					if ( has_post_thumbnail() ) {
						the_post_thumbnail( 'post-thumbnail', array( 'alt' => the_title_attribute( array( 'echo' => false ) ) ) );
					} else {
						echo '<img src="' . TP_EDUCATION_URL_PATH . '/assets/images/demo-300x200.jpg" alt="' . the_title_attribute( array( 'echo' => false ) ) . '">';
					}
					?>
					<div class="white-overlay"></div>
				</a>
				<a href="<?php the_permalink(); ?>" class="btn btn-blue"><?php _e( 'Learn More', 'tp-education' ); ?></a>
			</div><!-- .image-wrapper -->
			<div class="course-contents">
				<div class="course-header-contents">
					<h5 class="title">
						<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
					</h5> 
					<div class="categories">
						<b><?php esc_html_e( 'Posted In: ', 'tp-education' ); ?></b>
						<?php 
						// get all terms
						tp_education_get_terms( 'tp-course-category' );
						?>
					</div><!-- .categories -->
					<div class="tags">
						<b><?php esc_html_e( 'Tags: ', 'tp-education' ); ?></b>
						<?php 
						// get all terms
						tp_education_get_terms( 'tp-course-tag' );
						?>
					</div><!-- .tags -->
				</div><!-- .course-header-contents -->
				<div class="course-footer-contents">
					<ul>
						<li>
							<?php  
							// course type
							tp_course_type();
							?>
						</li>
						<li>
							<?php  
							// course price
							tp_course_price();
							?>
						</li>
					</ul>
				</div><!-- .course-footer-contents -->
			</div><!-- .course-contents -->
		</div><!-- .course-item -->
	</li><!-- .column-wrapper -->
<?php
}
add_action( 'tp_education_archive_course_content_action', 'tp_education_archive_course_content', 10 );

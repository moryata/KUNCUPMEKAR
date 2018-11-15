<?php
/**
 * Kids Education options
 *
 * @package Theme Palace
 * @subpackage Kids Education
 * @since Kids Education 0.1
 */

/**
 * Sidebar position
 * @return array Sidbar positions
 */
function kids_education_sidebar_position() {
  $kids_education_sidebar_position = array(
    'right-sidebar' => esc_html__( 'Right', 'kids-education' ),
    'no-sidebar'    => esc_html__( 'No Sidebar', 'kids-education' ),
  );

  $output = apply_filters( 'kids_education_sidebar_position', $kids_education_sidebar_position );

  return $output;
}

/**
 * Pagination
 * @return array site pagination options
 */
function kids_education_pagination_options() {
  $kids_education_pagination_options = array(
    'default'         => esc_html__( 'Default(Older/Newer)', 'kids-education' ),
    'numeric'         => esc_html__( 'Numeric', 'kids-education' ),
  );

  $output = apply_filters( 'kids_education_pagination_options', $kids_education_pagination_options );

  return $output;
}

/**
 * Slider
 * @return array slider options
 */
function kids_education_enable_disable_options() {
  $kids_education_enable_disable_options = array(
    'static-frontpage'  => esc_html__( 'Static Frontpage', 'kids-education' ),
    'disabled'          => esc_html__( 'Disabled', 'kids-education' ),
  );

  $output = apply_filters( 'kids_education_enable_disable_options', $kids_education_enable_disable_options );

  return $output;
}


/**
 * Returns list for recent section content type
 * @return array content type
 */
function kids_education_recent_content_type(){

    $choices        = array(
      'post'        => esc_html__( 'Post', 'kids-education' ),
    );
    $custom_choices = array();
    if ( class_exists( 'TP_Education' ) ) {
      $custom_choices = array(
        'class'       => esc_html__( 'Class', 'kids-education' ),
        'course'      => esc_html__( 'Course', 'kids-education' ),
        'event'       => esc_html__( 'Event', 'kids-education' ),
        'excursion'   => esc_html__( 'Excursion', 'kids-education' ),
      );
    }
    $choices = array_merge( $choices, $custom_choices );
    $output = apply_filters( 'kids_education_recent_content_type', $choices );
    if ( ! empty( $output ) ) {
      ksort( $output );
    }
    return $output;

}

<?php
/**
* Customizer validation functions
*
* @package Theme Palace
* @subpackage Kids Education
* @since Kids Education 0.1
*/

function kids_education_validate_long_excerpt( $validity, $value ){
       $value = intval( $value );
   if ( empty( $value ) || ! is_numeric( $value ) ) {
       $validity->add( 'required', esc_html__( 'You must supply a valid number.', 'kids-education' ) );
   } elseif ( $value < 5 ) {
       $validity->add( 'min_no_of_words', esc_html__( 'Minimum no of words is 5', 'kids-education' ) );
   } elseif ( $value > 100 ) {
       $validity->add( 'max_no_of_words', esc_html__( 'Maximum no of words is 100', 'kids-education' ) );
   }
   return $validity;
}

function kids_education_validate_slider_count( $validity, $value ){
       $value = intval( $value );
   if ( empty( $value ) || ! is_numeric( $value ) ) {
       $validity->add( 'required', esc_html__( 'You must supply a valid number.', 'kids-education' ) );
   } elseif ( $value < 1 ) {
       $validity->add( 'min_no_of_images', esc_html__( 'Minimum no of image is 1', 'kids-education' ) );
   } elseif ( $value > 5 ) {
       $validity->add( 'max_no_of_images', esc_html__( 'Maximum no of images is 5', 'kids-education' ) );
   }
   return $validity;
}

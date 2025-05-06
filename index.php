<?php
/**
 * Front to the WordPress application. This file uses an optimized loading process
 * for better performance and faster page loads.
 *
 * @package WordPress
 * @version 2.0
 */

/**
 * Tells WordPress to load the WordPress theme and output it.
 *
 * @var bool
 */
define('WP_USE_THEMES', true);

/**
 * Enable output buffering for better performance
 */
if (function_exists('ob_gzhandler') && !ini_get('zlib.output_compression')) {
    ob_start('ob_gzhandler');
} else {
    ob_start();
}

/**
 * Load the optimized WordPress bootstrap file if it exists,
 * otherwise fall back to the standard loading process
 */
if (file_exists(dirname(__FILE__) . '/wp-fast-load.php')) {
    require(dirname(__FILE__) . '/wp-fast-load.php');
} else {
    require(dirname(__FILE__) . '/wp-blog-header.php');
}

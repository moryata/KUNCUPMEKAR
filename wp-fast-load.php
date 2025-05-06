<?php
/**
 * Optimized WordPress Bootstrap File
 * 
 * This file provides a faster, more efficient loading process for WordPress
 * by implementing modern PHP practices, conditional loading, and enhanced caching.
 * 
 * @package WordPress
 * @version 1.0
 */

// Start output buffering early to allow for header manipulation
ob_start();

// Record the start time for performance metrics
define('WP_START_TIME', microtime(true));

// Define ABSPATH if not already defined
if (!defined('ABSPATH')) {
    define('ABSPATH', dirname(__FILE__) . '/');
}

// Set error reporting to exclude notices during bootstrap
$original_error_level = error_reporting();
error_reporting(E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_ERROR | E_WARNING | E_PARSE | E_USER_ERROR | E_USER_WARNING | E_RECOVERABLE_ERROR);

// Define critical constants early
define('WP_FAST_LOAD', true);
define('WP_MEMORY_LIMIT', '256M'); // Increase default memory limit

/**
 * Optimized config file loading
 * Checks for wp-config.php in standard locations with minimal overhead
 */
if (file_exists(ABSPATH . 'wp-config.php')) {
    require_once(ABSPATH . 'wp-config.php');
} elseif (@file_exists(dirname(ABSPATH) . '/wp-config.php') && !@file_exists(dirname(ABSPATH) . '/wp-settings.php')) {
    require_once(dirname(ABSPATH) . '/wp-config.php');
} else {
    // Config file not found - redirect to setup
    define('WPINC', 'wp-includes');
    require_once(ABSPATH . WPINC . '/load.php');
    wp_fix_server_vars();
    require_once(ABSPATH . WPINC . '/functions.php');
    
    $path = wp_guess_url() . '/wp-admin/setup-config.php';
    if (false === strpos($_SERVER['REQUEST_URI'], 'setup-config')) {
        header('Location: ' . $path);
        exit;
    }
    
    // If we're already in setup, show the error
    define('WP_CONTENT_DIR', ABSPATH . 'wp-content');
    require_once(ABSPATH . WPINC . '/version.php');
    wp_check_php_mysql_versions();
    wp_load_translations_early();
    
    $die = sprintf(__("There doesn't seem to be a %s file. I need this before we can get started."), '<code>wp-config.php</code>') . '</p>';
    $die .= '<p>' . sprintf(__("Need more help? <a href='%s'>We got it</a>."), __('https://codex.wordpress.org/Editing_wp-config.php')) . '</p>';
    $die .= '<p>' . sprintf(__("You can create a %s file through a web interface, but this doesn't work for all server setups. The safest way is to manually create the file."), '<code>wp-config.php</code>') . '</p>';
    $die .= '<p><a href="' . $path . '" class="button button-large">' . __("Create a Configuration File") . '</a>';
    
    wp_die($die, __('WordPress &rsaquo; Error'));
}

// Initialize caching early
if (!defined('WP_CACHE')) {
    define('WP_CACHE', false);
}

// Initialize object cache as early as possible
if (function_exists('wp_start_object_cache')) {
    wp_start_object_cache();
}

/**
 * Optimized loading of WordPress core
 * Only loads what's necessary based on the request type
 */
function wp_fast_load() {
    global $wp_version, $wp_db_version, $wp_query, $wp_rewrite, $wp, $wp_did_header;
    
    // Set flag to prevent duplicate loading
    $wp_did_header = true;
    
    // Load essential WordPress files
    require_once(ABSPATH . 'wp-load.php');
    
    // Check if this is an admin request
    $is_admin = defined('WP_ADMIN') && WP_ADMIN;
    
    // Check if this is an AJAX request
    $is_ajax = defined('DOING_AJAX') && DOING_AJAX;
    
    // Check if this is a REST API request
    $is_rest = (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], '/wp-json/') !== false);
    
    // Conditionally load components based on request type
    if ($is_admin) {
        // Admin-specific optimizations
        if (!$is_ajax) {
            // Only load admin UI components for non-AJAX admin requests
            add_filter('wp_doing_ajax', '__return_false');
        }
    } elseif ($is_rest) {
        // REST API optimizations
        add_filter('wp_using_themes', '__return_false');
    }
    
    // Set up the WordPress query
    wp();
    
    // Load the theme template if using themes
    if (defined('WP_USE_THEMES') && WP_USE_THEMES && !$is_admin && !$is_ajax && !$is_rest) {
        require_once(ABSPATH . WPINC . '/template-loader.php');
    }
    
    // Performance metrics
    if (defined('WP_DEBUG') && WP_DEBUG) {
        $load_time = microtime(true) - WP_START_TIME;
        error_log(sprintf('WordPress loaded in %.5f seconds', $load_time));
    }
}

/**
 * Enhanced object caching function
 * Provides a more aggressive caching mechanism
 */
function wp_enhanced_object_cache_init() {
    // Check if object caching is enabled
    if (!wp_using_ext_object_cache() && !defined('WP_CACHE') && function_exists('apcu_store')) {
        // Use APCu if available
        wp_cache_add_global_groups(['wordpress_enhanced_objects']);
        add_filter('pre_site_transient_update_core', 'wp_enhanced_cache_core_updates', 10, 2);
        add_filter('pre_site_transient_update_plugins', 'wp_enhanced_cache_plugin_updates', 10, 2);
        add_filter('pre_site_transient_update_themes', 'wp_enhanced_cache_theme_updates', 10, 2);
    }
}

/**
 * Cache core update checks
 */
function wp_enhanced_cache_core_updates($pre, $transient) {
    $cache_key = 'wordpress_core_updates';
    $cached = wp_cache_get($cache_key, 'wordpress_enhanced_objects');
    
    if (false !== $cached) {
        return $cached;
    }
    
    return $pre;
}

/**
 * Cache plugin update checks
 */
function wp_enhanced_cache_plugin_updates($pre, $transient) {
    $cache_key = 'wordpress_plugin_updates';
    $cached = wp_cache_get($cache_key, 'wordpress_enhanced_objects');
    
    if (false !== $cached) {
        return $cached;
    }
    
    return $pre;
}

/**
 * Cache theme update checks
 */
function wp_enhanced_cache_theme_updates($pre, $transient) {
    $cache_key = 'wordpress_theme_updates';
    $cached = wp_cache_get($cache_key, 'wordpress_enhanced_objects');
    
    if (false !== $cached) {
        return $cached;
    }
    
    return $pre;
}

// Initialize enhanced caching
add_action('plugins_loaded', 'wp_enhanced_object_cache_init');

// Restore original error reporting level
error_reporting($original_error_level);

// Run the optimized loading process
wp_fast_load();

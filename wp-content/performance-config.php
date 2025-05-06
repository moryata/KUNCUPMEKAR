<?php
/**
 * WordPress Performance Configuration
 * 
 * This file contains performance optimizations for WordPress.
 * Include this file in your wp-config.php to enable these optimizations.
 * 
 * @package WordPress
 * @version 1.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Memory Limit Settings
 * Increase memory limits for better performance
 */
define('WP_MEMORY_LIMIT', '256M');
define('WP_MAX_MEMORY_LIMIT', '512M');

/**
 * Cache Settings
 * Enable various caching mechanisms
 */
define('WP_CACHE', true);

/**
 * Database Settings
 * Optimize database connections and queries
 */
// Disable revisions to reduce database size
define('WP_POST_REVISIONS', 5);

// Set autosave interval to reduce database writes
define('AUTOSAVE_INTERVAL', 120); // 2 minutes

// Disable file editing in the admin to improve security and performance
define('DISALLOW_FILE_EDIT', true);

/**
 * Optimization Functions
 */

/**
 * Optimize WordPress Heartbeat API
 * The Heartbeat API can cause high server load
 */
function optimize_heartbeat_settings() {
    // Reduce heartbeat frequency
    wp_localize_script('heartbeat', 'heartbeatSettings', array(
        'interval' => 60, // Default is 15
    ));
}
add_action('init', 'optimize_heartbeat_settings');

/**
 * Disable Emoji Support
 * WordPress emoji support adds unnecessary JavaScript and CSS
 */
function disable_wp_emojis() {
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
    
    // Remove the tinymce emoji plugin
    add_filter('tiny_mce_plugins', 'disable_emojis_tinymce');
}
add_action('init', 'disable_wp_emojis');

/**
 * Filter function to remove the tinymce emoji plugin
 */
function disable_emojis_tinymce($plugins) {
    if (is_array($plugins)) {
        return array_diff($plugins, array('wpemoji'));
    } else {
        return array();
    }
}

/**
 * Disable XML-RPC
 * XML-RPC can be a security risk and performance drain if not used
 */
function disable_xmlrpc() {
    add_filter('xmlrpc_enabled', '__return_false');
    
    // Remove headers
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');
}
add_action('init', 'disable_xmlrpc');

/**
 * Optimize WP Query
 * Improve database query performance
 */
function optimize_wp_query($query) {
    if (!is_admin() && $query->is_main_query()) {
        // Limit post revisions in queries
        $query->set('no_found_rows', true);
        
        // Don't count posts for pagination if not needed
        if (!$query->is_search() && !is_archive()) {
            $query->set('no_found_rows', true);
        }
        
        // Optimize term queries
        $query->set('update_post_term_cache', false);
        
        // Only get the fields you need
        if ($query->is_archive() || $query->is_search()) {
            $query->set('fields', 'ids');
        }
    }
    
    return $query;
}
add_action('pre_get_posts', 'optimize_wp_query');

/**
 * Optimize Script Loading
 * Improve script and style loading
 */
function optimize_script_loading() {
    // Move jQuery to the footer
    if (!is_admin()) {
        wp_scripts()->add_data('jquery', 'group', 1);
        wp_scripts()->add_data('jquery-core', 'group', 1);
        wp_scripts()->add_data('jquery-migrate', 'group', 1);
    }
}
add_action('wp_enqueue_scripts', 'optimize_script_loading');

/**
 * Remove Query Strings From Static Resources
 * Improves caching of static resources
 */
function remove_query_strings() {
    if (!is_admin()) {
        add_filter('script_loader_src', 'remove_query_strings_split', 15);
        add_filter('style_loader_src', 'remove_query_strings_split', 15);
    }
}
add_action('init', 'remove_query_strings');

/**
 * Helper function to remove query strings
 */
function remove_query_strings_split($src) {
    if (strpos($src, '?ver=')) {
        $src = remove_query_arg('ver', $src);
    }
    return $src;
}

/**
 * Disable Self Pingbacks
 * WordPress can ping itself when linking to its own content
 */
function disable_self_pingbacks(&$links) {
    $home = get_option('home');
    foreach ($links as $l => $link) {
        if (0 === strpos($link, $home)) {
            unset($links[$l]);
        }
    }
}
add_action('pre_ping', 'disable_self_pingbacks');

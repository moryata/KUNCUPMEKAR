<?php
/**
 * WordPress Asset Optimizer
 * 
 * This file contains optimizations for WordPress asset loading (CSS, JS).
 * 
 * @package WordPress
 * @version 1.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Asset Optimizer Class
 * Provides methods to optimize asset loading
 */
class WP_Asset_Optimizer {
    /**
     * Instance of this class
     */
    private static $instance = null;
    
    /**
     * Scripts to defer
     */
    private $defer_scripts = [];
    
    /**
     * Scripts to async
     */
    private $async_scripts = [];
    
    /**
     * Get the singleton instance
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Constructor
     */
    private function __construct() {
        // Initialize hooks
        $this->init_hooks();
        
        // Set default scripts to defer/async
        $this->set_default_optimizations();
    }
    
    /**
     * Initialize hooks
     */
    private function init_hooks() {
        // Optimize script loading
        add_filter('script_loader_tag', [$this, 'optimize_script_loading'], 10, 3);
        
        // Optimize style loading
        add_filter('style_loader_tag', [$this, 'optimize_style_loading'], 10, 4);
        
        // Remove query strings from static resources
        add_filter('script_loader_src', [$this, 'remove_query_strings'], 15);
        add_filter('style_loader_src', [$this, 'remove_query_strings'], 15);
        
        // Optimize emoji loading
        add_action('init', [$this, 'disable_emojis']);
        
        // Optimize wp-embed
        add_action('init', [$this, 'disable_embeds']);
        
        // Move jQuery to footer
        add_action('wp_enqueue_scripts', [$this, 'move_jquery_to_footer']);
        
        // Preload critical assets
        add_action('wp_head', [$this, 'preload_critical_assets'], 1);
    }
    
    /**
     * Set default optimizations
     */
    private function set_default_optimizations() {
        // Scripts to defer (non-critical scripts)
        $this->defer_scripts = [
            'comment-reply',
            'wp-embed',
            'wp-emoji',
            'regenerator-runtime',
        ];
        
        // Scripts to load asynchronously
        $this->async_scripts = [
            'google-recaptcha',
            'google-analytics',
            'facebook-pixel',
        ];
    }
    
    /**
     * Optimize script loading with defer/async attributes
     */
    public function optimize_script_loading($tag, $handle, $src) {
        // Skip optimization in admin
        if (is_admin()) {
            return $tag;
        }
        
        // Add defer attribute
        if (in_array($handle, $this->defer_scripts)) {
            return str_replace(' src', ' defer src', $tag);
        }
        
        // Add async attribute
        if (in_array($handle, $this->async_scripts)) {
            return str_replace(' src', ' async src', $tag);
        }
        
        return $tag;
    }
    
    /**
     * Optimize style loading
     */
    public function optimize_style_loading($html, $handle, $href, $media) {
        // Skip optimization in admin
        if (is_admin()) {
            return $html;
        }
        
        // Use preload for critical CSS
        if ($handle === 'main-style' || $handle === 'theme-style') {
            $html = "<link rel='preload' as='style' onload='this.onload=null;this.rel=\"stylesheet\"' id='$handle-css' href='$href' type='text/css' media='$media' />";
            $html .= "<noscript><link rel='stylesheet' id='$handle-css' href='$href' type='text/css' media='$media' /></noscript>";
            return $html;
        }
        
        return $html;
    }
    
    /**
     * Remove query strings from static resources
     */
    public function remove_query_strings($src) {
        // Skip admin resources
        if (is_admin()) {
            return $src;
        }
        
        // Remove version query string
        if (strpos($src, '?ver=') || strpos($src, '&ver=')) {
            $src = remove_query_arg('ver', $src);
        }
        
        return $src;
    }
    
    /**
     * Disable WordPress emojis
     */
    public function disable_emojis() {
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('admin_print_scripts', 'print_emoji_detection_script');
        remove_action('wp_print_styles', 'print_emoji_styles');
        remove_action('admin_print_styles', 'print_emoji_styles');
        remove_filter('the_content_feed', 'wp_staticize_emoji');
        remove_filter('comment_text_rss', 'wp_staticize_emoji');
        remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
        
        // Remove the tinymce emoji plugin
        add_filter('tiny_mce_plugins', function($plugins) {
            if (is_array($plugins)) {
                return array_diff($plugins, ['wpemoji']);
            }
            return [];
        });
    }
    
    /**
     * Disable WordPress embeds
     */
    public function disable_embeds() {
        // Remove the embed script
        wp_deregister_script('wp-embed');
        
        // Remove oEmbed discovery links
        remove_action('wp_head', 'wp_oembed_add_discovery_links');
        remove_action('wp_head', 'wp_oembed_add_host_js');
        
        // Remove REST API endpoint
        remove_action('rest_api_init', 'wp_oembed_register_route');
        
        // Remove oEmbed-specific JavaScript from the front-end and back-end
        add_filter('embed_oembed_discover', '__return_false');
        
        // Remove filter of the oEmbed result
        remove_filter('oembed_dataparse', 'wp_filter_oembed_result');
        
        // Remove oEmbed auto discovery links
        remove_filter('pre_oembed_result', 'wp_filter_pre_oembed_result');
    }
    
    /**
     * Move jQuery to footer
     */
    public function move_jquery_to_footer() {
        // Skip in admin
        if (is_admin()) {
            return;
        }
        
        // Move jQuery core to footer
        wp_scripts()->add_data('jquery', 'group', 1);
        wp_scripts()->add_data('jquery-core', 'group', 1);
        wp_scripts()->add_data('jquery-migrate', 'group', 1);
    }
    
    /**
     * Preload critical assets
     */
    public function preload_critical_assets() {
        // Skip in admin
        if (is_admin()) {
            return;
        }
        
        // Preload main font
        echo '<link rel="preload" href="' . get_template_directory_uri() . '/assets/fonts/main-font.woff2" as="font" type="font/woff2" crossorigin>';
        
        // Preload main CSS
        echo '<link rel="preload" href="' . get_template_directory_uri() . '/style.css" as="style">';
        
        // Preload main JS
        echo '<link rel="preload" href="' . get_template_directory_uri() . '/assets/js/main.js" as="script">';
    }
}

// Initialize the asset optimizer
WP_Asset_Optimizer::get_instance();

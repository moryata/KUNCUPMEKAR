<?php
/**
 * WordPress Cache Optimizer
 * 
 * This file contains optimizations for WordPress caching.
 * 
 * @package WordPress
 * @version 1.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Cache Optimizer Class
 * Provides methods to optimize WordPress caching
 */
class WP_Cache_Optimizer {
    /**
     * Instance of this class
     */
    private static $instance = null;
    
    /**
     * Cache directory
     */
    private $cache_dir;
    
    /**
     * Cache expiration time (in seconds)
     */
    private $cache_expiration = 3600; // 1 hour
    
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
        // Set cache directory
        $this->cache_dir = WP_CONTENT_DIR . '/cache/page-cache';
        
        // Create cache directory if it doesn't exist
        if (!file_exists($this->cache_dir)) {
            wp_mkdir_p($this->cache_dir);
        }
        
        // Initialize hooks
        $this->init_hooks();
    }
    
    /**
     * Initialize hooks
     */
    private function init_hooks() {
        // Cache management
        add_action('init', [$this, 'start_page_cache']);
        add_action('shutdown', [$this, 'end_page_cache']);
        
        // Clear cache on content updates
        add_action('save_post', [$this, 'clear_post_cache']);
        add_action('deleted_post', [$this, 'clear_post_cache']);
        add_action('comment_post', [$this, 'clear_post_cache']);
        add_action('wp_set_comment_status', [$this, 'clear_post_cache']);
        
        // Schedule cache cleanup
        if (!wp_next_scheduled('wp_cache_cleanup_event')) {
            wp_schedule_event(time(), 'daily', 'wp_cache_cleanup_event');
        }
        add_action('wp_cache_cleanup_event', [$this, 'cleanup_expired_cache']);
        
        // Optimize transients
        add_action('init', [$this, 'optimize_transients']);
    }
    
    /**
     * Start page caching
     */
    public function start_page_cache() {
        // Skip caching for specific conditions
        if ($this->should_skip_cache()) {
            return;
        }
        
        // Check if page is cached
        $cache_file = $this->get_cache_file_path();
        
        if (file_exists($cache_file) && (time() - filemtime($cache_file) < $this->cache_expiration)) {
            // Serve from cache
            $this->serve_from_cache($cache_file);
        }
        
        // Start output buffering for new cache
        ob_start([$this, 'cache_output_callback']);
    }
    
    /**
     * End page caching
     */
    public function end_page_cache() {
        // Skip if not buffering or should skip cache
        if (!ob_get_level() || $this->should_skip_cache()) {
            return;
        }
        
        // End buffering
        ob_end_flush();
    }
    
    /**
     * Cache output callback
     */
    public function cache_output_callback($buffer) {
        // Skip if buffer is empty or error page
        if (empty($buffer) || is_404() || is_search()) {
            return $buffer;
        }
        
        // Save buffer to cache file
        $cache_file = $this->get_cache_file_path();
        file_put_contents($cache_file, $buffer);
        
        return $buffer;
    }
    
    /**
     * Serve page from cache
     */
    private function serve_from_cache($cache_file) {
        // Set appropriate headers
        header('X-WP-Cache: HIT');
        
        // Output cached content
        readfile($cache_file);
        
        // Exit to prevent further processing
        exit;
    }
    
    /**
     * Check if caching should be skipped
     */
    private function should_skip_cache() {
        // Skip for logged in users
        if (is_user_logged_in()) {
            return true;
        }
        
        // Skip for POST requests
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return true;
        }
        
        // Skip for admin, login, or dynamic pages
        if (is_admin() || is_404() || is_search() || is_feed()) {
            return true;
        }
        
        // Skip if query parameters exist (except pagination)
        $query_string = $_SERVER['QUERY_STRING'];
        if (!empty($query_string) && !preg_match('/^(page|paged)=\d+$/', $query_string)) {
            return true;
        }
        
        // Skip for WooCommerce cart, checkout, or account pages
        if (function_exists('is_woocommerce') && (is_cart() || is_checkout() || is_account_page())) {
            return true;
        }
        
        // Skip if specific cookies exist
        foreach ($_COOKIE as $key => $value) {
            if (strpos($key, 'wordpress_logged_in') === 0 || strpos($key, 'comment_author') === 0) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Get cache file path for current request
     */
    private function get_cache_file_path() {
        // Generate unique cache key based on URL
        $cache_key = md5($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
        
        // Add mobile detection
        if ($this->is_mobile()) {
            $cache_key .= '_mobile';
        }
        
        return $this->cache_dir . '/' . $cache_key . '.html';
    }
    
    /**
     * Check if current request is from a mobile device
     */
    private function is_mobile() {
        if (empty($_SERVER['HTTP_USER_AGENT'])) {
            return false;
        }
        
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        
        $mobile_agents = [
            'Android', 'iPhone', 'iPod', 'iPad', 'Windows Phone', 'BlackBerry', 'Opera Mini', 'Mobile', 'Tablet'
        ];
        
        foreach ($mobile_agents as $agent) {
            if (stripos($user_agent, $agent) !== false) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Clear cache for a specific post
     */
    public function clear_post_cache($post_id) {
        // Get post
        $post = get_post($post_id);
        
        if (!$post) {
            return;
        }
        
        // Clear post URL cache
        $post_url = get_permalink($post_id);
        $cache_key = md5(parse_url($post_url, PHP_URL_HOST) . parse_url($post_url, PHP_URL_PATH));
        
        $cache_files = [
            $this->cache_dir . '/' . $cache_key . '.html',
            $this->cache_dir . '/' . $cache_key . '_mobile.html'
        ];
        
        foreach ($cache_files as $file) {
            if (file_exists($file)) {
                unlink($file);
            }
        }
        
        // Clear home page cache
        $home_url = home_url();
        $home_cache_key = md5(parse_url($home_url, PHP_URL_HOST) . parse_url($home_url, PHP_URL_PATH));
        
        $home_cache_files = [
            $this->cache_dir . '/' . $home_cache_key . '.html',
            $this->cache_dir . '/' . $home_cache_key . '_mobile.html'
        ];
        
        foreach ($home_cache_files as $file) {
            if (file_exists($file)) {
                unlink($file);
            }
        }
    }
    
    /**
     * Clean up expired cache files
     */
    public function cleanup_expired_cache() {
        // Skip if cache directory doesn't exist
        if (!file_exists($this->cache_dir)) {
            return;
        }
        
        // Get all cache files
        $files = glob($this->cache_dir . '/*.html');
        
        if (!$files) {
            return;
        }
        
        // Current time
        $now = time();
        
        // Delete expired files
        foreach ($files as $file) {
            if ($now - filemtime($file) > $this->cache_expiration) {
                unlink($file);
            }
        }
    }
    
    /**
     * Optimize transients
     */
    public function optimize_transients() {
        global $wpdb;
        
        // Clean up expired transients weekly
        if (!wp_next_scheduled('wp_cleanup_transients_event')) {
            wp_schedule_event(time(), 'weekly', 'wp_cleanup_transients_event');
        }
        
        add_action('wp_cleanup_transients_event', function() use ($wpdb) {
            // Delete expired transients
            $wpdb->query(
                "DELETE FROM $wpdb->options 
                WHERE option_name LIKE '%_transient_timeout_%' 
                AND option_value < " . time()
            );
            
            $wpdb->query(
                "DELETE FROM $wpdb->options 
                WHERE option_name LIKE '%_transient_%' 
                AND option_name NOT LIKE '%_transient_timeout_%' 
                AND option_name NOT IN (
                    SELECT CONCAT('_transient_', SUBSTRING(option_name, 20)) 
                    FROM $wpdb->options 
                    WHERE option_name LIKE '%_transient_timeout_%'
                )"
            );
        });
    }
}

// Initialize the cache optimizer
WP_Cache_Optimizer::get_instance();

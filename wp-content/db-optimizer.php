<?php
/**
 * WordPress Database Optimizer
 * 
 * This file contains optimizations for WordPress database operations.
 * 
 * @package WordPress
 * @version 1.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Database Optimizer Class
 * Provides methods to optimize database operations
 */
class WP_DB_Optimizer {
    /**
     * Instance of this class
     */
    private static $instance = null;
    
    /**
     * Cache of prepared queries
     */
    private $query_cache = [];
    
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
    }
    
    /**
     * Initialize hooks
     */
    private function init_hooks() {
        // Hook into database queries
        add_filter('query', [$this, 'optimize_query']);
        
        // Hook into post queries
        add_action('pre_get_posts', [$this, 'optimize_post_query']);
        
        // Optimize term queries
        add_action('pre_get_terms', [$this, 'optimize_term_query']);
        
        // Optimize comment queries
        add_filter('comments_clauses', [$this, 'optimize_comment_query']);
        
        // Schedule database optimization
        if (!wp_next_scheduled('wp_db_optimization_event')) {
            wp_schedule_event(time(), 'daily', 'wp_db_optimization_event');
        }
        add_action('wp_db_optimization_event', [$this, 'run_scheduled_optimization']);
    }
    
    /**
     * Optimize database queries
     */
    public function optimize_query($query) {
        global $wpdb;
        
        // Skip if empty query
        if (empty($query)) {
            return $query;
        }
        
        // Skip admin queries
        if (is_admin()) {
            return $query;
        }
        
        // Cache query if it's a SELECT
        if (0 === stripos(trim($query), 'SELECT')) {
            $query_hash = md5($query);
            
            if (isset($this->query_cache[$query_hash])) {
                // Return cached query result
                return $this->query_cache[$query_hash];
            }
            
            // Store in cache for future use
            $this->query_cache[$query_hash] = $query;
        }
        
        // Optimize COUNT queries
        if (stripos($query, 'SELECT COUNT(*)') !== false) {
            // Use SQL_CALC_FOUND_ROWS for better performance
            $query = str_replace('SELECT COUNT(*)', 'SELECT SQL_CALC_FOUND_ROWS', $query);
        }
        
        return $query;
    }
    
    /**
     * Optimize post queries
     */
    public function optimize_post_query($query) {
        // Skip if in admin or not main query
        if (is_admin() || !$query->is_main_query()) {
            return;
        }
        
        // Don't count rows if not needed
        if (!$query->is_search() && !is_archive()) {
            $query->set('no_found_rows', true);
        }
        
        // Optimize meta queries
        if ($query->get('meta_query')) {
            $meta_query = $query->get('meta_query');
            
            // Use EXISTS instead of JOIN for meta queries when possible
            if (is_array($meta_query) && count($meta_query) === 1 && isset($meta_query[0]['key'])) {
                $query->set('update_post_meta_cache', true);
            }
        }
        
        // Optimize taxonomy queries
        if ($query->get('tax_query')) {
            $tax_query = $query->get('tax_query');
            
            // Use EXISTS instead of JOIN for taxonomy queries when possible
            if (is_array($tax_query) && count($tax_query) === 1 && isset($tax_query[0]['taxonomy'])) {
                $query->set('update_post_term_cache', true);
            }
        }
    }
    
    /**
     * Optimize term queries
     */
    public function optimize_term_query($query) {
        // Skip admin queries
        if (is_admin()) {
            return;
        }
        
        // Set cache results to true for better performance
        $query->query_vars['cache_results'] = true;
        
        // Only get the fields needed
        if (!isset($query->query_vars['fields']) || $query->query_vars['fields'] === 'all') {
            $query->query_vars['fields'] = 'id=>name';
        }
    }
    
    /**
     * Optimize comment queries
     */
    public function optimize_comment_query($clauses) {
        // Skip admin queries
        if (is_admin()) {
            return $clauses;
        }
        
        // Add index hints for better performance
        if (isset($clauses['join']) && strpos($clauses['join'], 'wp_comments') !== false) {
            $clauses['join'] = str_replace(
                'JOIN wp_comments',
                'JOIN wp_comments USE INDEX (comment_post_ID)',
                $clauses['join']
            );
        }
        
        return $clauses;
    }
    
    /**
     * Run scheduled database optimization
     */
    public function run_scheduled_optimization() {
        global $wpdb;
        
        // Only run if not in admin
        if (is_admin()) {
            return;
        }
        
        // Optimize tables
        $tables = $wpdb->get_results('SHOW TABLES', ARRAY_N);
        
        if ($tables) {
            foreach ($tables as $table) {
                $table_name = $table[0];
                
                // Skip non-WordPress tables
                if (strpos($table_name, $wpdb->prefix) !== 0) {
                    continue;
                }
                
                // Optimize table
                $wpdb->query("OPTIMIZE TABLE $table_name");
            }
        }
        
        // Clear expired transients
        $wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE '%_transient_%' AND option_value < " . time());
    }
}

// Initialize the database optimizer
WP_DB_Optimizer::get_instance();

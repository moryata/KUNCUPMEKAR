<?php
/**
 * WordPress Performance Loader
 * 
 * This file loads all performance optimizations.
 * Include this file in your wp-config.php to enable all optimizations.
 * 
 * @package WordPress
 * @version 1.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define performance constants
if (!defined('WP_PERFORMANCE_OPTIMIZATION')) {
    define('WP_PERFORMANCE_OPTIMIZATION', true);
}

// Set memory limits
if (!defined('WP_MEMORY_LIMIT')) {
    define('WP_MEMORY_LIMIT', '256M');
}

if (!defined('WP_MAX_MEMORY_LIMIT')) {
    define('WP_MAX_MEMORY_LIMIT', '512M');
}

/**
 * Performance Loader Class
 * Loads all performance optimization modules
 */
class WP_Performance_Loader {
    /**
     * Instance of this class
     */
    private static $instance = null;
    
    /**
     * Optimization modules
     */
    private $modules = [];
    
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
        // Define optimization modules
        $this->modules = [
            'performance-config' => WP_CONTENT_DIR . '/performance-config.php',
            'db-optimizer' => WP_CONTENT_DIR . '/db-optimizer.php',
            'asset-optimizer' => WP_CONTENT_DIR . '/asset-optimizer.php',
            'cache-optimizer' => WP_CONTENT_DIR . '/cache-optimizer.php',
        ];
        
        // Load modules
        $this->load_modules();
        
        // Initialize hooks
        $this->init_hooks();
    }
    
    /**
     * Load optimization modules
     */
    private function load_modules() {
        foreach ($this->modules as $module => $path) {
            if (file_exists($path)) {
                require_once $path;
            }
        }
    }
    
    /**
     * Initialize hooks
     */
    private function init_hooks() {
        // Add performance headers
        add_action('send_headers', [$this, 'add_performance_headers']);
        
        // Add performance admin menu
        add_action('admin_menu', [$this, 'add_performance_menu']);
        
        // Add performance dashboard widget
        add_action('wp_dashboard_setup', [$this, 'add_performance_dashboard_widget']);
    }
    
    /**
     * Add performance headers
     */
    public function add_performance_headers() {
        // Add performance headers
        header('X-WP-Optimized: true');
        
        // Add cache control headers for better browser caching
        if (!is_user_logged_in()) {
            header('Cache-Control: public, max-age=3600');
        } else {
            header('Cache-Control: no-cache, must-revalidate, max-age=0');
        }
    }
    
    /**
     * Add performance admin menu
     */
    public function add_performance_menu() {
        add_management_page(
            'Performance Optimization',
            'Performance',
            'manage_options',
            'wp-performance',
            [$this, 'render_performance_page']
        );
    }
    
    /**
     * Render performance admin page
     */
    public function render_performance_page() {
        ?>
        <div class="wrap">
            <h1>WordPress Performance Optimization</h1>
            
            <div class="notice notice-info">
                <p>Performance optimization is active. The following modules are loaded:</p>
            </div>
            
            <table class="widefat" style="margin-top: 20px;">
                <thead>
                    <tr>
                        <th>Module</th>
                        <th>Status</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($this->modules as $module => $path): ?>
                        <tr>
                            <td><?php echo ucwords(str_replace('-', ' ', $module)); ?></td>
                            <td><?php echo file_exists($path) ? '<span style="color: green;">Active</span>' : '<span style="color: red;">Inactive</span>'; ?></td>
                            <td>
                                <?php
                                switch ($module) {
                                    case 'performance-config':
                                        echo 'Core performance configuration settings';
                                        break;
                                    case 'db-optimizer':
                                        echo 'Database query optimization';
                                        break;
                                    case 'asset-optimizer':
                                        echo 'CSS and JavaScript optimization';
                                        break;
                                    case 'cache-optimizer':
                                        echo 'Page and object caching';
                                        break;
                                    default:
                                        echo 'Performance optimization module';
                                }
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <h2 style="margin-top: 30px;">Performance Tips</h2>
            <ul style="list-style-type: disc; margin-left: 20px;">
                <li>Use a caching plugin like WP Super Cache or W3 Total Cache</li>
                <li>Optimize images before uploading</li>
                <li>Use a CDN for static assets</li>
                <li>Keep plugins to a minimum</li>
                <li>Use a lightweight theme</li>
                <li>Update WordPress, themes, and plugins regularly</li>
            </ul>
        </div>
        <?php
    }
    
    /**
     * Add performance dashboard widget
     */
    public function add_performance_dashboard_widget() {
        wp_add_dashboard_widget(
            'wp_performance_dashboard_widget',
            'Performance Optimization',
            [$this, 'render_performance_dashboard_widget']
        );
    }
    
    /**
     * Render performance dashboard widget
     */
    public function render_performance_dashboard_widget() {
        // Count active modules
        $active_modules = 0;
        foreach ($this->modules as $path) {
            if (file_exists($path)) {
                $active_modules++;
            }
        }
        
        echo '<p>Performance optimization is active with ' . $active_modules . ' modules.</p>';
        echo '<p><a href="' . admin_url('tools.php?page=wp-performance') . '">View Performance Settings</a></p>';
    }
}

// Initialize the performance loader
WP_Performance_Loader::get_instance();

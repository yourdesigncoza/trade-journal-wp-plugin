<?php
/**
 * Plugin Name: Trade Journal WP
 * Plugin URI: https://github.com/yourname/trade-journal-wp
 * Description: A comprehensive trading journal plugin with performance analytics, trade tracking, and detailed reporting features.
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://yourwebsite.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: trade-journal-wp
 * Domain Path: /languages
 * Requires at least: 5.0
 * Tested up to: 6.4
 * Requires PHP: 7.4
 * Network: false
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define plugin constants
define( 'TRADE_JOURNAL_WP_VERSION', '1.0.0' );
define( 'TRADE_JOURNAL_WP_PLUGIN_FILE', __FILE__ );
define( 'TRADE_JOURNAL_WP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'TRADE_JOURNAL_WP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'TRADE_JOURNAL_WP_TABLE_NAME', 'trade_journal_entries' );

/**
 * Main plugin class
 */
class Trade_Journal_WP {

    /**
     * Plugin instance
     */
    private static $instance = null;

    /**
     * Get plugin instance
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        $this->init();
    }

    /**
     * Initialize plugin
     */
    private function init() {
        // Load plugin textdomain for translations
        add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
        
        // Plugin activation/deactivation hooks
        register_activation_hook( TRADE_JOURNAL_WP_PLUGIN_FILE, array( $this, 'activate' ) );
        register_deactivation_hook( TRADE_JOURNAL_WP_PLUGIN_FILE, array( $this, 'deactivate' ) );
        
        // Initialize plugin components
        add_action( 'init', array( $this, 'init_components' ) );
        
        // Enqueue scripts and styles
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_assets' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
        
        // Admin menu
        add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
        
        // AJAX handlers
        add_action( 'wp_ajax_trade_journal_get_trades', array( $this, 'ajax_get_trades' ) );
        add_action( 'wp_ajax_trade_journal_save_trade', array( $this, 'ajax_save_trade' ) );
        add_action( 'wp_ajax_trade_journal_update_trade', array( $this, 'ajax_update_trade' ) );
        add_action( 'wp_ajax_trade_journal_delete_trade', array( $this, 'ajax_delete_trade' ) );
        add_action( 'wp_ajax_trade_journal_test_db_connection', array( $this, 'ajax_test_db_connection' ) );
        
        // Load required files
        $this->load_includes();
    }

    /**
     * Load plugin textdomain
     */
    public function load_textdomain() {
        load_plugin_textdomain( 'trade-journal-wp', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
    }

    /**
     * Plugin activation
     */
    public function activate() {
        $this->create_tables();
        $this->set_default_options();
        flush_rewrite_rules();
    }

    /**
     * Plugin deactivation
     */
    public function deactivate() {
        flush_rewrite_rules();
    }

    /**
     * Create database tables
     */
    private function create_tables() {
        global $wpdb;

        $table_name = $wpdb->prefix . TRADE_JOURNAL_WP_TABLE_NAME;

        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id varchar(255) NOT NULL,
            market enum('XAUUSD','EU','GU','UJ','US30','NAS100') NOT NULL,
            session enum('LO','NY','AS') NOT NULL,
            date date NOT NULL,
            time time DEFAULT NULL,
            direction enum('LONG','SHORT') NOT NULL,
            entry_price decimal(10,5) DEFAULT NULL,
            exit_price decimal(10,5) DEFAULT NULL,
            outcome enum('W','L','BE','C') DEFAULT NULL,
            pl_percent decimal(10,2) DEFAULT NULL,
            rr decimal(10,2) DEFAULT NULL,
            tf json DEFAULT NULL,
            chart_htf text DEFAULT NULL,
            chart_ltf text DEFAULT NULL,
            comments text DEFAULT NULL,
            created_at timestamp DEFAULT CURRENT_TIMESTAMP,
            updated_at timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY idx_date (date),
            KEY idx_market (market),
            KEY idx_outcome (outcome),
            KEY idx_created_at (created_at)
        ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }

    /**
     * Set default plugin options
     */
    private function set_default_options() {
        add_option( 'trade_journal_wp_version', TRADE_JOURNAL_WP_VERSION );
        add_option( 'trade_journal_wp_settings', array(
            'markets' => array( 'XAUUSD', 'EU', 'GU', 'UJ', 'US30', 'NAS100' ),
            'sessions' => array( 'LO', 'NY', 'AS' ),
            'outcomes' => array( 'W', 'L', 'BE', 'C' ),
            'directions' => array( 'LONG', 'SHORT' ),
            'timeframes' => array( '5m', '15m', '30m', '1H', '4H', '1D' ),
        ) );
    }

    /**
     * Initialize plugin components
     */
    public function init_components() {
        // Register shortcodes
        add_shortcode( 'trade_journal_dashboard', array( $this, 'shortcode_dashboard' ) );
        add_shortcode( 'trade_journal_add', array( $this, 'shortcode_add_form' ) );
        add_shortcode( 'trade_journal_list', array( $this, 'shortcode_trade_list' ) );
        add_shortcode( 'trade_journal_stats', array( $this, 'shortcode_stats' ) );
        add_shortcode( 'trade_journal_checklist', array( $this, 'shortcode_checklist' ) );
    }

    /**
     * Load required files
     */
    private function load_includes() {
        require_once TRADE_JOURNAL_WP_PLUGIN_DIR . 'includes/class-trade-journal-database.php';
        require_once TRADE_JOURNAL_WP_PLUGIN_DIR . 'includes/class-trade-journal-shortcodes.php';
        require_once TRADE_JOURNAL_WP_PLUGIN_DIR . 'includes/class-trade-journal-admin.php';
    }

    /**
     * Enqueue frontend assets
     */
    public function enqueue_frontend_assets() {
        // Only load on pages with shortcodes or specific pages
        global $post;
        
        if ( ! is_a( $post, 'WP_Post' ) ) {
            return;
        }

        $has_shortcode = (
            has_shortcode( $post->post_content, 'trade_journal_dashboard' ) ||
            has_shortcode( $post->post_content, 'trade_journal_add' ) ||
            has_shortcode( $post->post_content, 'trade_journal_list' ) ||
            has_shortcode( $post->post_content, 'trade_journal_stats' )
        );

        if ( ! $has_shortcode ) {
            return;
        }

        // Enqueue styles
        wp_enqueue_style(
            'trade-journal-wp-frontend',
            TRADE_JOURNAL_WP_PLUGIN_URL . 'assets/css/frontend.css',
            array(),
            TRADE_JOURNAL_WP_VERSION
        );

        // Enqueue Bootstrap if not already loaded
        if ( ! wp_style_is( 'bootstrap', 'enqueued' ) ) {
            wp_enqueue_style(
                'trade-journal-wp-bootstrap',
                'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css',
                array(),
                '5.3.2'
            );
        }

        // Enqueue Font Awesome if not already loaded
        if ( ! wp_style_is( 'font-awesome', 'enqueued' ) ) {
            wp_enqueue_style(
                'trade-journal-wp-fontawesome',
                'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css',
                array(),
                '6.4.0'
            );
        }

        // Enqueue scripts
        wp_enqueue_script( 'jquery' );
        
        if ( ! wp_script_is( 'bootstrap', 'enqueued' ) ) {
            wp_enqueue_script(
                'trade-journal-wp-bootstrap',
                'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js',
                array( 'jquery' ),
                '5.3.2',
                true
            );
        }

        wp_enqueue_script(
            'trade-journal-wp-frontend',
            TRADE_JOURNAL_WP_PLUGIN_URL . 'assets/js/frontend.js',
            array( 'jquery' ),
            TRADE_JOURNAL_WP_VERSION,
            true
        );

        // Localize script for AJAX
        wp_localize_script( 'trade-journal-wp-frontend', 'tradeJournalWP', array(
            'ajaxUrl' => admin_url( 'admin-ajax.php' ),
            'nonce' => wp_create_nonce( 'trade_journal_wp_nonce' ),
            'strings' => array(
                'confirmDelete' => __( 'Are you sure you want to delete this trade?', 'trade-journal-wp' ),
                'saveSuccess' => __( 'Trade saved successfully!', 'trade-journal-wp' ),
                'saveFailed' => __( 'Failed to save trade. Please try again.', 'trade-journal-wp' ),
                'deleteSuccess' => __( 'Trade deleted successfully!', 'trade-journal-wp' ),
                'deleteFailed' => __( 'Failed to delete trade. Please try again.', 'trade-journal-wp' ),
            ),
        ) );
    }

    /**
     * Enqueue admin assets
     */
    public function enqueue_admin_assets( $hook ) {
        // Only load on plugin admin pages
        if ( strpos( $hook, 'trade-journal-wp' ) === false ) {
            return;
        }

        wp_enqueue_style(
            'trade-journal-wp-admin',
            TRADE_JOURNAL_WP_PLUGIN_URL . 'assets/css/admin.css',
            array(),
            TRADE_JOURNAL_WP_VERSION
        );

        wp_enqueue_script(
            'trade-journal-wp-admin',
            TRADE_JOURNAL_WP_PLUGIN_URL . 'assets/js/admin.js',
            array( 'jquery' ),
            TRADE_JOURNAL_WP_VERSION,
            true
        );
    }

    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_menu_page(
            __( 'Trade Journal', 'trade-journal-wp' ),
            __( 'Trade Journal', 'trade-journal-wp' ),
            'manage_options',
            'trade-journal-wp',
            array( $this, 'admin_page' ),
            'dashicons-chart-line',
            30
        );

        add_submenu_page(
            'trade-journal-wp',
            __( 'All Trades', 'trade-journal-wp' ),
            __( 'All Trades', 'trade-journal-wp' ),
            'manage_options',
            'trade-journal-wp',
            array( $this, 'admin_page' )
        );

        add_submenu_page(
            'trade-journal-wp',
            __( 'Add New Trade', 'trade-journal-wp' ),
            __( 'Add New Trade', 'trade-journal-wp' ),
            'manage_options',
            'trade-journal-wp-add',
            array( $this, 'admin_add_page' )
        );

        add_submenu_page(
            'trade-journal-wp',
            __( 'Settings', 'trade-journal-wp' ),
            __( 'Settings', 'trade-journal-wp' ),
            'manage_options',
            'trade-journal-wp-settings',
            array( $this, 'admin_settings_page' )
        );
    }

    /**
     * Admin page callback
     */
    public function admin_page() {
        include TRADE_JOURNAL_WP_PLUGIN_DIR . 'views/admin/trades-list.php';
    }

    /**
     * Admin add page callback
     */
    public function admin_add_page() {
        include TRADE_JOURNAL_WP_PLUGIN_DIR . 'views/admin/add-trade.php';
    }

    /**
     * Admin settings page callback
     */
    public function admin_settings_page() {
        include TRADE_JOURNAL_WP_PLUGIN_DIR . 'views/admin/settings.php';
    }

    // Shortcode methods (delegated to shortcode class)
    public function shortcode_dashboard( $atts ) {
        return Trade_Journal_Shortcodes::dashboard( $atts );
    }

    public function shortcode_add_form( $atts ) {
        return Trade_Journal_Shortcodes::add_form( $atts );
    }

    public function shortcode_trade_list( $atts ) {
        return Trade_Journal_Shortcodes::trade_list( $atts );
    }

    public function shortcode_stats( $atts ) {
        return Trade_Journal_Shortcodes::stats( $atts );
    }

    public function shortcode_checklist( $atts ) {
        return Trade_Journal_Shortcodes::strategy_checklist( $atts );
    }

    // AJAX methods
    public function ajax_get_trades() {
        check_ajax_referer( 'trade_journal_wp_nonce', 'nonce' );
        
        $database = new Trade_Journal_Database();
        $trades = $database->get_all_trades();
        
        wp_send_json_success( $trades );
    }

    public function ajax_save_trade() {
        check_ajax_referer( 'trade_journal_wp_nonce', 'nonce' );
        
        if ( ! current_user_can( 'edit_posts' ) ) {
            wp_send_json_error( 'Insufficient permissions' );
        }
        
        $data = $this->sanitize_trade_data( $_POST );
        
        $database = new Trade_Journal_Database();
        $result = $database->save_trade( $data );
        
        if ( $result ) {
            wp_send_json_success( $result );
        } else {
            wp_send_json_error( 'Failed to save trade' );
        }
    }

    public function ajax_update_trade() {
        check_ajax_referer( 'trade_journal_wp_nonce', 'nonce' );
        
        if ( ! current_user_can( 'edit_posts' ) ) {
            wp_send_json_error( 'Insufficient permissions' );
        }
        
        $id = sanitize_text_field( $_POST['id'] );
        $data = $this->sanitize_trade_data( $_POST );
        
        $database = new Trade_Journal_Database();
        $result = $database->update_trade( $id, $data );
        
        if ( $result ) {
            wp_send_json_success( $result );
        } else {
            wp_send_json_error( 'Failed to update trade' );
        }
    }

    public function ajax_delete_trade() {
        check_ajax_referer( 'trade_journal_wp_nonce', 'nonce' );
        
        if ( ! current_user_can( 'delete_posts' ) ) {
            wp_send_json_error( 'Insufficient permissions' );
        }
        
        $id = sanitize_text_field( $_POST['id'] );
        
        $database = new Trade_Journal_Database();
        $result = $database->delete_trade( $id );
        
        if ( $result ) {
            wp_send_json_success();
        } else {
            wp_send_json_error( 'Failed to delete trade' );
        }
    }

    /**
     * AJAX handler for testing database connection
     */
    public function ajax_test_db_connection() {
        check_ajax_referer( 'trade_journal_test_db', 'nonce' );
        
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( 'Insufficient permissions' );
        }
        
        $config = array(
            'host'     => sanitize_text_field( $_POST['host'] ),
            'username' => sanitize_text_field( $_POST['username'] ),
            'password' => sanitize_text_field( $_POST['password'] ),
            'database' => sanitize_text_field( $_POST['database'] ),
            'port'     => intval( $_POST['port'] ),
        );
        
        // Test connection
        try {
            $connection = new mysqli(
                $config['host'],
                $config['username'],
                $config['password'],
                $config['database'],
                $config['port']
            );
            
            if ( $connection->connect_error ) {
                wp_send_json_error( 'Connection failed: ' . $connection->connect_error );
            }
            
            // Test if table exists
            $table_check = $connection->query( "SHOW TABLES LIKE 'trading_journal_entries'" );
            $table_exists = $table_check && $table_check->num_rows > 0;
            
            $connection->close();
            
            $message = 'Connection successful! ';
            if ( $table_exists ) {
                $message .= 'Trading journal table found.';
            } else {
                $message .= 'Table will be created automatically.';
            }
            
            wp_send_json_success( array( 'message' => $message ) );
            
        } catch ( Exception $e ) {
            wp_send_json_error( 'Connection test failed: ' . $e->getMessage() );
        }
    }

    /**
     * Sanitize trade data
     */
    private function sanitize_trade_data( $data ) {
        $sanitized = array();
        
        $sanitized['market'] = sanitize_text_field( $data['market'] ?? '' );
        $sanitized['session'] = sanitize_text_field( $data['session'] ?? '' );
        $sanitized['date'] = sanitize_text_field( $data['date'] ?? '' );
        $sanitized['time'] = sanitize_text_field( $data['time'] ?? '' );
        $sanitized['direction'] = sanitize_text_field( $data['direction'] ?? '' );
        $sanitized['entry_price'] = floatval( $data['entryPrice'] ?? 0 );
        $sanitized['exit_price'] = floatval( $data['exitPrice'] ?? 0 );
        $sanitized['outcome'] = sanitize_text_field( $data['outcome'] ?? '' );
        $sanitized['pl_percent'] = floatval( $data['plPercent'] ?? 0 );
        $sanitized['rr'] = floatval( $data['rr'] ?? 0 );
        $sanitized['tf'] = is_array( $data['tf'] ?? null ) ? $data['tf'] : array();
        $sanitized['chart_htf'] = esc_url_raw( $data['chartHtf'] ?? '' );
        $sanitized['chart_ltf'] = esc_url_raw( $data['chartLtf'] ?? '' );
        $sanitized['comments'] = sanitize_textarea_field( $data['comments'] ?? '' );
        
        return $sanitized;
    }
}

// Initialize plugin
Trade_Journal_WP::get_instance();
<?php
/**
 * Trade Journal Admin Class
 * 
 * Handles admin-specific functionality
 *
 * @package TradeJournalWP
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Trade_Journal_Admin {

    /**
     * Constructor
     */
    public function __construct() {
        add_action( 'admin_init', array( $this, 'init_settings' ) );
    }

    /**
     * Initialize settings
     */
    public function init_settings() {
        // Register settings
        register_setting( 'trade_journal_wp_settings_group', 'trade_journal_wp_settings', array( $this, 'sanitize_settings' ) );
        register_setting( 'trade_journal_wp_settings_group', 'trade_journal_wp_db_config' );

        // Add settings sections
        add_settings_section(
            'trade_journal_wp_general_section',
            __( 'General Settings', 'trade-journal-wp' ),
            array( $this, 'general_section_callback' ),
            'trade_journal_wp_settings'
        );

        add_settings_section(
            'trade_journal_wp_database_section',
            __( 'Database Configuration', 'trade-journal-wp' ),
            array( $this, 'database_section_callback' ),
            'trade_journal_wp_settings'
        );

        // Add settings fields
        $this->add_settings_fields();
    }

    /**
     * Add settings fields
     */
    private function add_settings_fields() {
        // Markets field
        add_settings_field(
            'markets',
            __( 'Available Markets', 'trade-journal-wp' ),
            array( $this, 'markets_field_callback' ),
            'trade_journal_wp_settings',
            'trade_journal_wp_general_section'
        );

        // Sessions field
        add_settings_field(
            'sessions',
            __( 'Trading Sessions', 'trade-journal-wp' ),
            array( $this, 'sessions_field_callback' ),
            'trade_journal_wp_settings',
            'trade_journal_wp_general_section'
        );

        // Timeframes field
        add_settings_field(
            'timeframes',
            __( 'Available Timeframes', 'trade-journal-wp' ),
            array( $this, 'timeframes_field_callback' ),
            'trade_journal_wp_settings',
            'trade_journal_wp_general_section'
        );

        // Database host
        add_settings_field(
            'db_host',
            __( 'Database Host', 'trade-journal-wp' ),
            array( $this, 'db_host_field_callback' ),
            'trade_journal_wp_settings',
            'trade_journal_wp_database_section'
        );

        // Database username
        add_settings_field(
            'db_username',
            __( 'Database Username', 'trade-journal-wp' ),
            array( $this, 'db_username_field_callback' ),
            'trade_journal_wp_settings',
            'trade_journal_wp_database_section'
        );

        // Database password
        add_settings_field(
            'db_password',
            __( 'Database Password', 'trade-journal-wp' ),
            array( $this, 'db_password_field_callback' ),
            'trade_journal_wp_settings',
            'trade_journal_wp_database_section'
        );

        // Database name
        add_settings_field(
            'db_name',
            __( 'Database Name', 'trade-journal-wp' ),
            array( $this, 'db_name_field_callback' ),
            'trade_journal_wp_settings',
            'trade_journal_wp_database_section'
        );

        // Database port
        add_settings_field(
            'db_port',
            __( 'Database Port', 'trade-journal-wp' ),
            array( $this, 'db_port_field_callback' ),
            'trade_journal_wp_settings',
            'trade_journal_wp_database_section'
        );
    }

    /**
     * General section callback
     */
    public function general_section_callback() {
        echo '<p>' . esc_html__( 'Configure general plugin settings.', 'trade-journal-wp' ) . '</p>';
    }

    /**
     * Database section callback
     */
    public function database_section_callback() {
        echo '<p>' . esc_html__( 'Configure the external database connection. These settings override the default configuration.', 'trade-journal-wp' ) . '</p>';
        
        // Test connection button
        echo '<p><button type="button" id="testDbConnection" class="button">' . esc_html__( 'Test Connection', 'trade-journal-wp' ) . '</button> <span id="connectionStatus"></span></p>';
    }

    /**
     * Markets field callback
     */
    public function markets_field_callback() {
        $settings = get_option( 'trade_journal_wp_settings', array() );
        $markets = isset( $settings['markets'] ) ? $settings['markets'] : array( 'XAUUSD', 'EU', 'GU', 'UJ', 'US30', 'NAS100' );
        
        // Handle case where markets might be saved as string instead of array
        if ( is_string( $markets ) ) {
            $markets = explode( "\n", trim( $markets ) );
        }
        
        // Ensure we have an array
        if ( ! is_array( $markets ) ) {
            $markets = array( 'XAUUSD', 'EU', 'GU', 'UJ', 'US30', 'NAS100' );
        }
        
        echo '<textarea name="trade_journal_wp_settings[markets]" rows="3" cols="50" class="regular-text">';
        echo esc_textarea( implode( "\n", $markets ) );
        echo '</textarea>';
        echo '<p class="description">' . esc_html__( 'Enter one market per line (e.g., XAUUSD, EURUSD, etc.)', 'trade-journal-wp' ) . '</p>';
    }

    /**
     * Sessions field callback
     */
    public function sessions_field_callback() {
        $settings = get_option( 'trade_journal_wp_settings', array() );
        $sessions = isset( $settings['sessions'] ) ? $settings['sessions'] : array( 'LO', 'NY', 'AS' );
        
        // Handle case where sessions might be saved as string instead of array
        if ( is_string( $sessions ) ) {
            $sessions = array_map( 'trim', explode( ',', $sessions ) );
        }
        
        // Ensure we have an array
        if ( ! is_array( $sessions ) ) {
            $sessions = array( 'LO', 'NY', 'AS' );
        }
        
        echo '<input type="text" name="trade_journal_wp_settings[sessions]" value="' . esc_attr( implode( ', ', $sessions ) ) . '" class="regular-text" />';
        echo '<p class="description">' . esc_html__( 'Enter sessions separated by commas (e.g., LO, NY, AS)', 'trade-journal-wp' ) . '</p>';
    }

    /**
     * Timeframes field callback
     */
    public function timeframes_field_callback() {
        $settings = get_option( 'trade_journal_wp_settings', array() );
        $timeframes = isset( $settings['timeframes'] ) ? $settings['timeframes'] : array( '5m', '15m', '30m', '1H', '4H', '1D' );
        
        // Handle case where timeframes might be saved as string instead of array
        if ( is_string( $timeframes ) ) {
            $timeframes = array_map( 'trim', explode( ',', $timeframes ) );
        }
        
        // Ensure we have an array
        if ( ! is_array( $timeframes ) ) {
            $timeframes = array( '5m', '15m', '30m', '1H', '4H', '1D' );
        }
        
        echo '<input type="text" name="trade_journal_wp_settings[timeframes]" value="' . esc_attr( implode( ', ', $timeframes ) ) . '" class="regular-text" />';
        echo '<p class="description">' . esc_html__( 'Enter timeframes separated by commas (e.g., 5m, 15m, 30m, 1H, 4H, 1D)', 'trade-journal-wp' ) . '</p>';
    }

    /**
     * Database host field callback
     */
    public function db_host_field_callback() {
        $config = get_option( 'trade_journal_wp_db_config', array() );
        $host = isset( $config['host'] ) ? $config['host'] : '';
        
        echo '<input type="text" name="trade_journal_wp_db_config[host]" value="' . esc_attr( $host ) . '" class="regular-text" />';
        echo '<p class="description">' . esc_html__( 'MySQL database host address', 'trade-journal-wp' ) . '</p>';
    }

    /**
     * Database username field callback
     */
    public function db_username_field_callback() {
        $config = get_option( 'trade_journal_wp_db_config', array() );
        $username = isset( $config['username'] ) ? $config['username'] : '';
        
        echo '<input type="text" name="trade_journal_wp_db_config[username]" value="' . esc_attr( $username ) . '" class="regular-text" />';
        echo '<p class="description">' . esc_html__( 'MySQL database username', 'trade-journal-wp' ) . '</p>';
    }

    /**
     * Database password field callback
     */
    public function db_password_field_callback() {
        $config = get_option( 'trade_journal_wp_db_config', array() );
        $password = isset( $config['password'] ) ? $config['password'] : '';
        
        echo '<input type="password" name="trade_journal_wp_db_config[password]" value="' . esc_attr( $password ) . '" class="regular-text" />';
        echo '<p class="description">' . esc_html__( 'MySQL database password', 'trade-journal-wp' ) . '</p>';
    }

    /**
     * Database name field callback
     */
    public function db_name_field_callback() {
        $config = get_option( 'trade_journal_wp_db_config', array() );
        $database = isset( $config['database'] ) ? $config['database'] : '';
        
        echo '<input type="text" name="trade_journal_wp_db_config[database]" value="' . esc_attr( $database ) . '" class="regular-text" />';
        echo '<p class="description">' . esc_html__( 'MySQL database name', 'trade-journal-wp' ) . '</p>';
    }

    /**
     * Database port field callback
     */
    public function db_port_field_callback() {
        $config = get_option( 'trade_journal_wp_db_config', array() );
        $port = isset( $config['port'] ) ? $config['port'] : 3306;
        
        echo '<input type="number" name="trade_journal_wp_db_config[port]" value="' . esc_attr( $port ) . '" class="small-text" min="1" max="65535" />';
        echo '<p class="description">' . esc_html__( 'MySQL database port (usually 3306)', 'trade-journal-wp' ) . '</p>';
    }

    /**
     * Sanitize settings
     */
    public function sanitize_settings( $settings ) {
        $sanitized = array();
        
        if ( isset( $settings['markets'] ) ) {
            $markets = explode( "\n", $settings['markets'] );
            $sanitized['markets'] = array_map( 'sanitize_text_field', array_map( 'trim', $markets ) );
            $sanitized['markets'] = array_filter( $sanitized['markets'] );
        }
        
        if ( isset( $settings['sessions'] ) ) {
            $sessions = explode( ',', $settings['sessions'] );
            $sanitized['sessions'] = array_map( 'sanitize_text_field', array_map( 'trim', $sessions ) );
            $sanitized['sessions'] = array_filter( $sanitized['sessions'] );
        }
        
        if ( isset( $settings['timeframes'] ) ) {
            $timeframes = explode( ',', $settings['timeframes'] );
            $sanitized['timeframes'] = array_map( 'sanitize_text_field', array_map( 'trim', $timeframes ) );
            $sanitized['timeframes'] = array_filter( $sanitized['timeframes'] );
        }
        
        return $sanitized;
    }

    /**
     * Get trade statistics for admin dashboard
     */
    public function get_admin_statistics() {
        $database = Trade_Journal_Database::get_instance();
        return $database->get_statistics();
    }

    /**
     * Export trades to CSV
     */
    public function export_trades_csv() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'Insufficient permissions', 'trade-journal-wp' ) );
        }

        $database = Trade_Journal_Database::get_instance();
        $trades = $database->get_all_trades();

        if ( empty( $trades ) ) {
            wp_die( esc_html__( 'No trades to export', 'trade-journal-wp' ) );
        }

        // Set headers for download
        header( 'Content-Type: text/csv' );
        header( 'Content-Disposition: attachment; filename="trade-journal-export-' . date( 'Y-m-d' ) . '.csv"' );

        // Create output stream
        $output = fopen( 'php://output', 'w' );

        // Add CSV headers
        $headers = array(
            'ID',
            'Date',
            'Time',
            'Market',
            'Session',
            'Direction',
            'Entry Price',
            'Exit Price',
            'Outcome',
            'P/L %',
            'RR',
            'Timeframes',
            'Chart HTF',
            'Chart LTF',
            'Comments',
            'Created At'
        );

        fputcsv( $output, $headers );

        // Add trade data
        foreach ( $trades as $trade ) {
            $row = array(
                $trade['id'],
                $trade['date'],
                $trade['time'],
                $trade['market'],
                $trade['session'],
                $trade['direction'],
                $trade['entry_price'],
                $trade['exit_price'],
                $trade['outcome'],
                $trade['pl_percent'],
                $trade['rr'],
                $trade['tf'] ? implode( ';', $trade['tf'] ) : '',
                $trade['chart_htf'],
                $trade['chart_ltf'],
                $trade['comments'],
                $trade['created_at']
            );

            fputcsv( $output, $row );
        }

        fclose( $output );
        exit;
    }
}

// Initialize admin class
new Trade_Journal_Admin();
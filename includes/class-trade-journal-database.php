<?php
/**
 * Trade Journal Database Class
 * 
 * Handles all database operations for the Trade Journal plugin
 * Uses external MySQL database, not WordPress database
 *
 * @package TradeJournalWP
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Trade_Journal_Database {

    /**
     * Database connection instance
     */
    private static $instance = null;
    
    /**
     * MySQLi connection
     */
    private $connection = null;
    
    /**
     * Database configuration
     */
    private $config = array();

    /**
     * Get database instance
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
        $this->load_config();
        $this->connect();
        $this->initialize_table();
    }

    /**
     * Load database configuration
     */
    private function load_config() {
        // Try to load from WordPress options first
        $saved_config = get_option( 'trade_journal_wp_db_config', array() );
        
        if ( ! empty( $saved_config ) ) {
            $this->config = $saved_config;
            return;
        }

        // Default configuration - can be overridden via WordPress admin
        $this->config = array(
            'host'     => '',
            'username' => '',
            'password' => '',
            'database' => '',
            'port'     => 3306,
        );
    }

    /**
     * Connect to external MySQL database
     */
    private function connect() {
        try {
            $this->connection = new mysqli(
                $this->config['host'],
                $this->config['username'],
                $this->config['password'],
                $this->config['database'],
                $this->config['port']
            );

            if ( $this->connection->connect_error ) {
                throw new Exception( 'Connection failed: ' . $this->connection->connect_error );
            }

            // Set charset
            $this->connection->set_charset( 'utf8mb4' );

        } catch ( Exception $e ) {
            error_log( 'Trade Journal WP Database Connection Error: ' . $e->getMessage() );
            
            // Show admin notice if in admin area
            if ( is_admin() ) {
                add_action( 'admin_notices', array( $this, 'connection_error_notice' ) );
            }
        }
    }

    /**
     * Show connection error notice in admin
     */
    public function connection_error_notice() {
        $message = __( 'Trade Journal WP: Unable to connect to external database. Please check your database settings.', 'trade-journal-wp' );
        echo '<div class="notice notice-error"><p>' . esc_html( $message ) . '</p></div>';
    }

    /**
     * Initialize database table
     */
    private function initialize_table() {
        if ( ! $this->connection ) {
            return false;
        }

        $sql = "CREATE TABLE IF NOT EXISTS trading_journal_entries (
            id varchar(255) NOT NULL,
            user_id int NOT NULL DEFAULT 0,
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
            KEY idx_user_id (user_id),
            KEY idx_date (date),
            KEY idx_market (market),
            KEY idx_outcome (outcome),
            KEY idx_created_at (created_at),
            KEY idx_user_date (user_id, date)
        )";

        if ( ! $this->connection->query( $sql ) ) {
            error_log( 'Trade Journal WP: Failed to create table: ' . $this->connection->error );
            return false;
        }

        return true;
    }

    /**
     * Get all trades for a specific user (or all trades for admins)
     */
    public function get_all_trades( $user_id = null ) {
        if ( ! $this->connection ) {
            return array();
        }

        // If no user_id provided, get current user ID
        if ( $user_id === null ) {
            $user_id = get_current_user_id();
        }
        
        // Admins can see all trades by passing user_id = 0
        if ( $user_id === 0 || ( current_user_can( 'manage_options' ) && $user_id === 'all' ) ) {
            $sql = "SELECT * FROM trading_journal_entries ORDER BY created_at DESC";
            $result = $this->connection->query( $sql );
        } else {
            $sql = "SELECT * FROM trading_journal_entries WHERE user_id = ? ORDER BY created_at DESC";
            $stmt = $this->connection->prepare( $sql );
            if ( ! $stmt ) {
                error_log( 'Trade Journal WP: Prepare failed: ' . $this->connection->error );
                return array();
            }
            $stmt->bind_param( "i", $user_id );
            $stmt->execute();
            $result = $stmt->get_result();
        }

        if ( ! $result ) {
            error_log( 'Trade Journal WP: Error fetching trades: ' . $this->connection->error );
            return array();
        }

        $trades = array();
        while ( $row = $result->fetch_assoc() ) {
            $trades[] = $this->format_trade( $row );
        }

        return $trades;
    }

    /**
     * Get single trade with user ownership validation
     */
    public function get_trade( $id, $user_id = null ) {
        if ( ! $this->connection ) {
            return null;
        }

        // If no user_id provided, get current user ID
        if ( $user_id === null ) {
            $user_id = get_current_user_id();
        }

        // Admins can access any trade
        if ( current_user_can( 'manage_options' ) ) {
            $sql = "SELECT * FROM trading_journal_entries WHERE id = ?";
            $stmt = $this->connection->prepare( $sql );
            if ( ! $stmt ) {
                error_log( 'Trade Journal WP: Prepare failed: ' . $this->connection->error );
                return null;
            }
            $stmt->bind_param( "s", $id );
        } else {
            $sql = "SELECT * FROM trading_journal_entries WHERE id = ? AND user_id = ?";
            $stmt = $this->connection->prepare( $sql );
            if ( ! $stmt ) {
                error_log( 'Trade Journal WP: Prepare failed: ' . $this->connection->error );
                return null;
            }
            $stmt->bind_param( "si", $id, $user_id );
        }

        $stmt->execute();
        $result = $stmt->get_result();
        if ( $row = $result->fetch_assoc() ) {
            return $this->format_trade( $row );
        }

        return null;
    }

    /**
     * Save new trade
     */
    public function save_trade( $data ) {
        if ( ! $this->connection ) {
            return false;
        }

        $id = $this->generate_id();
        $created_at = current_time( 'mysql' );

        $sql = "
            INSERT INTO trading_journal_entries (
                id, user_id, market, session, date, time, direction,
                entry_price, exit_price, outcome, pl_percent, rr,
                tf, chart_htf, chart_ltf, comments
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ";

        $stmt = $this->connection->prepare( $sql );
        if ( ! $stmt ) {
            error_log( 'Trade Journal WP: Prepare failed: ' . $this->connection->error );
            return false;
        }

        // Handle timeframes field - convert array to JSON
        $tf = null;
        if ( ! empty( $data['tf'] ) ) {
            if ( is_array( $data['tf'] ) ) {
                $tf = wp_json_encode( $data['tf'] );
            } else {
                $tf = $data['tf'];
            }
        }

        // Get user_id from data or current user
        $user_id = isset( $data['user_id'] ) ? $data['user_id'] : get_current_user_id();

        $stmt->bind_param(
            "sisssssddsddssss",
            $id,
            $user_id,
            $data['market'],
            $data['session'],
            $data['date'],
            $data['time'],
            $data['direction'],
            $data['entry_price'],
            $data['exit_price'],
            $data['outcome'],
            $data['pl_percent'],
            $data['rr'],
            $tf,
            $data['chart_htf'],
            $data['chart_ltf'],
            $data['comments']
        );

        if ( $stmt->execute() ) {
            return array_merge( $data, array(
                'id'         => $id,
                'created_at' => $created_at,
            ) );
        }

        error_log( 'Trade Journal WP: Failed to save trade: ' . $stmt->error );
        return false;
    }

    /**
     * Update existing trade with user ownership validation
     */
    public function update_trade( $id, $updates, $user_id = null ) {
        if ( ! $this->connection ) {
            return false;
        }

        $set_clause = array();
        $values = array();
        $types = "";

        $allowed_fields = array(
            'market'      => 's',
            'session'     => 's',
            'date'        => 's',
            'time'        => 's',
            'direction'   => 's',
            'entry_price' => 'd',
            'exit_price'  => 'd',
            'outcome'     => 's',
            'pl_percent'  => 'd',
            'rr'          => 'd',
            'tf'          => 's',
            'chart_htf'   => 's',
            'chart_ltf'   => 's',
            'comments'    => 's',
        );

        foreach ( $updates as $field => $value ) {
            if ( array_key_exists( $field, $allowed_fields ) ) {
                if ( 'tf' === $field ) {
                    // Handle timeframes field - convert array to JSON
                    if ( is_array( $value ) && ! empty( $value ) ) {
                        $value = wp_json_encode( $value );
                    } elseif ( empty( $value ) ) {
                        $value = null;
                    }
                } elseif ( in_array( $field, array( 'entry_price', 'exit_price', 'pl_percent', 'rr' ), true ) && '' === $value ) {
                    $value = null;
                }

                $set_clause[] = "$field = ?";
                $values[] = $value;
                $types .= $allowed_fields[ $field ];
            }
        }

        if ( empty( $set_clause ) ) {
            return false;
        }

        // If no user_id provided, get current user ID
        if ( $user_id === null ) {
            $user_id = get_current_user_id();
        }

        // Admins can update any trade, regular users only their own
        if ( current_user_can( 'manage_options' ) ) {
            $sql = "UPDATE trading_journal_entries SET " . implode( ', ', $set_clause ) . " WHERE id = ?";
            $values[] = $id;
            $types .= "s";
        } else {
            $sql = "UPDATE trading_journal_entries SET " . implode( ', ', $set_clause ) . " WHERE id = ? AND user_id = ?";
            $values[] = $id;
            $values[] = $user_id;
            $types .= "si";
        }

        $stmt = $this->connection->prepare( $sql );
        if ( ! $stmt ) {
            error_log( 'Trade Journal WP: Prepare failed: ' . $this->connection->error );
            return false;
        }

        $stmt->bind_param( $types, ...$values );

        if ( $stmt->execute() && $stmt->affected_rows > 0 ) {
            return $this->get_trade( $id );
        }

        return false;
    }

    /**
     * Delete trade with user ownership validation
     */
    public function delete_trade( $id, $user_id = null ) {
        if ( ! $this->connection ) {
            return false;
        }

        // If no user_id provided, get current user ID
        if ( $user_id === null ) {
            $user_id = get_current_user_id();
        }

        // Admins can delete any trade, regular users only their own
        if ( current_user_can( 'manage_options' ) ) {
            $sql = "DELETE FROM trading_journal_entries WHERE id = ?";
            $stmt = $this->connection->prepare( $sql );
            if ( ! $stmt ) {
                error_log( 'Trade Journal WP: Prepare failed: ' . $this->connection->error );
                return false;
            }
            $stmt->bind_param( "s", $id );
        } else {
            $sql = "DELETE FROM trading_journal_entries WHERE id = ? AND user_id = ?";
            $stmt = $this->connection->prepare( $sql );
            if ( ! $stmt ) {
                error_log( 'Trade Journal WP: Prepare failed: ' . $this->connection->error );
                return false;
            }
            $stmt->bind_param( "si", $id, $user_id );
        }

        return $stmt->execute() && $stmt->affected_rows > 0;
    }

    /**
     * Get trades statistics
     */
    public function get_statistics() {
        if ( ! $this->connection ) {
            return array();
        }

        $stats = array(
            'total_trades'  => 0,
            'wins'          => 0,
            'losses'        => 0,
            'break_even'    => 0,
            'cancelled'     => 0,
            'win_rate'      => 0,
            'profit_factor' => 0,
            'total_pl'      => 0,
            'avg_win'       => 0,
            'avg_loss'      => 0,
            'best_trade'    => 0,
            'worst_trade'   => 0,
        );

        // Get basic counts and totals
        $sql = "SELECT 
                    COUNT(*) as total_trades,
                    SUM(CASE WHEN outcome = 'W' THEN 1 ELSE 0 END) as wins,
                    SUM(CASE WHEN outcome = 'L' THEN 1 ELSE 0 END) as losses,
                    SUM(CASE WHEN outcome = 'BE' THEN 1 ELSE 0 END) as break_even,
                    SUM(CASE WHEN outcome = 'C' THEN 1 ELSE 0 END) as cancelled,
                    SUM(CASE WHEN pl_percent IS NOT NULL THEN pl_percent ELSE 0 END) as total_pl,
                    AVG(CASE WHEN outcome = 'W' AND pl_percent IS NOT NULL THEN pl_percent END) as avg_win,
                    AVG(CASE WHEN outcome = 'L' AND pl_percent IS NOT NULL THEN pl_percent END) as avg_loss,
                    MAX(pl_percent) as best_trade,
                    MIN(pl_percent) as worst_trade
                FROM trading_journal_entries";

        $result = $this->connection->query( $sql );
        if ( $result && $row = $result->fetch_assoc() ) {
            $stats = array_merge( $stats, array_map( 'floatval', $row ) );
        }

        // Calculate derived statistics
        if ( $stats['total_trades'] > 0 ) {
            $stats['win_rate'] = ( $stats['wins'] / $stats['total_trades'] ) * 100;
        }

        if ( $stats['avg_loss'] != 0 ) {
            $stats['profit_factor'] = abs( $stats['avg_win'] / $stats['avg_loss'] );
        }

        return $stats;
    }

    /**
     * Format trade data for output
     */
    private function format_trade( $row ) {
        return array(
            'id'          => $row['id'] ?? '',
            'user_id'     => isset( $row['user_id'] ) ? (int) $row['user_id'] : 0,
            'market'      => $row['market'] ?? '',
            'session'     => $row['session'] ?? '',
            'date'        => $row['date'] ?? '',
            'time'        => $row['time'] ?? '',
            'direction'   => $row['direction'] ?? '',
            'entry_price' => isset( $row['entry_price'] ) && $row['entry_price'] ? (float) $row['entry_price'] : null,
            'exit_price'  => isset( $row['exit_price'] ) && $row['exit_price'] ? (float) $row['exit_price'] : null,
            'outcome'     => $row['outcome'] ?? '',
            'pl_percent'  => isset( $row['pl_percent'] ) && $row['pl_percent'] ? (float) $row['pl_percent'] : null,
            'rr'          => isset( $row['rr'] ) && $row['rr'] ? (float) $row['rr'] : null,
            'tf'          => isset( $row['tf'] ) && $row['tf'] ? json_decode( $row['tf'], true ) : null,
            'chart_htf'   => $row['chart_htf'] ?? '',
            'chart_ltf'   => $row['chart_ltf'] ?? '',
            'comments'    => $row['comments'] ?? '',
            'created_at'  => $row['created_at'] ?? '',
            'updated_at'  => $row['updated_at'] ?? '',
        );
    }

    /**
     * Generate unique ID for new trades
     */
    private function generate_id() {
        return time() . substr( md5( wp_rand() ), 0, 8 );
    }

    /**
     * Get database connection for direct queries if needed
     */
    public function get_connection() {
        return $this->connection;
    }

    /**
     * Test database connection
     */
    public function test_connection() {
        if ( ! $this->connection ) {
            return false;
        }

        return $this->connection->ping();
    }

    /**
     * Update database configuration
     */
    public function update_config( $new_config ) {
        $this->config = array_merge( $this->config, $new_config );
        update_option( 'trade_journal_wp_db_config', $this->config );
        
        // Reconnect with new config
        if ( $this->connection ) {
            $this->connection->close();
        }
        
        $this->connect();
        $this->initialize_table();
    }

    /**
     * Close database connection on destruction
     */
    public function __destruct() {
        if ( $this->connection ) {
            $this->connection->close();
        }
    }
}
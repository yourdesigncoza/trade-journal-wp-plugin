<?php
/**
 * Trade Journal Shortcodes Class
 * 
 * Handles all shortcode implementations for the Trade Journal plugin
 *
 * @package TradeJournalWP
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Trade_Journal_Shortcodes {

    /**
     * Dashboard shortcode
     * 
     * @param array $atts Shortcode attributes
     * @return string HTML output
     */
    public static function dashboard( $atts ) {
        $atts = shortcode_atts( array(
            'show_form'  => 'true',
            'show_list'  => 'true',
            'show_stats' => 'true',
            'class'      => '',
        ), $atts, 'trade_journal_dashboard' );

        ob_start();
        
        echo '<div class="trade-journal-dashboard ' . esc_attr( $atts['class'] ) . '">';
        
        if ( 'true' === $atts['show_form'] || 'true' === $atts['show_list'] ) {
            echo '<div class="row">';
            
            if ( 'true' === $atts['show_form'] ) {
                echo '<div class="col-lg-8">';
                echo self::add_form( array( 'show_title' => 'false' ) );
                echo '</div>';
            }
            
            if ( 'true' === $atts['show_stats'] ) {
                $col_class = 'true' === $atts['show_form'] ? 'col-lg-4' : 'col-12';
                echo '<div class="' . $col_class . '">';
                echo self::stats( array( 'show_title' => 'false' ) );
                echo '</div>';
            }
            
            echo '</div>';
        }
        
        if ( 'true' === $atts['show_list'] ) {
            echo '<div class="row mt-4">';
            echo '<div class="col-12">';
            echo self::trade_list( array( 'show_title' => 'false' ) );
            echo '</div>';
            echo '</div>';
        }
        
        echo '</div>';
        
        return ob_get_clean();
    }

    /**
     * Add trade form shortcode
     * 
     * @param array $atts Shortcode attributes
     * @return string HTML output
     */
    public static function add_form( $atts ) {
        $atts = shortcode_atts( array(
            'show_title' => 'true',
            'class'      => '',
            'redirect'   => '',
        ), $atts, 'trade_journal_add' );

        $database = Trade_Journal_Database::get_instance();
        $settings = get_option( 'trade_journal_wp_settings', array() );

        ob_start();
        
        echo '<div class="trade-journal-add-form ' . esc_attr( $atts['class'] ) . '">';
        
        if ( 'true' === $atts['show_title'] ) {
            echo '<h3>' . esc_html__( 'Add New Trade', 'trade-journal-wp' ) . '</h3>';
        }
        
        include TRADE_JOURNAL_WP_PLUGIN_DIR . 'views/frontend/add-form.php';
        
        echo '</div>';
        
        return ob_get_clean();
    }

    /**
     * Trade list shortcode
     * 
     * @param array $atts Shortcode attributes
     * @return string HTML output
     */
    public static function trade_list( $atts ) {
        $atts = shortcode_atts( array(
            'show_title'   => 'true',
            'per_page'     => '20',
            'show_search'  => 'true',
            'show_filters' => 'true',
            'class'        => '',
        ), $atts, 'trade_journal_list' );

        $database = Trade_Journal_Database::get_instance();
        $trades = $database->get_all_trades();

        ob_start();
        
        echo '<div class="trade-journal-list ' . esc_attr( $atts['class'] ) . '">';
        
        if ( 'true' === $atts['show_title'] ) {
            echo '<h3>' . esc_html__( 'Trade History', 'trade-journal-wp' ) . '</h3>';
        }
        
        include TRADE_JOURNAL_WP_PLUGIN_DIR . 'views/frontend/trade-list.php';
        
        echo '</div>';
        
        return ob_get_clean();
    }

    /**
     * Statistics shortcode
     * 
     * @param array $atts Shortcode attributes
     * @return string HTML output
     */
    public static function stats( $atts ) {
        $atts = shortcode_atts( array(
            'show_title' => 'true',
            'layout'     => 'vertical', // vertical or horizontal
            'class'      => '',
        ), $atts, 'trade_journal_stats' );

        $database = Trade_Journal_Database::get_instance();
        $stats = $database->get_statistics();

        ob_start();
        
        echo '<div class="trade-journal-stats ' . esc_attr( $atts['class'] ) . '">';
        
        if ( 'true' === $atts['show_title'] ) {
            echo '<h3>' . esc_html__( 'Performance Statistics', 'trade-journal-wp' ) . '</h3>';
        }
        
        include TRADE_JOURNAL_WP_PLUGIN_DIR . 'views/frontend/stats.php';
        
        echo '</div>';
        
        return ob_get_clean();
    }

    /**
     * Trade strategy checklist shortcode
     * 
     * @param array $atts Shortcode attributes
     * @return string HTML output
     */
    public static function strategy_checklist( $atts ) {
        $atts = shortcode_atts( array(
            'show_title' => 'true',
            'class'      => '',
        ), $atts, 'trade_journal_checklist' );

        ob_start();
        
        echo '<div class="trade-journal-checklist ' . esc_attr( $atts['class'] ) . '">';
        
        if ( 'true' === $atts['show_title'] ) {
            echo '<h3>' . esc_html__( 'Pre-Trade Checklist', 'trade-journal-wp' ) . '</h3>';
        }
        
        include TRADE_JOURNAL_WP_PLUGIN_DIR . 'views/frontend/strategy-checklist.php';
        
        echo '</div>';
        
        return ob_get_clean();
    }

    /**
     * Helper method to get market options
     */
    public static function get_market_options() {
        $settings = get_option( 'trade_journal_wp_settings', array() );
        $markets = isset( $settings['markets'] ) ? $settings['markets'] : array( 'XAUUSD', 'EU', 'GU', 'UJ', 'US30', 'NAS100' );
        
        // Handle case where markets might be saved as string instead of array
        if ( is_string( $markets ) ) {
            $markets = array_filter( array_map( 'trim', explode( "\n", $markets ) ) );
        }
        
        // Ensure we have an array
        if ( ! is_array( $markets ) || empty( $markets ) ) {
            $markets = array( 'XAUUSD', 'EU', 'GU', 'UJ', 'US30', 'NAS100' );
        }
        
        $options = array();
        foreach ( $markets as $market ) {
            $label = self::get_market_label( $market );
            $options[ $market ] = $label;
        }
        
        return $options;
    }

    /**
     * Helper method to get session options
     */
    public static function get_session_options() {
        return array(
            'LO' => __( 'London', 'trade-journal-wp' ),
            'NY' => __( 'New York', 'trade-journal-wp' ),
            'AS' => __( 'Asia', 'trade-journal-wp' ),
        );
    }

    /**
     * Helper method to get direction options
     */
    public static function get_direction_options() {
        return array(
            'LONG'  => __( 'Long', 'trade-journal-wp' ),
            'SHORT' => __( 'Short', 'trade-journal-wp' ),
        );
    }

    /**
     * Helper method to get outcome options
     */
    public static function get_outcome_options() {
        return array(
            'W'  => __( 'Win', 'trade-journal-wp' ),
            'L'  => __( 'Loss', 'trade-journal-wp' ),
            'BE' => __( 'Break Even', 'trade-journal-wp' ),
            'C'  => __( 'Cancelled', 'trade-journal-wp' ),
        );
    }

    /**
     * Helper method to get timeframe options
     */
    public static function get_timeframe_options() {
        $settings = get_option( 'trade_journal_wp_settings', array() );
        $timeframes = isset( $settings['timeframes'] ) ? $settings['timeframes'] : array( '5m', '15m', '30m', '1H', '4H', '1D' );
        
        // Handle case where timeframes might be saved as string instead of array
        if ( is_string( $timeframes ) ) {
            $timeframes = array_filter( array_map( 'trim', explode( ',', $timeframes ) ) );
        }
        
        // Ensure we have an array
        if ( ! is_array( $timeframes ) || empty( $timeframes ) ) {
            $timeframes = array( '5m', '15m', '30m', '1H', '4H', '1D' );
        }
        
        $options = array();
        foreach ( $timeframes as $tf ) {
            $options[ $tf ] = self::get_timeframe_label( $tf );
        }
        
        return $options;
    }

    /**
     * Get market label
     */
    private static function get_market_label( $market ) {
        $labels = array(
            'XAUUSD' => 'XAUUSD (Gold)',
            'EU'     => 'EURUSD',
            'GU'     => 'GBPUSD',
            'UJ'     => 'USDJPY',
            'US30'   => 'US30',
            'NAS100' => 'NAS100',
        );
        
        return isset( $labels[ $market ] ) ? $labels[ $market ] : $market;
    }

    /**
     * Get timeframe label
     */
    private static function get_timeframe_label( $tf ) {
        $labels = array(
            '5m'  => '5 Minutes',
            '15m' => '15 Minutes',
            '30m' => '30 Minutes',
            '1H'  => '1 Hour',
            '4H'  => '4 Hours',
            '1D'  => 'Daily',
        );
        
        return isset( $labels[ $tf ] ) ? $labels[ $tf ] : $tf;
    }

    /**
     * Get outcome badge class
     */
    public static function get_outcome_badge_class( $outcome ) {
        $classes = array(
            'W'  => 'badge-success',
            'L'  => 'badge-danger',
            'BE' => 'badge-secondary',
            'C'  => 'badge-warning',
        );
        
        return isset( $classes[ $outcome ] ) ? $classes[ $outcome ] : 'badge-secondary';
    }

    /**
     * Format percentage value
     */
    public static function format_percentage( $value ) {
        if ( null === $value || '' === $value ) {
            return '-';
        }
        
        return number_format( (float) $value, 2 ) . '%';
    }

    /**
     * Format price value
     */
    public static function format_price( $value, $decimals = 3 ) {
        if ( null === $value || '' === $value ) {
            return '-';
        }
        
        // Truncate to specified decimals without rounding
        $multiplier = pow( 10, $decimals );
        $truncated = floor( (float) $value * $multiplier ) / $multiplier;
        
        return number_format( $truncated, $decimals );
    }

    /**
     * Format RR ratio
     */
    public static function format_rr( $value ) {
        if ( null === $value || '' === $value ) {
            return '-';
        }
        
        return number_format( (float) $value, 2 ) . ':1';
    }
}
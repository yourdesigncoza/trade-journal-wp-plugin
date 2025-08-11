<?php
/**
 * Trade Journal WP Uninstall Script
 * 
 * This file is executed when the plugin is uninstalled (deleted).
 * It cleans up plugin options and data.
 *
 * @package TradeJournalWP
 */

// If uninstall not called from WordPress, then exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

/**
 * Clean up plugin data on uninstall
 */
function trade_journal_wp_uninstall_cleanup() {
    // Remove plugin options
    delete_option( 'trade_journal_wp_version' );
    delete_option( 'trade_journal_wp_settings' );
    delete_option( 'trade_journal_wp_db_config' );
    
    // Remove any transients
    delete_transient( 'trade_journal_wp_stats_cache' );
    delete_transient( 'trade_journal_wp_connection_test' );
    
    // Clean up user meta (if any stored)
    delete_metadata( 'user', 0, 'trade_journal_wp_preferences', '', true );
    
    // Note: We do NOT drop the external database table as it may be shared
    // with other applications or the original PHP version of Trade Journal.
    // The external database should be managed separately.
    
    // Clear any cached data
    wp_cache_flush();
}

// Execute cleanup
trade_journal_wp_uninstall_cleanup();
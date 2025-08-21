<?php
/**
 * Admin Settings Page
 *
 * @package TradeJournalWP
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<div class="wrap">
    <h1><?php esc_html_e( 'Trade Journal Settings', 'trade-journal-wp' ); ?></h1>
    
    <?php settings_errors(); ?>

    <form method="post" action="options.php">
        <?php
        settings_fields( 'trade_journal_wp_settings_group' );
        do_settings_sections( 'trade_journal_wp_settings' );
        submit_button();
        ?>
    </form>

    <!-- Test Connection Results -->
    <div id="connectionTestResults" style="display: none;">
        <div class="postbox">
            <h2 class="hndle">
                <span><?php esc_html_e( 'Database Connection Test', 'trade-journal-wp' ); ?></span>
            </h2>
            <div class="inside">
                <div id="connectionTestContent"></div>
            </div>
        </div>
    </div>

    <!-- Plugin Information -->
    <div class="postbox-container" style="width: 100%; margin-top: 20px;">
        <div class="meta-box-sortables">
            <div class="postbox">
                <h2 class="hndle">
                    <span><?php esc_html_e( 'Plugin Information', 'trade-journal-wp' ); ?></span>
                </h2>
                <div class="inside">
                    <table class="form-table">
                        <tr>
                            <th scope="row"><?php esc_html_e( 'Plugin Version', 'trade-journal-wp' ); ?></th>
                            <td><?php echo esc_html( TRADE_JOURNAL_WP_VERSION ); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"><?php esc_html_e( 'Database Table', 'trade-journal-wp' ); ?></th>
                            <td><code>trading_journal_entries</code></td>
                        </tr>
                        <tr>
                            <th scope="row"><?php esc_html_e( 'External Database', 'trade-journal-wp' ); ?></th>
                            <td>
                                <?php 
                                $database = Trade_Journal_Database::get_instance();
                                if ( $database->test_connection() ) {
                                    echo '<span style="color: #46b450;">✓ ' . esc_html__( 'Connected', 'trade-journal-wp' ) . '</span>';
                                } else {
                                    echo '<span style="color: #dc3232;">✗ ' . esc_html__( 'Connection Failed', 'trade-journal-wp' ) . '</span>';
                                }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php esc_html_e( 'Total Trades', 'trade-journal-wp' ); ?></th>
                            <td>
                                <?php 
                                $trades = $database->get_all_trades();
                                echo esc_html( number_format( count( $trades ) ) );
                                ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Usage Guide -->
    <div class="postbox-container" style="width: 100%; margin-top: 20px;">
        <div class="meta-box-sortables">
            <div class="postbox">
                <h2 class="hndle">
                    <span><?php esc_html_e( 'Quick Setup Guide', 'trade-journal-wp' ); ?></span>
                </h2>
                <div class="inside">
                    <h4><?php esc_html_e( 'Step 1: Configure Database Connection', 'trade-journal-wp' ); ?></h4>
                    <p><?php esc_html_e( 'Update the database configuration above with your external MySQL database credentials. This allows the plugin to use a separate database from your WordPress installation.', 'trade-journal-wp' ); ?></p>
                    
                    <h4><?php esc_html_e( 'Step 2: Test Database Connection', 'trade-journal-wp' ); ?></h4>
                    <p><?php esc_html_e( 'Click the "Test Connection" button to verify that the plugin can connect to your external database successfully.', 'trade-journal-wp' ); ?></p>
                    
                    <h4><?php esc_html_e( 'Step 3: Add Shortcodes to Pages', 'trade-journal-wp' ); ?></h4>
                    <p><?php esc_html_e( 'Create pages or posts and add the trade journal shortcodes:', 'trade-journal-wp' ); ?></p>
                    <ul style="list-style: disc; margin-left: 20px;">
                        <li><code>[trade_journal_dashboard]</code> - <?php esc_html_e( 'Complete trading dashboard', 'trade-journal-wp' ); ?></li>
                        <li><code>[trade_journal_add]</code> - <?php esc_html_e( 'Add new trade form', 'trade-journal-wp' ); ?></li>
                        <li><code>[trade_journal_list]</code> - <?php esc_html_e( 'Trade history table', 'trade-journal-wp' ); ?></li>
                        <li><code>[trade_journal_stats]</code> - <?php esc_html_e( 'Performance statistics', 'trade-journal-wp' ); ?></li>
                        <li><code>[trade_journal_checklist]</code> - <?php esc_html_e( 'Pre-trade strategy checklist', 'trade-journal-wp' ); ?></li>
                        <li><code>[trade_journal_login]</code> - <?php esc_html_e( 'Custom login page with Phoenix design', 'trade-journal-wp' ); ?></li>
                        <li><code>[trade_journal_forgot_password]</code> - <?php esc_html_e( 'Password reset page with Phoenix design', 'trade-journal-wp' ); ?></li>
                        <li><code>[trade_journal_register]</code> - <?php esc_html_e( 'User registration page with Phoenix design', 'trade-journal-wp' ); ?></li>
                    </ul>
                    
                    <h4><?php esc_html_e( 'Step 4: Configure Page Protection', 'trade-journal-wp' ); ?></h4>
                    <p><?php esc_html_e( 'Use the "Protected Pages" section to select which pages require user login. Non-logged-in visitors will be redirected to the login page and returned after successful authentication.', 'trade-journal-wp' ); ?></p>
                    
                    <h4><?php esc_html_e( 'Step 5: Customize Settings', 'trade-journal-wp' ); ?></h4>
                    <p><?php esc_html_e( 'Adjust the available markets, sessions, and timeframes to match your trading preferences using the settings above.', 'trade-journal-wp' ); ?></p>
                    
                    <div class="notice notice-info inline" style="margin-top: 20px;">
                        <p>
                            <strong><?php esc_html_e( 'Note:', 'trade-journal-wp' ); ?></strong>
                            <?php esc_html_e( 'This plugin maintains compatibility with the original Trade Journal PHP application by using the same external database and table structure.', 'trade-journal-wp' ); ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    $('#testDbConnection').on('click', function() {
        var button = $(this);
        var status = $('#connectionStatus');
        var results = $('#connectionTestResults');
        var resultsContent = $('#connectionTestContent');
        
        button.prop('disabled', true).text('<?php esc_js( esc_html__( 'Testing...', 'trade-journal-wp' ) ); ?>');
        status.html('<span style="color: #ffb900;"><?php esc_js( esc_html__( 'Testing connection...', 'trade-journal-wp' ) ); ?></span>');
        
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'trade_journal_test_db_connection',
                nonce: '<?php echo esc_js( wp_create_nonce( 'trade_journal_test_db' ) ); ?>',
                host: $('input[name="trade_journal_wp_db_config[host]"]').val(),
                username: $('input[name="trade_journal_wp_db_config[username]"]').val(),
                password: $('input[name="trade_journal_wp_db_config[password]"]').val(),
                database: $('input[name="trade_journal_wp_db_config[database]"]').val(),
                port: $('input[name="trade_journal_wp_db_config[port]"]').val()
            },
            success: function(response) {
                if (response.success) {
                    status.html('<span style="color: #46b450;">✓ <?php esc_js( esc_html__( 'Connection successful!', 'trade-journal-wp' ) ); ?></span>');
                    resultsContent.html('<div class="notice notice-success inline"><p>' + response.data.message + '</p></div>');
                } else {
                    status.html('<span style="color: #dc3232;">✗ <?php esc_js( esc_html__( 'Connection failed', 'trade-journal-wp' ) ); ?></span>');
                    resultsContent.html('<div class="notice notice-error inline"><p>' + response.data + '</p></div>');
                }
                results.show();
            },
            error: function() {
                status.html('<span style="color: #dc3232;">✗ <?php esc_js( esc_html__( 'Connection test failed', 'trade-journal-wp' ) ); ?></span>');
                resultsContent.html('<div class="notice notice-error inline"><p><?php esc_js( esc_html__( 'Unable to test connection. Please try again.', 'trade-journal-wp' ) ); ?></p></div>');
                results.show();
            },
            complete: function() {
                button.prop('disabled', false).text('<?php esc_js( esc_html__( 'Test Connection', 'trade-journal-wp' ) ); ?>');
            }
        });
    });
});
</script>
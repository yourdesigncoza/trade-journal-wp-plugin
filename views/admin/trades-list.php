<?php
/**
 * Admin Trades List Page
 *
 * @package TradeJournalWP
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$database = Trade_Journal_Database::get_instance();

// Admins can see all trades by default, but can filter by user
$user_id = 'all';
if ( isset( $_GET['user_id'] ) && is_numeric( $_GET['user_id'] ) ) {
    $user_id = intval( $_GET['user_id'] );
}

$trades = $database->get_all_trades( $user_id );
$stats = $database->get_statistics();
?>

<div class="wrap">
    <h1>
        <?php esc_html_e( 'Trade Journal', 'trade-journal-wp' ); ?>
        <a href="<?php echo esc_url( admin_url( 'admin.php?page=trade-journal-wp-add' ) ); ?>" class="page-title-action">
            <?php esc_html_e( 'Add New Trade', 'trade-journal-wp' ); ?>
        </a>
        <a href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin.php?page=trade-journal-wp&action=export_csv' ), 'export_trades' ) ); ?>" class="page-title-action">
            <?php esc_html_e( 'Export CSV', 'trade-journal-wp' ); ?>
        </a>
    </h1>

    <?php
    // Handle export action
    if ( isset( $_GET['action'] ) && $_GET['action'] === 'export_csv' && wp_verify_nonce( $_GET['_wpnonce'], 'export_trades' ) ) {
        $admin = new Trade_Journal_Admin();
        $admin->export_trades_csv();
    }
    ?>

    <!-- Statistics Dashboard -->
    <div class="trade-journal-admin-stats">
        <div class="postbox-container" style="width: 100%;">
            <div class="meta-box-sortables">
                <div class="postbox">
                    <h2 class="hndle">
                        <span><?php esc_html_e( 'Performance Overview', 'trade-journal-wp' ); ?></span>
                    </h2>
                    <div class="inside">
                        <div class="activity-block" style="display: flex; flex-wrap: wrap; gap: 20px;">
                            
                            <div class="stat-box" style="flex: 1; min-width: 150px; padding: 15px; background: #f9f9f9; border-radius: 4px;">
                                <h3 style="margin: 0 0 5px 0; color: #23282d;"><?php echo esc_html( number_format( $stats['total_trades'] ) ); ?></h3>
                                <p style="margin: 0; color: #666;"><?php esc_html_e( 'Total Trades', 'trade-journal-wp' ); ?></p>
                            </div>

                            <div class="stat-box" style="flex: 1; min-width: 150px; padding: 15px; background: #f0f9ff; border-radius: 4px; border-left: 4px solid #0073aa;">
                                <h3 style="margin: 0 0 5px 0; color: #0073aa;"><?php echo esc_html( number_format( $stats['win_rate'], 1 ) ); ?>%</h3>
                                <p style="margin: 0; color: #666;"><?php esc_html_e( 'Win Rate', 'trade-journal-wp' ); ?></p>
                            </div>

                            <div class="stat-box" style="flex: 1; min-width: 150px; padding: 15px; background: <?php echo $stats['total_pl'] >= 0 ? '#f0f9f0' : '#fff0f0'; ?>; border-radius: 4px; border-left: 4px solid <?php echo $stats['total_pl'] >= 0 ? '#46b450' : '#dc3232'; ?>;">
                                <h3 style="margin: 0 0 5px 0; color: <?php echo $stats['total_pl'] >= 0 ? '#46b450' : '#dc3232'; ?>;"><?php echo esc_html( number_format( $stats['total_pl'], 2 ) ); ?>%</h3>
                                <p style="margin: 0; color: #666;"><?php esc_html_e( 'Account Gain', 'trade-journal-wp' ); ?></p>
                            </div>

                            <div class="stat-box" style="flex: 1; min-width: 150px; padding: 15px; background: #fffbf0; border-radius: 4px; border-left: 4px solid #ffb900;">
                                <h3 style="margin: 0 0 5px 0; color: #ffb900;"><?php echo $stats['profit_factor'] > 0 ? esc_html( number_format( $stats['profit_factor'], 2 ) ) : '-'; ?></h3>
                                <p style="margin: 0; color: #666;"><?php esc_html_e( 'Profit Factor', 'trade-journal-wp' ); ?></p>
                            </div>

                        </div>

                        <div style="display: flex; flex-wrap: wrap; gap: 20px; margin-top: 20px;">
                            
                            <div class="outcome-stats" style="flex: 1; min-width: 300px;">
                                <h4><?php esc_html_e( 'Trade Outcomes', 'trade-journal-wp' ); ?></h4>
                                <div style="display: flex; gap: 15px;">
                                    <div style="text-align: center;">
                                        <div style="font-size: 24px; font-weight: bold; color: #46b450;"><?php echo esc_html( number_format( $stats['wins'] ) ); ?></div>
                                        <div style="font-size: 12px; color: #666;"><?php esc_html_e( 'Wins', 'trade-journal-wp' ); ?></div>
                                    </div>
                                    <div style="text-align: center;">
                                        <div style="font-size: 24px; font-weight: bold; color: #dc3232;"><?php echo esc_html( number_format( $stats['losses'] ) ); ?></div>
                                        <div style="font-size: 12px; color: #666;"><?php esc_html_e( 'Losses', 'trade-journal-wp' ); ?></div>
                                    </div>
                                    <div style="text-align: center;">
                                        <div style="font-size: 24px; font-weight: bold; color: #666;"><?php echo esc_html( number_format( $stats['break_even'] ) ); ?></div>
                                        <div style="font-size: 12px; color: #666;"><?php esc_html_e( 'Break Even', 'trade-journal-wp' ); ?></div>
                                    </div>
                                    <div style="text-align: center;">
                                        <div style="font-size: 24px; font-weight: bold; color: #ffb900;"><?php echo esc_html( number_format( $stats['cancelled'] ) ); ?></div>
                                        <div style="font-size: 12px; color: #666;"><?php esc_html_e( 'Cancelled', 'trade-journal-wp' ); ?></div>
                                    </div>
                                </div>
                            </div>

                            <div class="best-worst-trades" style="flex: 1; min-width: 250px;">
                                <h4><?php esc_html_e( 'Best & Worst Trades', 'trade-journal-wp' ); ?></h4>
                                <div style="display: flex; gap: 30px;">
                                    <div>
                                        <div style="font-size: 20px; font-weight: bold; color: #46b450;">
                                            <?php echo $stats['best_trade'] ? esc_html( number_format( $stats['best_trade'], 2 ) ) . '%' : '-'; ?>
                                        </div>
                                        <div style="font-size: 12px; color: #666;"><?php esc_html_e( 'Best Trade', 'trade-journal-wp' ); ?></div>
                                    </div>
                                    <div>
                                        <div style="font-size: 20px; font-weight: bold; color: #dc3232;">
                                            <?php echo $stats['worst_trade'] ? esc_html( number_format( $stats['worst_trade'], 2 ) ) . '%' : '-'; ?>
                                        </div>
                                        <div style="font-size: 12px; color: #666;"><?php esc_html_e( 'Worst Trade', 'trade-journal-wp' ); ?></div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Trades Table -->
    <div class="postbox-container" style="width: 100%;">
        <div class="meta-box-sortables">
            <div class="postbox">
                <h2 class="hndle">
                    <span><?php esc_html_e( 'Recent Trades', 'trade-journal-wp' ); ?></span>
                </h2>
                <div class="inside">
                    
                    <!-- User Filter -->
                    <form method="get" style="margin-bottom: 20px;">
                        <input type="hidden" name="page" value="trade-journal-wp" />
                        <label for="user_id"><?php esc_html_e( 'Filter by User:', 'trade-journal-wp' ); ?></label>
                        <select name="user_id" id="user_id">
                            <option value=""><?php esc_html_e( 'All Users', 'trade-journal-wp' ); ?></option>
                            <?php
                            $users = get_users( array( 'capability' => 'read' ) );
                            foreach ( $users as $user ) :
                                $selected = ( isset( $_GET['user_id'] ) && $_GET['user_id'] == $user->ID ) ? 'selected' : '';
                            ?>
                                <option value="<?php echo esc_attr( $user->ID ); ?>" <?php echo $selected; ?>>
                                    <?php echo esc_html( $user->display_name . ' (' . $user->user_login . ')' ); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <input type="submit" value="<?php esc_attr_e( 'Filter', 'trade-journal-wp' ); ?>" class="button" />
                    </form>
                    
                    <?php if ( ! empty( $trades ) ) : ?>
                    
                    <table class="wp-list-table widefat fixed striped">
                        <thead>
                            <tr>
                                <th><?php esc_html_e( 'Date', 'trade-journal-wp' ); ?></th>
                                <th><?php esc_html_e( 'User', 'trade-journal-wp' ); ?></th>
                                <th><?php esc_html_e( 'Market', 'trade-journal-wp' ); ?></th>
                                <th><?php esc_html_e( 'Session', 'trade-journal-wp' ); ?></th>
                                <th><?php esc_html_e( 'Direction', 'trade-journal-wp' ); ?></th>
                                <th><?php esc_html_e( 'Entry', 'trade-journal-wp' ); ?></th>
                                <th><?php esc_html_e( 'Exit', 'trade-journal-wp' ); ?></th>
                                <th><?php esc_html_e( 'Outcome', 'trade-journal-wp' ); ?></th>
                                <th><?php esc_html_e( 'P/L %', 'trade-journal-wp' ); ?></th>
                                <th><?php esc_html_e( 'RR', 'trade-journal-wp' ); ?></th>
                                <th><?php esc_html_e( 'Actions', 'trade-journal-wp' ); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            // Show only first 20 trades in admin
                            $display_trades = array_slice( $trades, 0, 20 );
                            foreach ( $display_trades as $trade ) :
                            ?>
                            <tr>
                                <td><?php echo esc_html( date( 'M j, Y', strtotime( $trade['date'] ) ) ); ?></td>
                                <td>
                                    <?php 
                                    $user = get_user_by( 'id', $trade['user_id'] );
                                    if ( $user ) {
                                        echo esc_html( $user->display_name );
                                    } else {
                                        echo '<em>Unknown User</em>';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <span class="badge" style="background: #0073aa; color: white; padding: 2px 8px; border-radius: 3px; font-size: 11px;">
                                        <?php echo esc_html( $trade['market'] ); ?>
                                    </span>
                                </td>
                                <td><?php echo esc_html( Trade_Journal_Shortcodes::get_session_options()[ $trade['session'] ] ?? $trade['session'] ); ?></td>
                                <td>
                                    <span class="badge" style="background: <?php echo 'LONG' === $trade['direction'] ? '#46b450' : '#dc3232'; ?>; color: white; padding: 2px 8px; border-radius: 3px; font-size: 11px;">
                                        <?php echo esc_html( $trade['direction'] ); ?>
                                    </span>
                                </td>
                                <td><?php echo esc_html( Trade_Journal_Shortcodes::format_price( $trade['entry_price'] ) ); ?></td>
                                <td><?php echo esc_html( Trade_Journal_Shortcodes::format_price( $trade['exit_price'] ) ); ?></td>
                                <td>
                                    <?php if ( $trade['outcome'] ) : ?>
                                        <?php
                                        $outcome_colors = array(
                                            'W' => '#46b450',
                                            'L' => '#dc3232',
                                            'BE' => '#666',
                                            'C' => '#ffb900'
                                        );
                                        $color = $outcome_colors[ $trade['outcome'] ] ?? '#666';
                                        ?>
                                        <span class="badge" style="background: <?php echo esc_attr( $color ); ?>; color: white; padding: 2px 8px; border-radius: 3px; font-size: 11px;">
                                            <?php echo esc_html( Trade_Journal_Shortcodes::get_outcome_options()[ $trade['outcome'] ] ?? $trade['outcome'] ); ?>
                                        </span>
                                    <?php else : ?>
                                        <span style="color: #666;">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ( $trade['pl_percent'] !== null ) : ?>
                                        <span style="color: <?php echo $trade['pl_percent'] >= 0 ? '#46b450' : '#dc3232'; ?>; font-weight: bold;">
                                            <?php echo esc_html( Trade_Journal_Shortcodes::format_percentage( $trade['pl_percent'] ) ); ?>
                                        </span>
                                    <?php else : ?>
                                        <span style="color: #666;">-</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo esc_html( Trade_Journal_Shortcodes::format_rr( $trade['rr'] ) ); ?></td>
                                <td>
                                    <a href="<?php echo esc_url( admin_url( 'admin.php?page=trade-journal-wp-add&edit=' . $trade['id'] ) ); ?>" class="button button-small">
                                        <?php esc_html_e( 'Edit', 'trade-journal-wp' ); ?>
                                    </a>
                                    <a href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin.php?page=trade-journal-wp&action=delete&id=' . $trade['id'] ), 'delete_trade_' . $trade['id'] ) ); ?>" 
                                       class="button button-small" 
                                       style="color: #dc3232; border-color: #dc3232;"
                                       onclick="return confirm('<?php esc_attr_e( 'Are you sure you want to delete this trade?', 'trade-journal-wp' ); ?>')">
                                        <?php esc_html_e( 'Delete', 'trade-journal-wp' ); ?>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <?php if ( count( $trades ) > 20 ) : ?>
                    <p>
                        <em><?php echo esc_html( sprintf( __( 'Showing 20 of %d trades.', 'trade-journal-wp' ), count( $trades ) ) ); ?></em>
                        <?php esc_html_e( 'Use shortcodes on frontend pages to display all trades with full functionality.', 'trade-journal-wp' ); ?>
                    </p>
                    <?php endif; ?>

                    <?php else : ?>
                    
                    <div style="text-align: center; padding: 40px;">
                        <p style="font-size: 16px; color: #666; margin-bottom: 20px;">
                            <?php esc_html_e( 'No trades found. Start tracking your trading performance!', 'trade-journal-wp' ); ?>
                        </p>
                        <a href="<?php echo esc_url( admin_url( 'admin.php?page=trade-journal-wp-add' ) ); ?>" class="button button-primary button-large">
                            <?php esc_html_e( 'Add Your First Trade', 'trade-journal-wp' ); ?>
                        </a>
                    </div>
                    
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>

    <!-- Shortcode Information -->
    <div class="postbox-container" style="width: 100%;">
        <div class="meta-box-sortables">
            <div class="postbox">
                <h2 class="hndle">
                    <span><?php esc_html_e( 'Available Shortcodes', 'trade-journal-wp' ); ?></span>
                </h2>
                <div class="inside">
                    <p><?php esc_html_e( 'Use these shortcodes to display trade journal components on your pages and posts:', 'trade-journal-wp' ); ?></p>
                    
                    <table class="form-table">
                        <tr>
                            <th scope="row"><?php esc_html_e( 'Complete Dashboard', 'trade-journal-wp' ); ?></th>
                            <td>
                                <code>[trade_journal_dashboard]</code>
                                <p class="description"><?php esc_html_e( 'Displays the complete trading dashboard with form, statistics, and trade list.', 'trade-journal-wp' ); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php esc_html_e( 'Add Trade Form', 'trade-journal-wp' ); ?></th>
                            <td>
                                <code>[trade_journal_add]</code>
                                <p class="description"><?php esc_html_e( 'Shows only the add new trade form.', 'trade-journal-wp' ); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php esc_html_e( 'Trade List', 'trade-journal-wp' ); ?></th>
                            <td>
                                <code>[trade_journal_list]</code>
                                <p class="description"><?php esc_html_e( 'Displays the trade history table with sorting and filtering.', 'trade-journal-wp' ); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php esc_html_e( 'Statistics', 'trade-journal-wp' ); ?></th>
                            <td>
                                <code>[trade_journal_stats]</code>
                                <p class="description"><?php esc_html_e( 'Shows performance analytics and statistics.', 'trade-journal-wp' ); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php esc_html_e( 'Strategy Checklist', 'trade-journal-wp' ); ?></th>
                            <td>
                                <code>[trade_journal_checklist]</code>
                                <p class="description"><?php esc_html_e( 'Displays the pre-trade strategy checklist.', 'trade-journal-wp' ); ?></p>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

<?php
// Handle delete action
if ( isset( $_GET['action'] ) && $_GET['action'] === 'delete' && isset( $_GET['id'] ) ) {
    $trade_id = sanitize_text_field( $_GET['id'] );
    
    if ( wp_verify_nonce( $_GET['_wpnonce'], 'delete_trade_' . $trade_id ) ) {
        $result = $database->delete_trade( $trade_id );
        
        if ( $result ) {
            echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'Trade deleted successfully.', 'trade-journal-wp' ) . '</p></div>';
            echo '<script>setTimeout(function(){ location.reload(); }, 1500);</script>';
        } else {
            echo '<div class="notice notice-error is-dismissible"><p>' . esc_html__( 'Failed to delete trade.', 'trade-journal-wp' ) . '</p></div>';
        }
    }
}
?>
<?php
/**
 * Admin Add Trade Page
 *
 * @package TradeJournalWP
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$database = Trade_Journal_Database::get_instance();
$is_edit = isset( $_GET['edit'] ) && ! empty( $_GET['edit'] );
$edit_trade = null;

if ( $is_edit ) {
    $trade_id = sanitize_text_field( $_GET['edit'] );
    $edit_trade = $database->get_trade( $trade_id );
    
    if ( ! $edit_trade ) {
        wp_die( esc_html__( 'Trade not found.', 'trade-journal-wp' ) );
    }
}

// Handle form submission
if ( isset( $_POST['submit_trade'] ) && wp_verify_nonce( $_POST['_wpnonce'], 'add_trade_admin' ) ) {
    $trade_data = array(
        'market'      => sanitize_text_field( $_POST['market'] ),
        'session'     => sanitize_text_field( $_POST['session'] ),
        'date'        => sanitize_text_field( $_POST['date'] ),
        'time'        => sanitize_text_field( $_POST['time'] ),
        'direction'   => sanitize_text_field( $_POST['direction'] ),
        'entry_price' => floatval( $_POST['entry_price'] ),
        'exit_price'  => floatval( $_POST['exit_price'] ),
        'outcome'     => sanitize_text_field( $_POST['outcome'] ),
        'pl_percent'  => floatval( $_POST['pl_percent'] ),
        'rr'          => floatval( $_POST['rr'] ),
        'tf'          => isset( $_POST['tf'] ) && is_array( $_POST['tf'] ) ? array_map( 'sanitize_text_field', $_POST['tf'] ) : array(),
        'chart_htf'   => esc_url_raw( $_POST['chart_htf'] ),
        'chart_ltf'   => esc_url_raw( $_POST['chart_ltf'] ),
        'comments'    => sanitize_textarea_field( wp_unslash( $_POST['comments'] ) ),
        'order_type'  => sanitize_text_field( $_POST['order_type'] ?? '' ),
        'strategy'    => sanitize_text_field( $_POST['strategy'] ?? '' ),
        'stop_loss'   => floatval( $_POST['stop_loss'] ?? 0 ),
        'take_profit' => floatval( $_POST['take_profit'] ?? 0 ),
        'absolute_pl' => floatval( $_POST['absolute_pl'] ?? 0 ),
        'disciplined' => sanitize_text_field( $_POST['disciplined'] ?? '' ),
        'followed_rules' => sanitize_text_field( $_POST['followed_rules'] ?? '' ),
        'rating'      => intval( $_POST['rating'] ?? 0 ),
    );

    if ( $is_edit ) {
        $result = $database->update_trade( $edit_trade['id'], $trade_data );
        $success_message = __( 'Trade updated successfully!', 'trade-journal-wp' );
    } else {
        $result = $database->save_trade( $trade_data );
        $success_message = __( 'Trade saved successfully!', 'trade-journal-wp' );
    }

    if ( $result ) {
        echo '<div class="alert alert-subtle-success is-dismissible"><p>' . esc_html( $success_message ) . '</p></div>';
        if ( ! $is_edit ) {
            // Clear form data after successful save
            $edit_trade = null;
        } else {
            // Refresh trade data
            $edit_trade = $database->get_trade( $edit_trade['id'] );
        }
    } else {
        echo '<div class="alert alert-subtle-danger is-dismissible"><p>' . esc_html__( 'Failed to save trade. Please try again.', 'trade-journal-wp' ) . '</p></div>';
    }
}
?>

<div class="wrap">
    <h1>
        <?php echo $is_edit ? esc_html__( 'Edit Trade', 'trade-journal-wp' ) : esc_html__( 'Add New Trade', 'trade-journal-wp' ); ?>
        <a href="<?php echo esc_url( admin_url( 'admin.php?page=trade-journal-wp' ) ); ?>" class="page-title-action">
            <?php esc_html_e( '← Back to Trades', 'trade-journal-wp' ); ?>
        </a>
    </h1>

    <form method="post" action="">
        <?php wp_nonce_field( 'add_trade_admin' ); ?>
        
        <div class="postbox-container" style="width: 100%;">
            <div class="meta-box-sortables">
                
                <!-- Trade Basics -->
                <div class="postbox">
                    <h2 class="hndle"><span><?php esc_html_e( 'Trade Basics', 'trade-journal-wp' ); ?></span></h2>
                    <div class="inside">
                        <table class="form-table">
                            <tr>
                                <th scope="row">
                                    <label for="market"><?php esc_html_e( 'Market', 'trade-journal-wp' ); ?> <span style="color: red;">*</span></label>
                                </th>
                                <td>
                                    <select name="market" id="market" required>
                                        <option value=""><?php esc_html_e( 'Select a market', 'trade-journal-wp' ); ?></option>
                                        <?php foreach ( Trade_Journal_Shortcodes::get_market_options() as $value => $label ) : ?>
                                            <option value="<?php echo esc_attr( $value ); ?>" <?php selected( $edit_trade['market'] ?? '', $value ); ?>>
                                                <?php echo esc_html( $label ); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="session"><?php esc_html_e( 'Session', 'trade-journal-wp' ); ?> <span style="color: red;">*</span></label>
                                </th>
                                <td>
                                    <select name="session" id="session" required>
                                        <option value=""><?php esc_html_e( 'Select a session', 'trade-journal-wp' ); ?></option>
                                        <?php foreach ( Trade_Journal_Shortcodes::get_session_options() as $value => $label ) : ?>
                                            <option value="<?php echo esc_attr( $value ); ?>" <?php selected( $edit_trade['session'] ?? '', $value ); ?>>
                                                <?php echo esc_html( $label ); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="date"><?php esc_html_e( 'Date', 'trade-journal-wp' ); ?> <span style="color: red;">*</span></label>
                                </th>
                                <td>
                                    <input type="date" name="date" id="date" value="<?php echo esc_attr( $edit_trade['date'] ?? current_time( 'Y-m-d' ) ); ?>" required />
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="time"><?php esc_html_e( 'Time', 'trade-journal-wp' ); ?></label>
                                </th>
                                <td>
                                    <input type="time" name="time" id="time" value="<?php echo esc_attr( $edit_trade['time'] ?? current_time('H:i') ); ?>" />
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="direction"><?php esc_html_e( 'Direction', 'trade-journal-wp' ); ?> <span style="color: red;">*</span></label>
                                </th>
                                <td>
                                    <select name="direction" id="direction" required>
                                        <option value=""><?php esc_html_e( 'Select direction', 'trade-journal-wp' ); ?></option>
                                        <?php foreach ( Trade_Journal_Shortcodes::get_direction_options() as $value => $label ) : ?>
                                            <option value="<?php echo esc_attr( $value ); ?>" <?php selected( $edit_trade['direction'] ?? '', $value ); ?>>
                                                <?php echo esc_html( $label ); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="order_type"><?php esc_html_e( 'Order Type', 'trade-journal-wp' ); ?></label>
                                </th>
                                <td>
                                    <select name="order_type" id="order_type">
                                        <option value=""><?php esc_html_e( 'Select order type', 'trade-journal-wp' ); ?></option>
                                        <option value="Market" <?php selected( $edit_trade['order_type'] ?? '', 'Market' ); ?>><?php esc_html_e( 'Market', 'trade-journal-wp' ); ?></option>
                                        <option value="Limit" <?php selected( $edit_trade['order_type'] ?? '', 'Limit' ); ?>><?php esc_html_e( 'Limit', 'trade-journal-wp' ); ?></option>
                                        <option value="Stop" <?php selected( $edit_trade['order_type'] ?? '', 'Stop' ); ?>><?php esc_html_e( 'Stop', 'trade-journal-wp' ); ?></option>
                                    </select>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Trade Setup -->
                <div class="postbox">
                    <h2 class="hndle"><span><?php esc_html_e( 'Trade Setup', 'trade-journal-wp' ); ?></span></h2>
                    <div class="inside">
                        <table class="form-table">
                            <tr>
                                <th scope="row">
                                    <label for="strategy"><?php esc_html_e( 'Strategy Name', 'trade-journal-wp' ); ?></label>
                                </th>
                                <td>
                                    <input type="text" name="strategy" id="strategy" class="regular-text" 
                                           value="<?php echo esc_attr( $edit_trade['strategy'] ?? '' ); ?>" 
                                           placeholder="<?php esc_attr_e( 'e.g., FTR Breakout, Support/Resistance', 'trade-journal-wp' ); ?>" />
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="stop_loss"><?php esc_html_e( 'Stop Loss', 'trade-journal-wp' ); ?></label>
                                </th>
                                <td>
                                    <input type="number" step="0.00001" name="stop_loss" id="stop_loss" 
                                           value="<?php echo esc_attr( $edit_trade['stop_loss'] ?? '' ); ?>" 
                                           placeholder="0.00000" />
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="take_profit"><?php esc_html_e( 'Take Profit', 'trade-journal-wp' ); ?></label>
                                </th>
                                <td>
                                    <input type="number" step="0.00001" name="take_profit" id="take_profit" 
                                           value="<?php echo esc_attr( $edit_trade['take_profit'] ?? '' ); ?>" 
                                           placeholder="0.00000" />
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="entry_price"><?php esc_html_e( 'Entry Price', 'trade-journal-wp' ); ?></label>
                                </th>
                                <td>
                                    <input type="number" step="0.00001" name="entry_price" id="entry_price" 
                                           value="<?php echo esc_attr( $edit_trade['entry_price'] ?? '' ); ?>" 
                                           placeholder="0.00000" />
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="exit_price"><?php esc_html_e( 'Exit Price', 'trade-journal-wp' ); ?></label>
                                </th>
                                <td>
                                    <input type="number" step="0.00001" name="exit_price" id="exit_price" 
                                           value="<?php echo esc_attr( $edit_trade['exit_price'] ?? '' ); ?>" 
                                           placeholder="0.00000" />
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Trade Performance -->
                <div class="postbox">
                    <h2 class="hndle"><span><?php esc_html_e( 'Trade Performance', 'trade-journal-wp' ); ?></span></h2>
                    <div class="inside">
                        <table class="form-table">
                            <tr>
                                <th scope="row">
                                    <label for="outcome"><?php esc_html_e( 'Outcome', 'trade-journal-wp' ); ?></label>
                                </th>
                                <td>
                                    <select name="outcome" id="outcome">
                                        <option value=""><?php esc_html_e( 'Select outcome', 'trade-journal-wp' ); ?></option>
                                        <?php foreach ( Trade_Journal_Shortcodes::get_outcome_options() as $value => $label ) : ?>
                                            <option value="<?php echo esc_attr( $value ); ?>" <?php selected( $edit_trade['outcome'] ?? '', $value ); ?>>
                                                <?php echo esc_html( $label ); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="pl_percent"><?php esc_html_e( 'P/L %', 'trade-journal-wp' ); ?></label>
                                </th>
                                <td>
                                    <input type="number" step="0.01" name="pl_percent" id="pl_percent" 
                                           value="<?php echo esc_attr( $edit_trade['pl_percent'] ?? '' ); ?>" 
                                           placeholder="0.00" />
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="rr"><?php esc_html_e( 'Risk/Reward Ratio', 'trade-journal-wp' ); ?></label>
                                </th>
                                <td>
                                    <input type="number" step="0.01" name="rr" id="rr" 
                                           value="<?php echo esc_attr( $edit_trade['rr'] ?? '' ); ?>" 
                                           placeholder="1.0" />
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="absolute_pl"><?php esc_html_e( 'Absolute P/L', 'trade-journal-wp' ); ?></label>
                                </th>
                                <td>
                                    <input type="number" step="0.01" name="absolute_pl" id="absolute_pl" 
                                           value="<?php echo esc_attr( $edit_trade['absolute_pl'] ?? '' ); ?>" 
                                           placeholder="0.00" />
                                    <p class="description"><?php esc_html_e( 'Absolute profit/loss amount in account currency', 'trade-journal-wp' ); ?></p>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Analysis & Charts -->
                <div class="postbox">
                    <h2 class="hndle"><span><?php esc_html_e( 'Analysis & Charts', 'trade-journal-wp' ); ?></span></h2>
                    <div class="inside">
                        <table class="form-table">
                            <tr>
                                <th scope="row">
                                    <label><?php esc_html_e( 'Timeframes', 'trade-journal-wp' ); ?></label>
                                </th>
                                <td>
                                    <fieldset>
                                        <?php 
                                        $selected_tf = $edit_trade['tf'] ?? array();
                                        foreach ( Trade_Journal_Shortcodes::get_timeframe_options() as $value => $label ) : 
                                        ?>
                                            <label>
                                                <input type="checkbox" name="tf[]" value="<?php echo esc_attr( $value ); ?>" 
                                                       <?php checked( in_array( $value, $selected_tf, true ) ); ?> />
                                                <?php echo esc_html( $label ); ?>
                                            </label><br>
                                        <?php endforeach; ?>
                                    </fieldset>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="chart_htf"><?php esc_html_e( 'Chart HTF (Higher Timeframe)', 'trade-journal-wp' ); ?></label>
                                </th>
                                <td>
                                    <input type="url" name="chart_htf" id="chart_htf" class="regular-text" 
                                           value="<?php echo esc_attr( $edit_trade['chart_htf'] ?? '' ); ?>" 
                                           placeholder="https://www.tradingview.com/..." />
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="chart_ltf"><?php esc_html_e( 'Chart LTF (Lower Timeframe)', 'trade-journal-wp' ); ?></label>
                                </th>
                                <td>
                                    <input type="url" name="chart_ltf" id="chart_ltf" class="regular-text" 
                                           value="<?php echo esc_attr( $edit_trade['chart_ltf'] ?? '' ); ?>" 
                                           placeholder="https://www.tradingview.com/..." />
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Trade Review -->
                <div class="postbox">
                    <h2 class="hndle"><span><?php esc_html_e( 'Trade Review', 'trade-journal-wp' ); ?></span></h2>
                    <div class="inside">
                        <table class="form-table">
                            <tr>
                                <th scope="row">
                                    <label><?php esc_html_e( 'Disciplined', 'trade-journal-wp' ); ?></label>
                                </th>
                                <td>
                                    <fieldset>
                                        <label>
                                            <input type="radio" name="disciplined" value="Y" <?php checked( $edit_trade['disciplined'] ?? '', 'Y' ); ?> />
                                            <?php esc_html_e( 'Yes', 'trade-journal-wp' ); ?>
                                        </label><br>
                                        <label>
                                            <input type="radio" name="disciplined" value="N" <?php checked( $edit_trade['disciplined'] ?? '', 'N' ); ?> />
                                            <?php esc_html_e( 'No', 'trade-journal-wp' ); ?>
                                        </label>
                                    </fieldset>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label><?php esc_html_e( 'Followed Rules', 'trade-journal-wp' ); ?></label>
                                </th>
                                <td>
                                    <fieldset>
                                        <label>
                                            <input type="radio" name="followed_rules" value="Y" <?php checked( $edit_trade['followed_rules'] ?? '', 'Y' ); ?> />
                                            <?php esc_html_e( 'Yes', 'trade-journal-wp' ); ?>
                                        </label><br>
                                        <label>
                                            <input type="radio" name="followed_rules" value="N" <?php checked( $edit_trade['followed_rules'] ?? '', 'N' ); ?> />
                                            <?php esc_html_e( 'No', 'trade-journal-wp' ); ?>
                                        </label>
                                    </fieldset>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="rating"><?php esc_html_e( 'Rating', 'trade-journal-wp' ); ?></label>
                                </th>
                                <td>
                                    <select name="rating" id="rating">
                                        <option value=""><?php esc_html_e( 'Select rating', 'trade-journal-wp' ); ?></option>
                                        <?php for ( $i = 1; $i <= 5; $i++ ) : ?>
                                            <option value="<?php echo $i; ?>" <?php selected( $edit_trade['rating'] ?? '', $i ); ?>>
                                                <?php echo str_repeat( '★', $i ) . str_repeat( '☆', 5 - $i ); ?> (<?php echo $i; ?>/5)
                                            </option>
                                        <?php endfor; ?>
                                    </select>
                                    <p class="description"><?php esc_html_e( 'Rate the quality of this trade execution', 'trade-journal-wp' ); ?></p>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Comments -->
                <div class="postbox">
                    <h2 class="hndle"><span><?php esc_html_e( 'Comments & Notes', 'trade-journal-wp' ); ?></span></h2>
                    <div class="inside">
                        <table class="form-table">
                            <tr>
                                <th scope="row">
                                    <label for="comments"><?php esc_html_e( 'Comments', 'trade-journal-wp' ); ?></label>
                                </th>
                                <td>
                                    <textarea name="comments" id="comments" rows="5" class="large-text" 
                                              placeholder="<?php esc_attr_e( 'Add any notes about this trade...', 'trade-journal-wp' ); ?>"><?php echo esc_textarea( $edit_trade['comments'] ?? '' ); ?></textarea>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

            </div>
        </div>

        <p class="submit">
            <input type="submit" name="submit_trade" class="button-primary" 
                   value="<?php echo $is_edit ? esc_attr__( 'Update Trade', 'trade-journal-wp' ) : esc_attr__( 'Save Trade', 'trade-journal-wp' ); ?>" />
            <a href="<?php echo esc_url( admin_url( 'admin.php?page=trade-journal-wp' ) ); ?>" class="button">
                <?php esc_html_e( 'Cancel', 'trade-journal-wp' ); ?>
            </a>
        </p>
    </form>
</div>
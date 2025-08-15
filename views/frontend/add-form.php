<?php
/**
 * Add Trade Form Frontend View
 *
 * @package TradeJournalWP
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<div class="card shadow-none border mb-3">
    <div class="card-body p-4">
        <h5 class="text-body mb-4">
            <i class="fas fa-plus-circle me-2"></i>
            <?php esc_html_e( 'Journal Your Trades', 'trade-journal-wp' ); ?>
        </h5>
        
        <form id="tradeJournalForm" class="trade-journal-form">
            <?php wp_nonce_field( 'trade_journal_wp_nonce', 'trade_journal_nonce' ); ?>
            
            <!-- Trade Basics Section -->
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <label class="form-label">
                        <i class="fas fa-coins me-2 text-primary"></i>
                        <?php esc_html_e( 'Market', 'trade-journal-wp' ); ?>
                    </label>
                    <select class="form-select form-select-sm" name="market" required>
                        <option value=""><?php esc_html_e( 'Select an option', 'trade-journal-wp' ); ?></option>
                        <?php foreach ( Trade_Journal_Shortcodes::get_market_options() as $value => $label ) : ?>
                            <option value="<?php echo esc_attr( $value ); ?>"><?php echo esc_html( $label ); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">
                        <i class="fas fa-globe me-2 text-info"></i>
                        <?php esc_html_e( 'Session', 'trade-journal-wp' ); ?>
                    </label>
                    <select class="form-select form-select-sm" name="session" required>
                        <option value=""><?php esc_html_e( 'Select an option', 'trade-journal-wp' ); ?></option>
                        <?php foreach ( Trade_Journal_Shortcodes::get_session_options() as $value => $label ) : ?>
                            <option value="<?php echo esc_attr( $value ); ?>"><?php echo esc_html( $label ); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">
                        <i class="fas fa-calendar-alt me-2 text-primary"></i>
                        <?php esc_html_e( 'Date', 'trade-journal-wp' ); ?>
                    </label>
                    <input type="date" class="form-control form-control-sm" name="date" value="<?php echo current_time('Y-m-d'); ?>" required>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">
                        <i class="fas fa-clock me-2 text-info"></i>
                        <?php esc_html_e( 'Time', 'trade-journal-wp' ); ?>
                    </label>
                    <input type="time" class="form-control form-control-sm" name="time" value="<?php echo current_time('H:i'); ?>">
                </div>
            </div>
            
            <div class="row g-3 mb-4">
                <div class="col-md-2">
                    <label class="form-label">
                        <i class="fas fa-arrows-alt-v me-2 text-warning"></i>
                        <?php esc_html_e( 'Direction', 'trade-journal-wp' ); ?>
                    </label>
                    <select class="form-select form-select-sm" name="direction" required>
                        <option value=""><?php esc_html_e( 'Select an option', 'trade-journal-wp' ); ?></option>
                        <?php foreach ( Trade_Journal_Shortcodes::get_direction_options() as $value => $label ) : ?>
                            <option value="<?php echo esc_attr( $value ); ?>"><?php echo esc_html( $label ); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="col-md-2">
                    <label class="form-label">
                        <i class="fas fa-exchange-alt me-2 text-warning"></i>
                        <?php esc_html_e( 'Order Type', 'trade-journal-wp' ); ?>
                    </label>
                    <select class="form-select form-select-sm" name="order_type">
                        <option value=""><?php esc_html_e( 'Select order type', 'trade-journal-wp' ); ?></option>
                        <option value="Market"><?php esc_html_e( 'Market', 'trade-journal-wp' ); ?></option>
                        <option value="Limit"><?php esc_html_e( 'Limit', 'trade-journal-wp' ); ?></option>
                        <option value="Stop"><?php esc_html_e( 'Stop', 'trade-journal-wp' ); ?></option>
                    </select>
                </div>
                
                <div class="col-md-2">
                    <label class="form-label">
                        <i class="fas fa-trophy me-2 text-warning"></i>
                        <?php esc_html_e( 'Outcome', 'trade-journal-wp' ); ?>
                    </label>
                    <select class="form-select form-select-sm" name="outcome">
                        <option value=""><?php esc_html_e( 'Select outcome', 'trade-journal-wp' ); ?></option>
                        <?php foreach ( Trade_Journal_Shortcodes::get_outcome_options() as $value => $label ) : ?>
                            <option value="<?php echo esc_attr( $value ); ?>"><?php echo esc_html( $label ); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">
                        <i class="fas fa-chess me-2 text-primary"></i>
                        <?php esc_html_e( 'Strategy Name', 'trade-journal-wp' ); ?>
                    </label>
                    <input type="text" class="form-control form-control-sm" name="strategy" placeholder="<?php esc_attr_e( 'e.g., FTR Breakout, Support/Resistance', 'trade-journal-wp' ); ?>">
                </div>
            </div>

            <!-- Trade Setup Section -->
            <div class="row g-3 mb-4">

                <div class="col-md-3">
                    <label class="form-label">
                        <i class="fas fa-sign-in-alt me-2 text-success"></i>
                        <?php esc_html_e( 'Entry Price', 'trade-journal-wp' ); ?>
                    </label>
                    <input type="number" step="0.00001" class="form-control form-control-sm" name="entry_price" placeholder="0.00000">
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">
                        <i class="fas fa-sign-out-alt me-2 text-danger"></i>
                        <?php esc_html_e( 'Exit Price', 'trade-journal-wp' ); ?>
                    </label>
                    <input type="number" step="0.00001" class="form-control form-control-sm" name="exit_price" placeholder="0.00000">
                </div>
                <div class="col-md-3">
                    <label class="form-label">
                        <i class="fas fa-shield-alt me-2 text-danger"></i>
                        <?php esc_html_e( 'Stop Loss', 'trade-journal-wp' ); ?>
                    </label>
                    <input type="number" step="0.00001" class="form-control form-control-sm" name="stop_loss" placeholder="0.00000">
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">
                        <i class="fas fa-target me-2 text-success"></i>
                        <?php esc_html_e( 'Take Profit', 'trade-journal-wp' ); ?>
                    </label>
                    <input type="number" step="0.00001" class="form-control form-control-sm" name="take_profit" placeholder="0.00000">
                </div>
            </div>

            <!-- Trade Performance Section -->
            <div class="row g-3 mb-4">
                <div class="col-md-2">
                    <label class="form-label">
                        <i class="fas fa-percentage me-2 text-success"></i>
                        <?php esc_html_e( 'P/L %', 'trade-journal-wp' ); ?>
                    </label>
                    <input type="number" step="0.01" class="form-control form-control-sm" name="pl_percent" placeholder="0.00">
                </div>
                
                <div class="col-md-2">
                    <label class="form-label">
                        <i class="fas fa-balance-scale me-2 text-success"></i>
                        <?php esc_html_e( 'Risk/Reward', 'trade-journal-wp' ); ?>
                    </label>
                    <input type="number" step="0.01" class="form-control form-control-sm" name="rr" placeholder="1.0">
                </div>
                
                <div class="col-md-2">
                    <label class="form-label">
                        <i class="fas fa-dollar-sign me-2 text-success"></i>
                        <?php esc_html_e( 'Absolute P/L', 'trade-journal-wp' ); ?>
                    </label>
                    <input type="number" step="0.01" class="form-control form-control-sm" name="absolute_pl" placeholder="0.00">
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">
                        <i class="fas fa-chart-line me-2 text-info"></i>
                        <?php esc_html_e( 'Timeframes', 'trade-journal-wp' ); ?>
                    </label>
                    <div class="row g-1">
                        <?php foreach ( Trade_Journal_Shortcodes::get_timeframe_options() as $value => $label ) : ?>
                            <div class="col-auto">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="tf[]" value="<?php echo esc_attr( $value ); ?>" id="tf<?php echo esc_attr( $value ); ?>">
                                    <label class="form-check-label" for="tf<?php echo esc_attr( $value ); ?>">
                                        <?php echo esc_html( $value ); ?>
                                    </label>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            
            <!-- Charts Section -->
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label">
                        <i class="fas fa-chart-bar me-2 text-warning"></i>
                        <?php esc_html_e( 'Chart HTF', 'trade-journal-wp' ); ?>
                    </label>
                    <input type="url" class="form-control form-control-sm" name="chart_htf" placeholder="https://www.tradingview.com/...">
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">
                        <i class="fas fa-chart-area me-2 text-warning"></i>
                        <?php esc_html_e( 'Chart LTF', 'trade-journal-wp' ); ?>
                    </label>
                    <input type="url" class="form-control form-control-sm" name="chart_ltf" placeholder="https://www.tradingview.com/...">
                </div>
            </div>

                        <!-- Trade Review Section -->
            <div class="row g-3 mb-4">
                <div class="col-md-2 mt-5">
                    <label class="form-label">
                        <i class="fas fa-user-check me-2 text-info"></i>
                        <?php esc_html_e( 'Disciplined', 'trade-journal-wp' ); ?>
                    </label><br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="disciplined" id="disciplinedY" value="Y">
                        <label class="form-check-label" for="disciplinedY"><?php esc_html_e( 'Yes', 'trade-journal-wp' ); ?></label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="disciplined" id="disciplinedN" value="N">
                        <label class="form-check-label" for="disciplinedN"><?php esc_html_e( 'No', 'trade-journal-wp' ); ?></label>
                    </div>
                </div>
                
                <div class="col-md-2 mt-5">
                    <label class="form-label">
                        <i class="fas fa-clipboard-check me-2 text-info"></i>
                        <?php esc_html_e( 'Followed Rules', 'trade-journal-wp' ); ?>
                    </label><br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="followed_rules" id="followedY" value="Y">
                        <label class="form-check-label" for="followedY"><?php esc_html_e( 'Yes', 'trade-journal-wp' ); ?></label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="followed_rules" id="followedN" value="N">
                        <label class="form-check-label" for="followedN"><?php esc_html_e( 'No', 'trade-journal-wp' ); ?></label>
                    </div>
                </div>
                
                <div class="col-md-2 mt-5">
                    <label class="form-label">
                        <i class="fas fa-star me-2 text-warning"></i>
                        <?php esc_html_e( 'Rating', 'trade-journal-wp' ); ?>
                    </label>
                    <select class="form-select form-select-sm" name="rating">
                        <option value=""><?php esc_html_e( 'Select rating', 'trade-journal-wp' ); ?></option>
                        <option value="1">★☆☆☆☆ (1/5)</option>
                        <option value="2">★★☆☆☆ (2/5)</option>
                        <option value="3">★★★☆☆ (3/5)</option>
                        <option value="4">★★★★☆ (4/5)</option>
                        <option value="5">★★★★★ (5/5)</option>
                    </select>
                </div>
                <div class="col-6">
                    <label class="form-label">
                        <i class="fas fa-comment me-2 text-info"></i>
                        <?php esc_html_e( 'Comments', 'trade-journal-wp' ); ?>
                    </label>
                    <textarea class="form-control form-control-sm" name="comments" rows="3" placeholder="<?php esc_attr_e( 'Add any additional notes about this trade...', 'trade-journal-wp' ); ?>"></textarea>
                </div>

            </div>
            <!-- Submit Button -->
            <div class="row">
                <div class="col-12">
                    <button type="submit" class="btn btn-subtle-primary px-5" id="submitBtn">
                        <i class="fas fa-save me-2"></i>
                        <span id="submitText"><?php esc_html_e( 'Save Trade Entry', 'trade-journal-wp' ); ?></span>
                        <span id="submitSpinner" class="spinner-border spinner-border-sm ms-2 d-none"></span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Success/Error Messages -->
<div id="tradeJournalMessages"></div>
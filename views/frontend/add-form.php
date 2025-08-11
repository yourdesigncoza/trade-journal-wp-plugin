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
                        <i class="fas fa-globe me-2 text-primary"></i>
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
                        <i class="fas fa-clock me-2 text-primary"></i>
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
                        <i class="fas fa-calendar me-2 text-primary"></i>
                        <?php esc_html_e( 'Date', 'trade-journal-wp' ); ?>
                    </label>
                    <input type="date" class="form-control form-control-sm" name="date" required>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">
                        <i class="fas fa-clock me-2 text-primary"></i>
                        <?php esc_html_e( 'Time', 'trade-journal-wp' ); ?>
                    </label>
                    <input type="time" class="form-control form-control-sm" name="time">
                </div>
            </div>

            <!-- Performance Section -->
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <label class="form-label">
                        <i class="fas fa-trending-up me-2 text-success"></i>
                        <?php esc_html_e( 'Direction', 'trade-journal-wp' ); ?>
                    </label>
                    <select class="form-select form-select-sm" name="direction" required>
                        <option value=""><?php esc_html_e( 'Select an option', 'trade-journal-wp' ); ?></option>
                        <?php foreach ( Trade_Journal_Shortcodes::get_direction_options() as $value => $label ) : ?>
                            <option value="<?php echo esc_attr( $value ); ?>"><?php echo esc_html( $label ); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">
                        <i class="fas fa-dollar-sign me-2 text-success"></i>
                        <?php esc_html_e( 'Entry Price', 'trade-journal-wp' ); ?>
                    </label>
                    <input type="number" step="0.00001" class="form-control form-control-sm" name="entry_price" placeholder="0.00000">
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">
                        <i class="fas fa-dollar-sign me-2 text-success"></i>
                        <?php esc_html_e( 'Exit Price', 'trade-journal-wp' ); ?>
                    </label>
                    <input type="number" step="0.00001" class="form-control form-control-sm" name="exit_price" placeholder="0.00000">
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">
                        <i class="fas fa-chart-bar me-2 text-success"></i>
                        <?php esc_html_e( 'Outcome', 'trade-journal-wp' ); ?>
                    </label>
                    <select class="form-select form-select-sm" name="outcome">
                        <option value=""><?php esc_html_e( 'Select an option', 'trade-journal-wp' ); ?></option>
                        <?php foreach ( Trade_Journal_Shortcodes::get_outcome_options() as $value => $label ) : ?>
                            <option value="<?php echo esc_attr( $value ); ?>"><?php echo esc_html( $label ); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- Metrics Section -->
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <label class="form-label">
                        <i class="fas fa-chart-bar me-2 text-info"></i>
                        <?php esc_html_e( 'P/L %', 'trade-journal-wp' ); ?>
                    </label>
                    <input type="number" step="0.01" class="form-control form-control-sm" name="pl_percent" placeholder="0.00">
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">
                        <i class="fas fa-chart-bar me-2 text-info"></i>
                        <?php esc_html_e( 'RR', 'trade-journal-wp' ); ?>
                    </label>
                    <input type="number" step="0.01" class="form-control form-control-sm" name="rr" placeholder="1.0">
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">
                        <i class="fas fa-search me-2 text-info"></i>
                        <?php esc_html_e( 'Timeframes', 'trade-journal-wp' ); ?>
                    </label>
                    <div class="row g-2">
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
                        <i class="fas fa-chart-line me-2 text-warning"></i>
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

            <!-- Comments Section -->
            <div class="row g-3 mb-4">
                <div class="col-12">
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
                    <button type="submit" class="btn btn-primary px-5" id="submitBtn">
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
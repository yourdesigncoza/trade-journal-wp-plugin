<?php
/**
 * Strategy Checklist Frontend View
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
            <i class="fas fa-clipboard-check me-2"></i>
            <?php esc_html_e( 'Pre-Trade Strategy Checklist', 'trade-journal-wp' ); ?>
        </h5>
        
        <div class="checklist-container" id="strategyChecklist">
            
            <!-- Market Analysis Section -->
            <div class="mb-4">
                <h6 class="text-primary border-bottom pb-2 mb-3">
                    <i class="fas fa-search me-2"></i>
                    <?php esc_html_e( 'Market Analysis', 'trade-journal-wp' ); ?>
                </h6>
                
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="check_market_trend">
                    <label class="form-check-label" for="check_market_trend">
                        <?php esc_html_e( 'Market trend identified and confirmed', 'trade-journal-wp' ); ?>
                    </label>
                </div>
                
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="check_support_resistance">
                    <label class="form-check-label" for="check_support_resistance">
                        <?php esc_html_e( 'Key support/resistance levels marked', 'trade-journal-wp' ); ?>
                    </label>
                </div>
                
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="check_multiple_timeframes">
                    <label class="form-check-label" for="check_multiple_timeframes">
                        <?php esc_html_e( 'Multiple timeframe analysis completed', 'trade-journal-wp' ); ?>
                    </label>
                </div>
                
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="check_volume_analysis">
                    <label class="form-check-label" for="check_volume_analysis">
                        <?php esc_html_e( 'Volume/price action analyzed', 'trade-journal-wp' ); ?>
                    </label>
                </div>
            </div>

            <!-- Entry Setup Section -->
            <div class="mb-4">
                <h6 class="text-success border-bottom pb-2 mb-3">
                    <i class="fas fa-sign-in-alt me-2"></i>
                    <?php esc_html_e( 'Entry Setup', 'trade-journal-wp' ); ?>
                </h6>
                
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="check_entry_signal">
                    <label class="form-check-label" for="check_entry_signal">
                        <?php esc_html_e( 'Clear entry signal confirmed', 'trade-journal-wp' ); ?>
                    </label>
                </div>
                
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="check_entry_price">
                    <label class="form-check-label" for="check_entry_price">
                        <?php esc_html_e( 'Optimal entry price identified', 'trade-journal-wp' ); ?>
                    </label>
                </div>
                
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="check_market_session">
                    <label class="form-check-label" for="check_market_session">
                        <?php esc_html_e( 'Trading during active market session', 'trade-journal-wp' ); ?>
                    </label>
                </div>
                
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="check_confluence">
                    <label class="form-check-label" for="check_confluence">
                        <?php esc_html_e( 'Multiple confluences aligned', 'trade-journal-wp' ); ?>
                    </label>
                </div>
            </div>

            <!-- Risk Management Section -->
            <div class="mb-4">
                <h6 class="text-danger border-bottom pb-2 mb-3">
                    <i class="fas fa-shield-alt me-2"></i>
                    <?php esc_html_e( 'Risk Management', 'trade-journal-wp' ); ?>
                </h6>
                
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="check_stop_loss">
                    <label class="form-check-label" for="check_stop_loss">
                        <?php esc_html_e( 'Stop loss level determined', 'trade-journal-wp' ); ?>
                    </label>
                </div>
                
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="check_take_profit">
                    <label class="form-check-label" for="check_take_profit">
                        <?php esc_html_e( 'Take profit targets set', 'trade-journal-wp' ); ?>
                    </label>
                </div>
                
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="check_risk_reward">
                    <label class="form-check-label" for="check_risk_reward">
                        <?php esc_html_e( 'Risk/reward ratio calculated (min 1:2)', 'trade-journal-wp' ); ?>
                    </label>
                </div>
                
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="check_position_size">
                    <label class="form-check-label" for="check_position_size">
                        <?php esc_html_e( 'Position size calculated (max 2% risk)', 'trade-journal-wp' ); ?>
                    </label>
                </div>
            </div>

            <!-- Psychology & Discipline Section -->
            <div class="mb-4">
                <h6 class="text-warning border-bottom pb-2 mb-3">
                    <i class="fas fa-brain me-2"></i>
                    <?php esc_html_e( 'Psychology & Discipline', 'trade-journal-wp' ); ?>
                </h6>
                
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="check_emotional_state">
                    <label class="form-check-label" for="check_emotional_state">
                        <?php esc_html_e( 'Clear emotional state, not revenge trading', 'trade-journal-wp' ); ?>
                    </label>
                </div>
                
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="check_trading_plan">
                    <label class="form-check-label" for="check_trading_plan">
                        <?php esc_html_e( 'Following established trading plan', 'trade-journal-wp' ); ?>
                    </label>
                </div>
                
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="check_no_fomo">
                    <label class="form-check-label" for="check_no_fomo">
                        <?php esc_html_e( 'No FOMO - patient for proper setup', 'trade-journal-wp' ); ?>
                    </label>
                </div>
                
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="check_daily_limit">
                    <label class="form-check-label" for="check_daily_limit">
                        <?php esc_html_e( 'Within daily trading limits', 'trade-journal-wp' ); ?>
                    </label>
                </div>
            </div>

            <!-- Final Check Section -->
            <div class="mb-4">
                <h6 class="text-info border-bottom pb-2 mb-3">
                    <i class="fas fa-clipboard-check me-2"></i>
                    <?php esc_html_e( 'Final Validation', 'trade-journal-wp' ); ?>
                </h6>
                
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="check_news_events">
                    <label class="form-check-label" for="check_news_events">
                        <?php esc_html_e( 'No major news events during trade', 'trade-journal-wp' ); ?>
                    </label>
                </div>
                
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="check_setup_quality">
                    <label class="form-check-label" for="check_setup_quality">
                        <?php esc_html_e( 'Setup quality: HIGH (if not, skip trade)', 'trade-journal-wp' ); ?>
                    </label>
                </div>
                
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="check_confidence">
                    <label class="form-check-label" for="check_confidence">
                        <?php esc_html_e( 'High confidence in trade outcome', 'trade-journal-wp' ); ?>
                    </label>
                </div>
            </div>

            <!-- Checklist Summary -->
            <div class="alert alert-light border">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <strong id="checklistScore">0/16</strong> <?php esc_html_e( 'items completed', 'trade-journal-wp' ); ?>
                        <div class="progress mt-2" style="height: 6px; width: 200px;">
                            <div class="progress-bar bg-success" id="checklistProgress" style="width: 0%;"></div>
                        </div>
                    </div>
                    <div>
                        <span class="badge bg-secondary" id="checklistStatus">
                            <?php esc_html_e( 'Not Ready', 'trade-journal-wp' ); ?>
                        </span>
                    </div>
                </div>
                <small class="text-muted mt-2 d-block">
                    <?php esc_html_e( 'Minimum 80% completion recommended before entering trade', 'trade-journal-wp' ); ?>
                </small>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex gap-2 mt-3">
                <button type="button" class="btn btn-sm btn-outline-secondary" id="resetChecklist">
                    <i class="fas fa-undo me-1"></i>
                    <?php esc_html_e( 'Reset', 'trade-journal-wp' ); ?>
                </button>
                <button type="button" class="btn btn-sm btn-outline-primary" id="saveChecklist">
                    <i class="fas fa-save me-1"></i>
                    <?php esc_html_e( 'Save Checklist', 'trade-journal-wp' ); ?>
                </button>
                <button type="button" class="btn btn-sm btn-success" id="proceedToTrade" disabled>
                    <i class="fas fa-check me-1"></i>
                    <?php esc_html_e( 'Ready to Trade', 'trade-journal-wp' ); ?>
                </button>
            </div>
        </div>
    </div>
</div>
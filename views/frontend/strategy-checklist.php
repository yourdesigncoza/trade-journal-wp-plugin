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
            <?php esc_html_e( 'FTR Strategy Entry & Exit Checklist', 'trade-journal-wp' ); ?>
        </h5>
        
        <div class="checklist-container" id="strategyChecklist">
            
            <!-- Tab Navigation -->
            <ul class="nav nav-underline fs-9 mb-3" id="checklistTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="entry-tab" data-bs-toggle="tab" href="#tab-entry" role="tab" aria-controls="tab-entry" aria-selected="true">
                        <i class="fas fa-check-circle me-1 text-success"></i><?php esc_html_e( 'Entry', 'trade-journal-wp' ); ?>
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="exit-tab" data-bs-toggle="tab" href="#tab-exit" role="tab" aria-controls="tab-exit" aria-selected="false">
                        <i class="fas fa-stop-circle me-1 text-warning"></i><?php esc_html_e( 'Exit', 'trade-journal-wp' ); ?>
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="risk-tab" data-bs-toggle="tab" href="#tab-risk" role="tab" aria-controls="tab-risk" aria-selected="false">
                        <i class="fas fa-times-circle me-1 text-danger"></i><?php esc_html_e( 'Risk', 'trade-journal-wp' ); ?>
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="manage-tab" data-bs-toggle="tab" href="#tab-manage" role="tab" aria-controls="tab-manage" aria-selected="false">
                        <i class="fas fa-shield-alt me-1 text-info"></i><?php esc_html_e( 'Manage', 'trade-journal-wp' ); ?>
                    </a>
                </li>
            </ul>
            
            <!-- Tab Content -->
            <div class="tab-content" id="checklistTabContent">
                
                <!-- Entry Tab -->
                <div class="tab-pane fade active show" id="tab-entry" role="tabpanel" aria-labelledby="entry-tab">
                    <div class="mb-4">
                
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="check_bos">
                    <label class="form-check-label" for="check_bos">
                        <strong><?php esc_html_e( 'Clear Break of Structure (M15+)', 'trade-journal-wp' ); ?></strong><br>
                        <small class="text-muted"><?php esc_html_e( 'Wide-range candle(s) decisively breaks SR/SD zone on M15 timeframe or higher', 'trade-journal-wp' ); ?></small>
                    </label>
                </div>
                
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="check_ftr_base">
                    <label class="form-check-label" for="check_ftr_base">
                        <strong><?php esc_html_e( 'FTR Base Formation (1-3 Candles Max)', 'trade-journal-wp' ); ?></strong><br>
                        <small class="text-muted"><?php esc_html_e( 'Tight 1-3 candle base formed, never touching original SR (23-38% retracement typical)', 'trade-journal-wp' ); ?></small>
                    </label>
                </div>
                
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="check_continuation">
                    <label class="form-check-label" for="check_continuation">
                        <strong><?php esc_html_e( 'Continuation Confirmation', 'trade-journal-wp' ); ?></strong><br>
                        <small class="text-muted"><?php esc_html_e( 'Second impulse breaks recent high/low, leaving base intact as fresh FTR zone', 'trade-journal-wp' ); ?></small>
                    </label>
                </div>
                
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="check_ftb">
                    <label class="form-check-label" for="check_ftb">
                        <strong><?php esc_html_e( 'First Time Back ONLY', 'trade-journal-wp' ); ?></strong><br>
                        <small class="text-muted"><?php esc_html_e( 'Zone completely untouched since formation - skip if already tested', 'trade-journal-wp' ); ?></small>
                    </label>
                </div>
                
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="check_confluence">
                    <label class="form-check-label" for="check_confluence">
                        <strong><?php esc_html_e( 'HTF Confluence Present', 'trade-journal-wp' ); ?></strong><br>
                        <small class="text-muted"><?php esc_html_e( 'Aligns with HTF order block, FVG, or key Fib level (61.8%, 78.6%)', 'trade-journal-wp' ); ?></small>
                    </label>
                </div>
                    </div>
                </div>
                
                <!-- Exit Tab -->
                <div class="tab-pane fade" id="tab-exit" role="tabpanel" aria-labelledby="exit-tab">
                    <div class="mb-4">
                
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="check_stop_loss">
                    <label class="form-check-label" for="check_stop_loss">
                        <strong><?php esc_html_e( 'Stop Loss Placement', 'trade-journal-wp' ); ?></strong><br>
                        <small class="text-muted"><?php esc_html_e( 'Placed beyond far side of FTR zone (below base low for longs/above base high for shorts)', 'trade-journal-wp' ); ?></small>
                    </label>
                </div>
                
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="check_tp_targets">
                    <label class="form-check-label" for="check_tp_targets">
                        <strong><?php esc_html_e( 'Partial Profit Plan', 'trade-journal-wp' ); ?></strong><br>
                        <small class="text-muted"><?php esc_html_e( 'TP1: 50% at 1:1 or 2:1 | TP2: Trail remainder to 3:1+', 'trade-journal-wp' ); ?></small>
                    </label>
                </div>
                
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="check_invalidation">
                    <label class="form-check-label" for="check_invalidation">
                        <strong><?php esc_html_e( 'Invalidation Rules Clear', 'trade-journal-wp' ); ?></strong><br>
                        <small class="text-muted"><?php esc_html_e( 'Exit if retest candle closes beyond FTR zone', 'trade-journal-wp' ); ?></small>
                    </label>
                </div>
                
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="check_trail_manage">
                    <label class="form-check-label" for="check_trail_manage">
                        <strong><?php esc_html_e( 'Breakeven & Trail Plan', 'trade-journal-wp' ); ?></strong><br>
                        <small class="text-muted"><?php esc_html_e( 'Move SL to BE at +1R, then trail under swing lows/highs', 'trade-journal-wp' ); ?></small>
                    </label>
                </div>
                    </div>
                </div>
                
                <!-- Risk Tab -->
                <div class="tab-pane fade" id="tab-risk" role="tabpanel" aria-labelledby="risk-tab">
                    <div class="mb-4">
                
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="check_no_deep_retracement">
                    <label class="form-check-label" for="check_no_deep_retracement">
                        <strong><?php esc_html_e( 'No Deep Retracement', 'trade-journal-wp' ); ?></strong><br>
                        <small class="text-muted"><?php esc_html_e( 'Price has NOT returned fully to original SR level', 'trade-journal-wp' ); ?></small>
                    </label>
                </div>
                
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="check_fresh_zone">
                    <label class="form-check-label" for="check_fresh_zone">
                        <strong><?php esc_html_e( 'No Extended Consolidation', 'trade-journal-wp' ); ?></strong><br>
                        <small class="text-muted"><?php esc_html_e( 'Price pulled away strongly and returns directly (no drifting/ranging)', 'trade-journal-wp' ); ?></small>
                    </label>
                </div>
                
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="check_strong_impulse">
                    <label class="form-check-label" for="check_strong_impulse">
                        <strong><?php esc_html_e( 'Strong Impulse Present', 'trade-journal-wp' ); ?></strong><br>
                        <small class="text-muted"><?php esc_html_e( 'Clear momentum candle showing structural break', 'trade-journal-wp' ); ?></small>
                    </label>
                </div>
                
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="check_clean_base">
                    <label class="form-check-label" for="check_clean_base">
                        <strong><?php esc_html_e( 'Clean Base Formation', 'trade-journal-wp' ); ?></strong><br>
                        <small class="text-muted"><?php esc_html_e( 'Base is ≤3 candles without erratic wicks', 'trade-journal-wp' ); ?></small>
                    </label>
                </div>
                
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="check_session_timing">
                    <label class="form-check-label" for="check_session_timing">
                        <strong><?php esc_html_e( 'Optimal Session Timing', 'trade-journal-wp' ); ?></strong><br>
                        <small class="text-muted"><?php esc_html_e( 'Trading during London or NY session for maximum liquidity', 'trade-journal-wp' ); ?></small>
                    </label>
                </div>
                    </div>
                </div>
                
                <!-- Manage Tab -->
                <div class="tab-pane fade" id="tab-manage" role="tabpanel" aria-labelledby="manage-tab">
                    <div class="mb-4">
                
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="check_risk_reward">
                    <label class="form-check-label" for="check_risk_reward">
                        <?php esc_html_e( 'Risk/Reward ratio calculated (minimum 1:3)', 'trade-journal-wp' ); ?>
                    </label>
                </div>
                
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="check_position_size">
                    <label class="form-check-label" for="check_position_size">
                        <?php esc_html_e( 'Position size calculated (max 2% risk per trade)', 'trade-journal-wp' ); ?>
                    </label>
                </div>
                
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="check_emotional_state">
                    <label class="form-check-label" for="check_emotional_state">
                        <?php esc_html_e( 'Clear emotional state, not revenge trading', 'trade-journal-wp' ); ?>
                    </label>
                </div>
                
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="check_trading_plan">
                    <label class="form-check-label" for="check_trading_plan">
                        <?php esc_html_e( 'Following established FTR trading plan', 'trade-journal-wp' ); ?>
                    </label>
                </div>
                    </div>
                </div>
            
            </div> <!-- End tab-content -->

            <!-- Checklist Summary -->
            <div class="alert alert-subtle-primary">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <strong id="checklistScore">0/18</strong> <?php esc_html_e( 'items completed', 'trade-journal-wp' ); ?>
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
                <small class="mt-2 d-block">
                    <?php esc_html_e( 'Minimum 80% completion recommended before entering trade', 'trade-journal-wp' ); ?>
                </small>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex gap-2 mt-3">
                <button type="button" class="btn btn-sm btn-subtle-secondary" id="resetChecklist">
                    <i class="fas fa-undo me-1"></i>
                    <?php esc_html_e( 'Reset', 'trade-journal-wp' ); ?>
                </button>
                <button type="button" class="btn btn-sm btn-subtle-primary" id="saveChecklist">
                    <i class="fas fa-save me-1"></i>
                    <?php esc_html_e( 'Save Checklist', 'trade-journal-wp' ); ?>
                </button>
                <button type="button" class="btn btn-sm btn-subtle-success" id="proceedToTrade" disabled>
                    <i class="fas fa-check me-1"></i>
                    <?php esc_html_e( 'Ready to Trade', 'trade-journal-wp' ); ?>
                </button>
            </div>
        </div>
    </div>
</div>
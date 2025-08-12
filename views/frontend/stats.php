<?php
/**
 * Statistics Frontend View
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
            <i class="fas fa-chart-bar me-2"></i>
            <?php esc_html_e( 'Performance Analytics', 'trade-journal-wp' ); ?>
        </h5>

        <div class="list-group list-group-flush performance-stats" id="performanceStats">
            
            <!-- Total Trades -->
            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-sm me-3">
                        <div class="avatar-name rounded-circle bg-primary-subtle">
                            <span class="fs-9 text-primary">T</span>
                        </div>
                    </div>
                    <div>
                        <h6 class="mb-0"><?php esc_html_e( 'Total Trades', 'trade-journal-wp' ); ?></h6>
                    </div>
                </div>
                <span class="badge badge-phoenix fs-10 badge-phoenix-primary" id="totalTrades">
                    <?php echo esc_html( number_format( $stats['total_trades'] ) ); ?>
                </span>
            </div>

            <!-- Win Rate -->
            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-sm me-3">
                        <div class="avatar-name rounded-circle bg-success-subtle">
                            <span class="fs-9 text-success">W</span>
                        </div>
                    </div>
                    <div>
                        <h6 class="mb-0"><?php esc_html_e( 'Win Rate', 'trade-journal-wp' ); ?></h6>
                    </div>
                </div>
                <span class="badge badge-phoenix fs-10 badge-phoenix-success" id="winRate">
                    <?php echo esc_html( number_format( $stats['win_rate'], 1 ) ); ?>%
                </span>
            </div>

            <!-- Account Gain -->
            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-sm me-3">
                        <div class="avatar-name rounded-circle bg-info-subtle">
                            <span class="fs-9 text-info">A</span>
                        </div>
                    </div>
                    <div>
                        <h6 class="mb-0"><?php esc_html_e( 'Account Gain', 'trade-journal-wp' ); ?></h6>
                    </div>
                </div>
                <span class="badge badge-phoenix fs-10 <?php echo $stats['total_pl'] >= 0 ? 'badge-phoenix-success' : 'badge-phoenix-danger'; ?>" id="accountGain">
                    <?php echo esc_html( Trade_Journal_Shortcodes::format_percentage( $stats['total_pl'] ) ); ?>
                </span>
            </div>

            <!-- Profit Factor -->
            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-sm me-3">
                        <div class="avatar-name rounded-circle bg-warning-subtle">
                            <span class="fs-9 text-warning">P</span>
                        </div>
                    </div>
                    <div>
                        <h6 class="mb-0"><?php esc_html_e( 'Profit Factor', 'trade-journal-wp' ); ?></h6>
                    </div>
                </div>
                <span class="badge badge-phoenix fs-10 badge-phoenix-warning" id="profitFactor">
                    <?php echo $stats['profit_factor'] > 0 ? esc_html( number_format( $stats['profit_factor'], 2 ) ) : '-'; ?>
                </span>
            </div>

            <!-- Average RR -->
            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-sm me-3">
                        <div class="avatar-name rounded-circle bg-secondary-subtle">
                            <span class="fs-9 text-secondary">R</span>
                        </div>
                    </div>
                    <div>
                        <h6 class="mb-0"><?php esc_html_e( 'Avg RR', 'trade-journal-wp' ); ?></h6>
                    </div>
                </div>
                <span class="badge badge-phoenix fs-10 badge-phoenix-secondary" id="avgRR">
                    <?php
                    $trades = $database->get_all_trades();
                    $rr_values = array_filter( array_column( $trades, 'rr' ), function( $val ) { return $val !== null && $val !== ''; } );
                    $avg_rr = ! empty( $rr_values ) ? array_sum( $rr_values ) / count( $rr_values ) : 0;
                    echo $avg_rr > 0 ? esc_html( number_format( $avg_rr, 2 ) ) . ':1' : '-';
                    ?>
                </span>
            </div>

            <!-- Best Trade -->
            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-sm me-3">
                        <div class="avatar-name rounded-circle bg-success-subtle">
                            <span class="fs-9 text-success">↗</span>
                        </div>
                    </div>
                    <div>
                        <h6 class="mb-0"><?php esc_html_e( 'Best Trade', 'trade-journal-wp' ); ?></h6>
                    </div>
                </div>
                <span class="badge badge-phoenix fs-10 badge-phoenix-success" id="bestTrade">
                    <?php echo esc_html( Trade_Journal_Shortcodes::format_percentage( $stats['best_trade'] ) ); ?>
                </span>
            </div>

            <!-- Worst Trade -->
            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-sm me-3">
                        <div class="avatar-name rounded-circle bg-danger-subtle">
                            <span class="fs-9 text-danger">↘</span>
                        </div>
                    </div>
                    <div>
                        <h6 class="mb-0"><?php esc_html_e( 'Worst Trade', 'trade-journal-wp' ); ?></h6>
                    </div>
                </div>
                <span class="badge badge-phoenix fs-10 badge-phoenix-danger" id="worstTrade">
                    <?php echo esc_html( Trade_Journal_Shortcodes::format_percentage( $stats['worst_trade'] ) ); ?>
                </span>
            </div>

        </div>

        <!-- Outcome Breakdown -->
        <div class="mt-4">
            <h6 class="text-body mb-3">
                <i class="fas fa-chart-pie me-2"></i>
                <?php esc_html_e( 'Trade Outcomes', 'trade-journal-wp' ); ?>
            </h6>
            <div class="row g-2">
                <div class="col-3">
                    <div class="text-center p-2 bg-success-subtle rounded">
                        <div class="h5 text-success mb-0" id="winsCount">
                            <?php echo esc_html( number_format( $stats['wins'] ) ); ?>
                        </div>
                        <small class="text-success"><?php esc_html_e( 'Wins', 'trade-journal-wp' ); ?></small>
                    </div>
                </div>
                <div class="col-3">
                    <div class="text-center p-2 bg-danger-subtle rounded">
                        <div class="h5 text-danger mb-0" id="lossesCount">
                            <?php echo esc_html( number_format( $stats['losses'] ) ); ?>
                        </div>
                        <small class="text-danger"><?php esc_html_e( 'Losses', 'trade-journal-wp' ); ?></small>
                    </div>
                </div>
                <div class="col-3">
                    <div class="text-center p-2 bg-secondary-subtle rounded">
                        <div class="h5 text-secondary mb-0" id="breakEvenCount">
                            <?php echo esc_html( number_format( $stats['break_even'] ) ); ?>
                        </div>
                        <small class="text-secondary"><?php esc_html_e( 'B/E', 'trade-journal-wp' ); ?></small>
                    </div>
                </div>
                <div class="col-3">
                    <div class="text-center p-2 bg-warning-subtle rounded">
                        <div class="h5 text-warning mb-0" id="cancelledCount">
                            <?php echo esc_html( number_format( $stats['cancelled'] ) ); ?>
                        </div>
                        <small class="text-warning"><?php esc_html_e( 'Cancelled', 'trade-journal-wp' ); ?></small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Auto-refresh toggle -->
        <!-- <div class="mt-4">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="autoRefreshStats" checked>
                <label class="form-check-label" for="autoRefreshStats">
                    <?php // esc_html_e( 'Auto-refresh statistics', 'trade-journal-wp' ); ?>
                </label>
            </div>
        </div> -->
    </div>
</div>
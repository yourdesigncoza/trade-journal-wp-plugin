<?php
/**
 * Trade List Frontend View
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
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="text-body mb-0">
                <i class="fas fa-table me-2"></i>
                <?php esc_html_e( 'Trade History', 'trade-journal-wp' ); ?>
            </h5>
            
            <?php if ( 'true' === $atts['show_search'] ) : ?>
            <div class="d-flex gap-2">
                <input type="text" id="searchInput" class="form-control form-control-sm" 
                       placeholder="<?php esc_attr_e( 'Search trades...', 'trade-journal-wp' ); ?>" style="width: 200px;">
                <button type="button" class="btn btn-sm btn-outline-primary" id="refreshTrades">
                    <i class="fas fa-sync-alt"></i>
                </button>
            </div>
            <?php endif; ?>
        </div>

        <?php if ( 'true' === $atts['show_filters'] ) : ?>
        <div class="row g-2 mb-3">
            <div class="col-md-2">
                <select class="form-select form-select-sm" id="marketFilter">
                    <option value=""><?php esc_html_e( 'All Markets', 'trade-journal-wp' ); ?></option>
                    <?php foreach ( Trade_Journal_Shortcodes::get_market_options() as $value => $label ) : ?>
                        <option value="<?php echo esc_attr( $value ); ?>"><?php echo esc_html( $label ); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select form-select-sm" id="sessionFilter">
                    <option value=""><?php esc_html_e( 'All Sessions', 'trade-journal-wp' ); ?></option>
                    <?php foreach ( Trade_Journal_Shortcodes::get_session_options() as $value => $label ) : ?>
                        <option value="<?php echo esc_attr( $value ); ?>"><?php echo esc_html( $label ); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select form-select-sm" id="outcomeFilter">
                    <option value=""><?php esc_html_e( 'All Outcomes', 'trade-journal-wp' ); ?></option>
                    <?php foreach ( Trade_Journal_Shortcodes::get_outcome_options() as $value => $label ) : ?>
                        <option value="<?php echo esc_attr( $value ); ?>"><?php echo esc_html( $label ); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" class="form-control form-control-sm" id="dateFromFilter" 
                       placeholder="<?php esc_attr_e( 'dd/mm/yyyy', 'trade-journal-wp' ); ?>">
            </div>
            <div class="col-md-2">
                <input type="date" class="form-control form-control-sm" id="dateToFilter" 
                       placeholder="<?php esc_attr_e( 'dd/mm/yyyy', 'trade-journal-wp' ); ?>">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-sm btn-outline-secondary w-100" id="clearFilters">
                    <?php esc_html_e( 'Clear', 'trade-journal-wp' ); ?>
                </button>
            </div>
        </div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-sm fs-9 mb-0 overflow-hidden" id="tradesTable">
                <thead class="text-body">
                    <tr>
                        <th class="sort ps-3 pe-1 align-middle white-space-nowrap" data-sort="date">
                            <i class="fas fa-calendar-alt text-primary me-1 fs-10"></i>
                            <?php esc_html_e( 'Date', 'trade-journal-wp' ); ?>
                        </th>
                        <th class="sort pe-1 align-middle white-space-nowrap" data-sort="time">
                            <i class="fas fa-clock text-info me-1 fs-10"></i>
                            <?php esc_html_e( 'Time', 'trade-journal-wp' ); ?>
                        </th>
                        <th class="sort pe-1 align-middle white-space-nowrap text-center" data-sort="market" style="min-width: 90px;">
                            <i class="fas fa-coins text-primary me-1 fs-10"></i>
                            <?php esc_html_e( 'Market', 'trade-journal-wp' ); ?>
                        </th>
                        <th class="sort pe-1 align-middle white-space-nowrap text-center" data-sort="session" style="min-width: 90px;">
                            <i class="fas fa-globe text-info me-1 fs-10"></i>
                            <?php esc_html_e( 'Session', 'trade-journal-wp' ); ?>
                        </th>
                        <th class="sort pe-1 align-middle white-space-nowrap" data-sort="direction" style="min-width: 90px;">
                            <i class="fas fa-arrows-alt-v text-warning me-1 fs-10"></i>
                            <?php esc_html_e( 'Direction', 'trade-journal-wp' ); ?>
                        </th>
                        <th class="sort pe-1 align-middle white-space-nowrap text-center" data-sort="order_type" style="min-width: 90px;">
                            <i class="fas fa-shopping-cart text-info me-1 fs-10"></i>
                            <?php esc_html_e( 'Order Type', 'trade-journal-wp' ); ?>
                        </th>
                        <th class="sort pe-1 align-middle white-space-nowrap text-center" data-sort="strategy" style="min-width: 90px;">
                            <i class="fas fa-chess text-primary me-1 fs-10"></i>
                            <?php esc_html_e( 'Strategy', 'trade-journal-wp' ); ?>
                        </th>
                        <th class="sort pe-1 align-middle white-space-nowrap text-center" data-sort="stop_loss" style="min-width: 110px;">
                            <i class="fas fa-shield-alt text-danger me-1 fs-10"></i>
                            <?php esc_html_e( 'Stop Loss', 'trade-journal-wp' ); ?>
                        </th>
                        <th class="sort pe-1 align-middle white-space-nowrap text-center" data-sort="take_profit" style="min-width: 110px;">
                            <i class="fas fa-bullseye text-success me-1 fs-10"></i>
                            <?php esc_html_e( 'Take Profit', 'trade-journal-wp' ); ?>
                        </th>
                        <th class="sort pe-1 align-middle white-space-nowrap text-center" data-sort="entry_price" style="min-width: 110px;">
                            <i class="fas fa-sign-in-alt text-success me-1 fs-10"></i>
                            <?php esc_html_e( 'Entry Price', 'trade-journal-wp' ); ?>
                        </th>
                        <th class="sort pe-1 align-middle white-space-nowrap text-center" data-sort="exit_price" style="min-width: 110px;">
                            <i class="fas fa-sign-out-alt text-danger me-1 fs-10"></i>
                            <?php esc_html_e( 'Exit Price', 'trade-journal-wp' ); ?>
                        </th>
                        <th class="sort pe-1 align-middle white-space-nowrap text-center" data-sort="outcome" style="min-width: 100px;">
                            <i class="fas fa-trophy text-warning me-1 fs-10"></i>
                            <?php esc_html_e( 'Outcome', 'trade-journal-wp' ); ?>
                        </th>
                        <th class="sort pe-1 align-middle white-space-nowrap text-center" data-sort="pl_percent" style="min-width: 90px;">
                            <i class="fas fa-percentage text-success me-1 fs-10"></i>
                            <?php esc_html_e( 'P/L %', 'trade-journal-wp' ); ?>
                        </th>
                        <th class="sort pe-1 align-middle white-space-nowrap text-center" data-sort="rr" style="min-width: 90px;">
                            <i class="fas fa-balance-scale me-1 fs-10"></i>
                            <?php esc_html_e( 'RR', 'trade-journal-wp' ); ?>
                        </th>
                        <th class="sort pe-1 align-middle white-space-nowrap text-center" data-sort="absolute_pl" style="min-width: 100px;">
                            <i class="fas fa-dollar-sign text-success me-1 fs-10"></i>
                            <?php esc_html_e( 'Absolute P/L', 'trade-journal-wp' ); ?>
                        </th>
                        <th class="sort pe-1 align-middle white-space-nowrap text-center" data-sort="disciplined" style="min-width: 100px;">
                            <i class="fas fa-user-check text-warning me-1 fs-10"></i>
                            <?php esc_html_e( 'Disciplined', 'trade-journal-wp' ); ?>
                        </th>
                        <th class="sort pe-1 align-middle white-space-nowrap text-center" data-sort="followed_rules" style="min-width: 100px;">
                            <i class="fas fa-clipboard-check text-info me-1 fs-10"></i>
                            <?php esc_html_e( 'Rules', 'trade-journal-wp' ); ?>
                        </th>
                        <th class="sort pe-1 align-middle white-space-nowrap text-center" data-sort="rating" style="min-width: 100px;">
                            <i class="fas fa-star text-warning me-1 fs-10"></i>
                            <?php esc_html_e( 'Rating', 'trade-journal-wp' ); ?>
                        </th>
                        <th class="pe-1 align-middle white-space-nowrap text-center" style="min-width: 100px;">
                            <i class="fas fa-chart-line text-info me-1 fs-10"></i>
                            <?php esc_html_e( 'TF', 'trade-journal-wp' ); ?>
                        </th>
                        <th class="pe-1 align-middle white-space-nowrap text-center" style="min-width: 100px;">
                            <i class="fas fa-chart-bar text-warning me-1 fs-10"></i>
                            <?php esc_html_e( 'Chart HTF', 'trade-journal-wp' ); ?>
                        </th>
                        <th class="pe-1 align-middle white-space-nowrap text-center" style="min-width: 100px;">
                            <i class="fas fa-chart-area text-warning me-1 fs-10"></i>
                            <?php esc_html_e( 'Chart LTF', 'trade-journal-wp' ); ?>
                        </th>
                        <th class="no-sort text-center" style="min-width: 150px;"><?php // esc_html_e( 'Actions', 'trade-journal-wp' ); ?></th>
                    </tr>
                </thead>
                <tbody class="list">
                    <?php if ( ! empty( $trades ) ) : ?>
                        <?php 
                        $row_count = 0;
                        foreach ( $trades as $trade ) : 
                            $row_count++;
                            $row_class = $row_count % 2 === 0 ? 'bg-light' : '';
                        ?>
                            <tr class="btn-reveal-trigger" data-trade-id="<?php echo esc_attr( $trade['id'] ); ?>">
                                <td class="py-2 ps-3 align-middle white-space-nowrap fs-9">
                                    <?php echo esc_html( date( 'd/m/Y', strtotime( $trade['date'] ) ) ); ?>
                                </td>
                                <td class="py-2 align-middle fs-9">
                                    <?php echo esc_html( isset( $trade['time'] ) ? $trade['time'] : '-' ); ?>
                                </td>
                                <td class="py-2 align-middle text-center">
                                    <span class="badge badge-sm rounded-pill badge-phoenix badge-phoenix-primary text-center"><?php echo esc_html( $trade['market'] ); ?></span>
                                </td>
                                <td class="py-2 align-middle fs-9 text-center">
                                    <?php echo esc_html( Trade_Journal_Shortcodes::get_session_options()[ $trade['session'] ] ?? $trade['session'] ); ?>
                                </td>
                                <td class="py-2 align-middle text-center">
                                    <span class="badge badge-sm rounded-pill badge-phoenix <?php echo 'LONG' === $trade['direction'] ? 'badge-phoenix-success' : 'badge-phoenix-danger'; ?>">
                                        <?php echo esc_html( $trade['direction'] ); ?>
                                    </span>
                                </td>
                                <td class="py-2 align-middle text-center">
                                    <?php if ( isset( $trade['order_type'] ) && ! empty( $trade['order_type'] ) ) : ?>
                                        <span class="badge badge-sm rounded-pill badge-phoenix badge-phoenix-info"><?php echo esc_html( $trade['order_type'] ); ?></span>
                                    <?php else : ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-2 align-middle text-center">
                                    <?php echo esc_html( isset( $trade['strategy'] ) && ! empty( $trade['strategy'] ) ? $trade['strategy'] : '-' ); ?>
                                </td>
                                <td class="py-2 align-middle fs-9 text-center"><?php echo esc_html( Trade_Journal_Shortcodes::format_price( $trade['stop_loss'] ) ); ?></td>
                                <td class="py-2 align-middle fs-9 text-center"><?php echo esc_html( Trade_Journal_Shortcodes::format_price( $trade['take_profit'] ) ); ?></td>
                                <td class="py-2 align-middle fs-9 text-center"><?php echo esc_html( Trade_Journal_Shortcodes::format_price( $trade['entry_price'] ) ); ?></td>
                                <td class="py-2 align-middle fs-9 text-center"><?php echo esc_html( Trade_Journal_Shortcodes::format_price( $trade['exit_price'] ) ); ?></td>
                                <td class="py-2 align-middle text-center white-space-nowrap">
                                    <?php if ( $trade['outcome'] ) : ?>
                                        <?php
                                        $outcome_class = '';
                                        switch ( $trade['outcome'] ) {
                                            case 'W': $outcome_class = 'badge-phoenix-success'; break;
                                            case 'L': $outcome_class = 'badge-phoenix-danger'; break;
                                            case 'BE': $outcome_class = 'badge-phoenix-secondary'; break;
                                            case 'C': $outcome_class = 'badge-phoenix-warning'; break;
                                            default: $outcome_class = 'badge-phoenix-secondary';
                                        }
                                        ?>
                                        <span class="badge badge-sm rounded-pill badge-phoenix text-center <?php echo esc_attr( $outcome_class ); ?>">
                                            <?php echo esc_html( Trade_Journal_Shortcodes::get_outcome_options()[ $trade['outcome'] ] ?? $trade['outcome'] ); ?>
                                        </span>
                                    <?php else : ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-2 align-middle text-center fs-9 fw-medium text-center">
                                    <?php if ( isset( $trade['pl_percent'] ) && $trade['pl_percent'] !== null ) : ?>
                                        <span class="<?php echo $trade['pl_percent'] >= 0 ? 'text-success' : 'text-danger'; ?>">
                                            <?php 
                                            $prefix = $trade['pl_percent'] >= 0 ? '+' : '';
                                            echo esc_html( $prefix . number_format( (float) $trade['pl_percent'], 2 ) . '%' ); 
                                            ?>
                                        </span>
                                    <?php else : ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-2 align-middle text-center fs-9 fw-medium text-center">
                                    <?php echo esc_html( isset( $trade['rr'] ) && $trade['rr'] !== null ? number_format( (float) $trade['rr'], 1 ) : '-' ); ?>
                                </td>
                                <td class="py-2 align-middle text-center fs-9 fw-medium">
                                    <?php if ( isset( $trade['absolute_pl'] ) && $trade['absolute_pl'] !== null ) : ?>
                                        <span class="<?php echo $trade['absolute_pl'] >= 0 ? 'text-success' : 'text-danger'; ?>">
                                            <?php 
                                            $prefix = $trade['absolute_pl'] >= 0 ? '+' : '';
                                            echo esc_html( $prefix . number_format( (float) $trade['absolute_pl'], 2 ) ); 
                                            ?>
                                        </span>
                                    <?php else : ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-2 align-middle text-center">
                                    <?php if ( isset( $trade['disciplined'] ) && ! empty( $trade['disciplined'] ) ) : ?>
                                        <span class="badge badge-sm rounded-pill badge-phoenix <?php echo 'Y' === $trade['disciplined'] ? 'badge-phoenix-success' : 'badge-phoenix-danger'; ?>">
                                            <?php echo 'Y' === $trade['disciplined'] ? esc_html__( 'Yes', 'trade-journal-wp' ) : esc_html__( 'No', 'trade-journal-wp' ); ?>
                                        </span>
                                    <?php else : ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-2 align-middle text-center">
                                    <?php if ( isset( $trade['followed_rules'] ) && ! empty( $trade['followed_rules'] ) ) : ?>
                                        <span class="badge badge-sm rounded-pill badge-phoenix <?php echo 'Y' === $trade['followed_rules'] ? 'badge-phoenix-success' : 'badge-phoenix-danger'; ?>">
                                            <?php echo 'Y' === $trade['followed_rules'] ? esc_html__( 'Yes', 'trade-journal-wp' ) : esc_html__( 'No', 'trade-journal-wp' ); ?>
                                        </span>
                                    <?php else : ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-2 align-middle text-center">
                                    <?php if ( isset( $trade['rating'] ) && $trade['rating'] !== null ) : ?>
                                        <span class="text-warning fs-8">
                                            <?php 
                                            $rating = (int) $trade['rating'];
                                            for ( $i = 1; $i <= 5; $i++ ) {
                                                echo $i <= $rating ? '★' : '☆';
                                            }
                                            ?>
                                        </span>
                                    <?php else : ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-2 align-middle text-center">
                                    <?php if ( isset( $trade['tf'] ) && ! empty( $trade['tf'] ) ) : ?>
                                        <?php 
                                        $timeframes = is_string( $trade['tf'] ) ? json_decode( $trade['tf'], true ) : $trade['tf'];
                                        if ( is_array( $timeframes ) ) :
                                            foreach ( $timeframes as $tf ) : ?>
                                                <span class="badge badge-sm rounded-pill badge-phoenix badge-phoenix-info me-1"><?php echo esc_html( $tf ); ?></span>
                                            <?php endforeach;
                                        else : ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    <?php else : ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-2 align-middle fs-9 text-center">
                                    <?php if ( isset( $trade['chart_htf'] ) && ! empty( $trade['chart_htf'] ) ) : ?>
                                        <a href="<?php echo esc_url( $trade['chart_htf'] ); ?>" target="_blank" rel="noopener noreferrer" class="text-primary text-decoration-none">
                                            <span class="badge badge-phoenix badge-phoenix-primary">Image <i class="fa fa-external-link" aria-hidden="true"></i></span>
                                        </a>
                                    <?php else : ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-2 align-middle fs-9 text-center">
                                    <?php if ( isset( $trade['chart_ltf'] ) && ! empty( $trade['chart_ltf'] ) ) : ?>
                                        <a href="<?php echo esc_url( $trade['chart_ltf'] ); ?>" target="_blank" rel="noopener noreferrer" class="text-primary text-decoration-none">
                                            <span class="badge badge-phoenix badge-phoenix-primary">Image <i class="fa fa-external-link" aria-hidden="true"></i></span>
                                        </a>
                                    <?php else : ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-2 align-middle white-space-nowrap text-center">
                                    <div class="btn-group btn-group-sm ydcoza-btn-group-tiny" role="group" aria-label="Trade Actions">
                                        <?php if ( isset( $trade['comments'] ) && ! empty( $trade['comments'] ) ) : ?>
                                            <button type="button" class="btn btn-subtle-info" 
                                                    data-bs-toggle="tooltip" 
                                                    data-bs-placement="top" 
                                                    data-bs-html="false"
                                                    title="<?php echo esc_attr( $trade['comments'] ); ?>">
                                                <i class="fas fa-comment opacity-75"></i>
                                            </button>
                                        <?php endif; ?>
                                        <button type="button" class="btn btn-subtle-primary btn-edit" 
                                                data-trade-id="<?php echo esc_attr( $trade['id'] ); ?>"
                                                title="<?php esc_attr_e( 'Edit', 'trade-journal-wp' ); ?>">
                                            <i class="fas fa-edit opacity-75"></i>
                                        </button>
                                        <button type="button" class="btn btn-subtle-danger btn-delete" 
                                                data-trade-id="<?php echo esc_attr( $trade['id'] ); ?>"
                                                title="<?php esc_attr_e( 'Delete', 'trade-journal-wp' ); ?>">
                                            <i class="fas fa-trash opacity-75"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr id="noTradesRow">
                            <td colspan="22" class="text-center text-muted py-4">
                                <?php esc_html_e( 'No trades found. Start by adding your first trade!', 'trade-journal-wp' ); ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="text-muted">
                <span id="tradesCount"><?php echo esc_html( count( $trades ) ); ?></span> <?php esc_html_e( 'trades found', 'trade-journal-wp' ); ?>
            </div>
            <nav aria-label="<?php esc_attr_e( 'Trade list pagination', 'trade-journal-wp' ); ?>">
                <ul class="pagination pagination-sm mb-0" id="tradesPagination">
                    <!-- Pagination will be generated by JavaScript -->
                </ul>
            </nav>
        </div>
    </div>
</div>

<!-- Edit Trade Modal -->
<div class="modal fade" id="editTradeModal" tabindex="-1" aria-labelledby="editTradeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editTradeModalLabel">
                    <?php esc_html_e( 'Edit Trade', 'trade-journal-wp' ); ?>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?php esc_attr_e( 'Close', 'trade-journal-wp' ); ?>"></button>
            </div>
            <div class="modal-body">
                <form id="editTradeForm">
                    <?php wp_nonce_field( 'trade_journal_wp_nonce', 'edit_trade_journal_nonce' ); ?>
                    <input type="hidden" name="trade_id" id="editTradeId">
                    
                    <!-- Form fields will be populated by JavaScript -->
                    <div id="editFormFields"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-subtle-secondary" data-bs-dismiss="modal">
                    <?php esc_html_e( 'Cancel', 'trade-journal-wp' ); ?>
                </button>
                <button type="button" class="btn btn-subtle-primary" id="saveEditTrade">
                    <i class="fas fa-save me-2"></i>
                    <?php esc_html_e( 'Save Changes', 'trade-journal-wp' ); ?>
                </button>
            </div>
        </div>
    </div>
</div>

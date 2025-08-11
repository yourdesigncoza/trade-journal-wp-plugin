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
                       placeholder="<?php esc_attr_e( 'From Date', 'trade-journal-wp' ); ?>">
            </div>
            <div class="col-md-2">
                <input type="date" class="form-control form-control-sm" id="dateToFilter" 
                       placeholder="<?php esc_attr_e( 'To Date', 'trade-journal-wp' ); ?>">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-sm btn-outline-secondary w-100" id="clearFilters">
                    <?php esc_html_e( 'Clear', 'trade-journal-wp' ); ?>
                </button>
            </div>
        </div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-hover" id="tradesTable">
                <thead class="table-light">
                    <tr>
                        <th class="sortable" data-sort="date">
                            <?php esc_html_e( 'Date', 'trade-journal-wp' ); ?>
                            <i class="fas fa-sort ms-1"></i>
                        </th>
                        <th class="sortable" data-sort="market">
                            <?php esc_html_e( 'Market', 'trade-journal-wp' ); ?>
                            <i class="fas fa-sort ms-1"></i>
                        </th>
                        <th class="sortable" data-sort="session">
                            <?php esc_html_e( 'Session', 'trade-journal-wp' ); ?>
                            <i class="fas fa-sort ms-1"></i>
                        </th>
                        <th class="sortable" data-sort="direction">
                            <?php esc_html_e( 'Direction', 'trade-journal-wp' ); ?>
                            <i class="fas fa-sort ms-1"></i>
                        </th>
                        <th class="sortable" data-sort="entry_price">
                            <?php esc_html_e( 'Entry', 'trade-journal-wp' ); ?>
                            <i class="fas fa-sort ms-1"></i>
                        </th>
                        <th class="sortable" data-sort="exit_price">
                            <?php esc_html_e( 'Exit', 'trade-journal-wp' ); ?>
                            <i class="fas fa-sort ms-1"></i>
                        </th>
                        <th class="sortable" data-sort="outcome">
                            <?php esc_html_e( 'Outcome', 'trade-journal-wp' ); ?>
                            <i class="fas fa-sort ms-1"></i>
                        </th>
                        <th class="sortable" data-sort="pl_percent">
                            <?php esc_html_e( 'P/L %', 'trade-journal-wp' ); ?>
                            <i class="fas fa-sort ms-1"></i>
                        </th>
                        <th class="sortable" data-sort="rr">
                            <?php esc_html_e( 'RR', 'trade-journal-wp' ); ?>
                            <i class="fas fa-sort ms-1"></i>
                        </th>
                        <th><?php esc_html_e( 'Actions', 'trade-journal-wp' ); ?></th>
                    </tr>
                </thead>
                <tbody id="tradesTableBody">
                    <?php if ( ! empty( $trades ) ) : ?>
                        <?php foreach ( $trades as $trade ) : ?>
                            <tr data-trade-id="<?php echo esc_attr( $trade['id'] ); ?>">
                                <td><?php echo esc_html( date( 'M j, Y', strtotime( $trade['date'] ) ) ); ?></td>
                                <td>
                                    <span class="badge bg-primary"><?php echo esc_html( $trade['market'] ); ?></span>
                                </td>
                                <td><?php echo esc_html( Trade_Journal_Shortcodes::get_session_options()[ $trade['session'] ] ?? $trade['session'] ); ?></td>
                                <td>
                                    <span class="badge <?php echo 'LONG' === $trade['direction'] ? 'bg-success' : 'bg-danger'; ?>">
                                        <?php echo esc_html( $trade['direction'] ); ?>
                                    </span>
                                </td>
                                <td><?php echo esc_html( Trade_Journal_Shortcodes::format_price( $trade['entry_price'] ) ); ?></td>
                                <td><?php echo esc_html( Trade_Journal_Shortcodes::format_price( $trade['exit_price'] ) ); ?></td>
                                <td>
                                    <?php if ( $trade['outcome'] ) : ?>
                                        <span class="badge <?php echo esc_attr( Trade_Journal_Shortcodes::get_outcome_badge_class( $trade['outcome'] ) ); ?>">
                                            <?php echo esc_html( Trade_Journal_Shortcodes::get_outcome_options()[ $trade['outcome'] ] ?? $trade['outcome'] ); ?>
                                        </span>
                                    <?php else : ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ( $trade['pl_percent'] ) : ?>
                                        <span class="<?php echo $trade['pl_percent'] >= 0 ? 'text-success' : 'text-danger'; ?>">
                                            <?php echo esc_html( Trade_Journal_Shortcodes::format_percentage( $trade['pl_percent'] ) ); ?>
                                        </span>
                                    <?php else : ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo esc_html( Trade_Journal_Shortcodes::format_rr( $trade['rr'] ) ); ?></td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-outline-primary btn-edit" 
                                                data-trade-id="<?php echo esc_attr( $trade['id'] ); ?>"
                                                title="<?php esc_attr_e( 'Edit Trade', 'trade-journal-wp' ); ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-danger btn-delete" 
                                                data-trade-id="<?php echo esc_attr( $trade['id'] ); ?>"
                                                title="<?php esc_attr_e( 'Delete Trade', 'trade-journal-wp' ); ?>">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr id="noTradesRow">
                            <td colspan="10" class="text-center text-muted py-4">
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
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <?php esc_html_e( 'Cancel', 'trade-journal-wp' ); ?>
                </button>
                <button type="button" class="btn btn-primary" id="saveEditTrade">
                    <i class="fas fa-save me-2"></i>
                    <?php esc_html_e( 'Save Changes', 'trade-journal-wp' ); ?>
                </button>
            </div>
        </div>
    </div>
</div>
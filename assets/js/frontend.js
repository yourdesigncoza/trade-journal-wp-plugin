/**
 * Trade Journal WP Frontend JavaScript
 * 
 * @package TradeJournalWP
 */

(function($) {
    'use strict';

    // Application state
    let trades = [];
    let filteredTrades = [];
    let sortConfig = { field: 'date', direction: 'desc' };
    let currentPage = 1;
    let tradesPerPage = 20;

    // Initialize when document is ready
    $(document).ready(function() {
        init();
        
        // Initialize Bootstrap tooltips
        if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }
    });

    /**
     * Initialize the application
     */
    function init() {
        setupFormHandlers();
        setupTableHandlers();
        setupChecklistHandlers();
        initializeTradesFromDOM();
        // setupAutoRefresh();
    }

    /**
     * Setup form event handlers
     */
    function setupFormHandlers() {
        // Main trade form submission
        $('#tradeJournalForm').on('submit', function(e) {
            e.preventDefault();
            saveTrade($(this));
        });

        // Edit form submission
        $(document).on('click', '#saveEditTrade', function() {
            saveEditedTrade();
        });

        // Auto-save draft functionality
        let autoSaveTimer;
        $('#tradeJournalForm input, #tradeJournalForm select, #tradeJournalForm textarea').on('input change', function() {
            clearTimeout(autoSaveTimer);
            autoSaveTimer = setTimeout(function() {
                saveDraft();
            }, 10000); // Auto-save after 10 seconds of inactivity
        });

        // Load draft on page load
        loadDraft();
    }

    /**
     * Setup table event handlers
     */
    function setupTableHandlers() {
        // Sortable headers
        $(document).on('click', '.sortable', function() {
            const field = $(this).data('sort');
            const direction = sortConfig.field === field && sortConfig.direction === 'asc' ? 'desc' : 'asc';
            
            sortConfig = { field, direction };
            
            // Update UI
            $('.sortable i').removeClass('fa-sort-up fa-sort-down').addClass('fa-sort');
            $(this).find('i').removeClass('fa-sort').addClass(direction === 'asc' ? 'fa-sort-up' : 'fa-sort-down');
            $('.sortable').removeClass('active');
            $(this).addClass('active');
            
            sortAndDisplayTrades();
        });

        // Search functionality
        $('#searchInput').on('input', function() {
            filterTrades();
        });

        // Filters
        $('#marketFilter, #sessionFilter, #outcomeFilter, #dateFromFilter, #dateToFilter').on('change', function() {
            filterTrades();
        });

        // Clear filters
        $('#clearFilters').on('click', function() {
            $('#marketFilter, #sessionFilter, #outcomeFilter, #dateFromFilter, #dateToFilter, #searchInput').val('');
            filterTrades();
        });

        // Edit trade
        $(document).on('click', '.btn-edit', function() {
            const tradeId = $(this).data('trade-id');
            editTrade(tradeId);
        });

        // Delete trade
        $(document).on('click', '.btn-delete', function() {
            const tradeId = $(this).data('trade-id');
            if (confirm(tradeJournalWP.strings.confirmDelete)) {
                deleteTrade(tradeId);
            }
        });

        // Refresh trades
        $('#refreshTrades').on('click', function() {
            loadTrades();
        });
    }

    /**
     * Setup checklist handlers
     */
    function setupChecklistHandlers() {
        // Update checklist progress
        $(document).on('change', '#strategyChecklist input[type="checkbox"]', function() {
            updateChecklistProgress();
        });

        // Reset checklist
        $('#resetChecklist').on('click', function() {
            $('#strategyChecklist input[type="checkbox"]').prop('checked', false);
            updateChecklistProgress();
            localStorage.removeItem('trade_journal_wp_checklist');
        });

        // Save checklist
        $('#saveChecklist').on('click', function() {
            saveChecklistState();
            showMessage('Checklist saved!', 'success');
        });

        // Load saved checklist
        loadChecklistState();
        updateChecklistProgress();
    }

    /**
     * Save trade
     */
    function saveTrade($form) {
        const submitBtn = $form.find('#submitBtn');
        const submitText = submitBtn.find('#submitText');
        const submitSpinner = submitBtn.find('#submitSpinner');
        
        // Show loading state
        submitBtn.prop('disabled', true);
        submitSpinner.removeClass('d-none');
        submitText.text('Saving...');

        const formData = new FormData($form[0]);
        formData.append('action', 'trade_journal_save_trade');
        formData.append('nonce', tradeJournalWP.nonce);

        $.ajax({
            url: tradeJournalWP.ajaxUrl,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    showMessage(tradeJournalWP.strings.saveSuccess, 'success');
                    $form[0].reset();
                    clearDraft();
                    
                    // Show loading in table during refresh
                    const tbody = $('#tradesTable tbody');
                    if (tbody.length > 0) {
                        tbody.html('<tr><td colspan="22" class="text-center py-4"><i class="fas fa-spinner fa-spin me-2"></i>Refreshing trades...</td></tr>');
                    }
                    
                    loadTrades();
                    updateStatistics();
                } else {
                    showMessage(response.data || tradeJournalWP.strings.saveFailed, 'danger');
                }
            },
            error: function() {
                showMessage(tradeJournalWP.strings.saveFailed, 'danger');
            },
            complete: function() {
                // Reset button state
                submitBtn.prop('disabled', false);
                submitSpinner.addClass('d-none');
                submitText.text('Save Trade Entry');
            }
        });
    }

    /**
     * Load trades from server
     */
    function loadTrades() {
        $.ajax({
            url: tradeJournalWP.ajaxUrl,
            type: 'POST',
            data: {
                action: 'trade_journal_get_trades',
                nonce: tradeJournalWP.nonce
            },
            success: function(response) {
                if (response.success) {
                    trades = response.data;
                    filterTrades();
                    updateStatistics();
                }
            },
            error: function() {
                console.error('Failed to load trades');
            }
        });
    }

    /**
     * Initialize trades array from existing DOM table data
     * This eliminates the need for initial AJAX call
     */
    function initializeTradesFromDOM() {
        trades = [];
        
        // Check if we have any trades in the table
        const tableRows = $('#tradesTable tbody tr[data-trade-id]');
        
        if (tableRows.length === 0) {
            // No trades found, initialize empty state
            filterTrades();
            updateStatistics();
            return;
        }
        
        // Parse each row and extract trade data
        tableRows.each(function() {
            const $row = $(this);
            const tradeId = $row.data('trade-id');
            
            if (!tradeId) return; // Skip if no trade ID
            
            // Extract data from each cell based on column position
            const cells = $row.find('td');
            
            const trade = {
                id: tradeId,
                date: parseDate(cells.eq(0).text().trim()),
                time: cells.eq(1).text().trim() || null,
                market: cells.eq(2).find('.badge').text().trim(),
                session: getSessionCode(cells.eq(3).text().trim()),
                direction: cells.eq(4).find('.badge').text().trim(),
                order_type: parseOptionalBadge(cells.eq(5)),
                strategy: parseOptionalText(cells.eq(6)),
                stop_loss: parsePrice(cells.eq(7).text().trim()),
                take_profit: parsePrice(cells.eq(8).text().trim()),
                entry_price: parsePrice(cells.eq(9).text().trim()),
                exit_price: parsePrice(cells.eq(10).text().trim()),
                outcome: parseOptionalBadge(cells.eq(11)),
                pl_percent: parsePercentage(cells.eq(12)),
                rr: parseRR(cells.eq(13).text().trim()),
                absolute_pl: parseAbsolutePL(cells.eq(14)),
                disciplined: parseYesNo(cells.eq(15)),
                followed_rules: parseYesNo(cells.eq(16)),
                rating: parseRating(cells.eq(17)),
                tf: parseTimeframes(cells.eq(18)),
                chart_htf: parseChartLink(cells.eq(19)),
                chart_ltf: parseChartLink(cells.eq(20)),
                comments: parseComments(cells.eq(21))
            };
            
            trades.push(trade);
        });
        
        // Initialize filters and statistics with the DOM data
        filterTrades();
        updateStatistics();
    }

    // Helper functions for parsing DOM data
    function parseDate(dateText) {
        // Convert from "15/08/2025" format to "2025-08-15" format
        const parts = dateText.split('/');
        if (parts.length === 3) {
            return `${parts[2]}-${parts[1].padStart(2, '0')}-${parts[0].padStart(2, '0')}`;
        }
        return dateText;
    }

    function getSessionCode(sessionText) {
        const sessionMap = {
            'London': 'LO',
            'New York': 'NY',
            'Asia': 'AS'
        };
        return sessionMap[sessionText] || sessionText;
    }

    function parseOptionalBadge($cell) {
        const badge = $cell.find('.badge');
        return badge.length ? badge.text().trim() : null;
    }

    function parseOptionalText($cell) {
        const text = $cell.text().trim();
        return text === '-' ? null : text;
    }

    function parsePrice(priceText) {
        const text = priceText.trim();
        return text === '-' ? null : parseFloat(text);
    }

    function parsePercentage($cell) {
        const span = $cell.find('span:not(.text-muted)');
        if (span.length) {
            const text = span.text().replace('%', '').replace('+', '');
            return parseFloat(text);
        }
        return null;
    }

    function parseRR(rrText) {
        const text = rrText.trim();
        return text === '-' ? null : parseFloat(text);
    }

    function parseAbsolutePL($cell) {
        const span = $cell.find('span:not(.text-muted)');
        if (span.length) {
            const text = span.text().replace('+', '');
            return parseFloat(text);
        }
        return null;
    }

    function parseYesNo($cell) {
        const badge = $cell.find('.badge');
        if (badge.length) {
            const text = badge.text().trim();
            return text === 'Yes' ? 'Y' : (text === 'No' ? 'N' : null);
        }
        return null;
    }

    function parseRating($cell) {
        const stars = $cell.find('span').text();
        if (stars && stars.includes('★')) {
            return stars.split('★').length - 1; // Count filled stars
        }
        return null;
    }

    function parseTimeframes($cell) {
        const badges = $cell.find('.badge');
        if (badges.length) {
            const timeframes = [];
            badges.each(function() {
                timeframes.push($(this).text().trim());
            });
            return timeframes;
        }
        return null;
    }

    function parseChartLink($cell) {
        const link = $cell.find('a');
        return link.length ? link.attr('href') : null;
    }

    function parseComments($cell) {
        const commentBtn = $cell.find('[title]');
        return commentBtn.length ? commentBtn.attr('title') : null;
    }

    /**
     * Filter trades based on search and filters
     */
    function filterTrades() {
        const searchTerm = $('#searchInput').val().toLowerCase();
        const marketFilter = $('#marketFilter').val();
        const sessionFilter = $('#sessionFilter').val();
        const outcomeFilter = $('#outcomeFilter').val();
        const dateFrom = $('#dateFromFilter').val();
        const dateTo = $('#dateToFilter').val();

        filteredTrades = trades.filter(function(trade) {
            // Search filter
            if (searchTerm && !Object.values(trade).some(value => 
                String(value).toLowerCase().includes(searchTerm))) {
                return false;
            }

            // Market filter
            if (marketFilter && trade.market !== marketFilter) {
                return false;
            }

            // Session filter
            if (sessionFilter && trade.session !== sessionFilter) {
                return false;
            }

            // Outcome filter
            if (outcomeFilter && trade.outcome !== outcomeFilter) {
                return false;
            }

            // Date range filter
            if (dateFrom && trade.date < dateFrom) {
                return false;
            }

            if (dateTo && trade.date > dateTo) {
                return false;
            }

            return true;
        });

        sortAndDisplayTrades();
    }

    /**
     * Sort and display trades
     */
    function sortAndDisplayTrades() {
        // Sort trades
        filteredTrades.sort(function(a, b) {
            let aVal = a[sortConfig.field];
            let bVal = b[sortConfig.field];

            // Handle null/undefined values
            if (aVal == null) aVal = '';
            if (bVal == null) bVal = '';

            // Convert to appropriate types for comparison
            if (sortConfig.field === 'date' || sortConfig.field === 'created_at') {
                aVal = new Date(aVal);
                bVal = new Date(bVal);
            } else if (['entry_price', 'exit_price', 'pl_percent', 'rr', 'stop_loss', 'take_profit', 'absolute_pl', 'rating'].includes(sortConfig.field)) {
                aVal = parseFloat(aVal) || 0;
                bVal = parseFloat(bVal) || 0;
            } else {
                aVal = String(aVal).toLowerCase();
                bVal = String(bVal).toLowerCase();
            }

            let result = 0;
            if (aVal < bVal) result = -1;
            if (aVal > bVal) result = 1;

            return sortConfig.direction === 'desc' ? -result : result;
        });

        displayTrades();
        updateTradesCount();
    }

    /**
     * Display trades in table
     */
    function displayTrades() {
        const tbody = $('#tradesTable tbody');
        
        if (filteredTrades.length === 0) {
            tbody.html('<tr id="noTradesRow"><td colspan="22" class="text-center text-muted py-4">No trades found. Start by adding your first trade!</td></tr>');
            return;
        }

        // Pagination
        const startIndex = (currentPage - 1) * tradesPerPage;
        const endIndex = startIndex + tradesPerPage;
        const pageTrades = filteredTrades.slice(startIndex, endIndex);

        let html = '';
        pageTrades.forEach(function(trade) {
            html += buildTradeRow(trade);
        });

        tbody.html(html);
        updatePagination();
        
        // Initialize Bootstrap tooltips
        if (window.bootstrap && window.bootstrap.Tooltip) {
            const tooltips = tbody.find('[data-bs-toggle="tooltip"]');
            tooltips.each(function() {
                new bootstrap.Tooltip(this);
            });
        }
    }

    /**
     * Build HTML for trade row
     */
    function buildTradeRow(trade) {
        const outcomeClass = getOutcomeBadgeClass(trade.outcome);
        const directionClass = trade.direction === 'LONG' ? 'badge-phoenix-success' : 'badge-phoenix-danger';
        const plClass = trade.pl_percent >= 0 ? 'text-success' : 'text-danger';
        const plPrefix = trade.pl_percent >= 0 ? '+' : '';
        
        // Build timeframes badges
        let timeframesHtml = '<span class="text-muted">-</span>';
        if (trade.tf && trade.tf.length > 0) {
            let timeframes = Array.isArray(trade.tf) ? trade.tf : JSON.parse(trade.tf || '[]');
            if (timeframes.length > 0) {
                timeframesHtml = timeframes.map(tf => 
                    `<span class="badge badge-sm rounded-pill badge-phoenix badge-phoenix-info me-1">${tf}</span>`
                ).join('');
            }
        }
        
        // Build chart links
        const chartHTFHtml = trade.chart_htf ? 
            `<a href="${trade.chart_htf}" target="_blank" rel="noopener noreferrer" class="text-primary text-decoration-none">
                <span class="badge badge-phoenix badge-phoenix-primary">Image <i class="fa fa-external-link" aria-hidden="true"></i></span>
            </a>` : '<span class="text-muted">-</span>';
        
        const chartLTFHtml = trade.chart_ltf ? 
            `<a href="${trade.chart_ltf}" target="_blank" rel="noopener noreferrer" class="text-primary text-decoration-none">
                <span class="badge badge-phoenix badge-phoenix-primary">Image <i class="fa fa-external-link" aria-hidden="true"></i></span>
            </a>` : '<span class="text-muted">-</span>';

        return `
            <tr class="btn-reveal-trigger" data-trade-id="${trade.id}">
                <td class="py-2 ps-3 align-middle white-space-nowrap fs-9">
                    ${formatDate(trade.date)}
                </td>
                <td class="py-2 align-middle fs-9">
                    ${trade.time || '-'}
                </td>
                <td class="py-2 align-middle text-center">
                    <span class="badge badge-sm rounded-pill badge-phoenix badge-phoenix-primary text-center">${trade.market}</span>
                </td>
                <td class="py-2 align-middle fs-9 text-center">
                    ${getSessionLabel(trade.session)}
                </td>
                <td class="py-2 align-middle text-center">
                    <span class="badge badge-sm rounded-pill badge-phoenix ${directionClass}">
                        ${trade.direction}
                    </span>
                </td>
                <td class="py-2 align-middle text-center">
                    ${trade.order_type ? `<span class="badge badge-sm rounded-pill badge-phoenix badge-phoenix-info">${trade.order_type}</span>` : '<span class="text-muted">-</span>'}
                </td>
                <td class="py-2 align-middle fs-9 text-center">${trade.strategy || '-'}</td>
                <td class="py-2 align-middle fs-9 text-center">${formatPrice(trade.stop_loss)}</td>
                <td class="py-2 align-middle fs-9 text-center">${formatPrice(trade.take_profit)}</td>
                <td class="py-2 align-middle fs-9 text-center">${formatPrice(trade.entry_price)}</td>
                <td class="py-2 align-middle fs-9 text-center">${formatPrice(trade.exit_price)}</td>
                <td class="py-2 align-middle text-center white-space-nowrap">
                    ${trade.outcome ? `<span class="badge badge-sm rounded-pill badge-phoenix text-center ${outcomeClass}">${getOutcomeLabel(trade.outcome)}</span>` : '<span class="text-muted">-</span>'}
                </td>
                <td class="py-2 align-middle text-center fs-9 fw-medium text-center">
                    ${trade.pl_percent !== null && trade.pl_percent !== undefined ? `<span class="${plClass}">${plPrefix}${parseFloat(trade.pl_percent).toFixed(2)}%</span>` : '<span class="text-muted">-</span>'}
                </td>
                <td class="py-2 align-middle text-center fs-9 fw-medium text-center">
                    ${formatRR(trade.rr)}
                </td>
                <td class="py-2 align-middle text-center fs-9 fw-medium">
                    ${trade.absolute_pl !== null && trade.absolute_pl !== undefined ? `<span class="${trade.absolute_pl >= 0 ? 'text-success' : 'text-danger'}">${trade.absolute_pl >= 0 ? '+' : ''}${parseFloat(trade.absolute_pl).toFixed(2)}</span>` : '<span class="text-muted">-</span>'}
                </td>
                <td class="py-2 align-middle text-center">
                    ${trade.disciplined ? `<span class="badge badge-sm rounded-pill badge-phoenix ${trade.disciplined === 'Y' ? 'badge-phoenix-success' : 'badge-phoenix-danger'}">${trade.disciplined === 'Y' ? 'Yes' : 'No'}</span>` : '<span class="text-muted">-</span>'}
                </td>
                <td class="py-2 align-middle text-center">
                    ${trade.followed_rules ? `<span class="badge badge-sm rounded-pill badge-phoenix ${trade.followed_rules === 'Y' ? 'badge-phoenix-success' : 'badge-phoenix-danger'}">${trade.followed_rules === 'Y' ? 'Yes' : 'No'}</span>` : '<span class="text-muted">-</span>'}
                </td>
                <td class="py-2 align-middle text-center">
                    ${trade.rating ? `<span class="text-warning fs-8">${'★'.repeat(trade.rating)}${'☆'.repeat(5 - trade.rating)}</span>` : '<span class="text-muted">-</span>'}
                </td>
                <td class="py-2 align-middle text-center">
                    ${timeframesHtml}
                </td>
                <td class="py-2 align-middle fs-9 text-center">
                    ${chartHTFHtml}
                </td>
                <td class="py-2 align-middle fs-9 text-center">
                    ${chartLTFHtml}
                </td>
                <td class="py-2 align-middle white-space-nowrap text-center">
                    <div class="btn-group btn-group-sm ydcoza-btn-group-tiny" role="group" aria-label="Trade Actions">
                        ${trade.comments ? `<button type="button" class="btn btn-subtle-info" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-html="false" title="${escapeHtml(trade.comments)}"><i class="fas fa-comment opacity-75"></i></button>` : ''}
                        <button type="button" class="btn btn-subtle-primary btn-edit" data-trade-id="${trade.id}" title="Edit">
                            <i class="fas fa-edit opacity-75"></i>
                        </button>
                        <button type="button" class="btn btn-subtle-danger btn-delete" data-trade-id="${trade.id}" title="Delete">
                            <i class="fas fa-trash opacity-75"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    }

    /**
     * Edit trade
     */
    function editTrade(tradeId) {
        const trade = trades.find(t => t.id === tradeId);
        if (!trade) return;

        // Populate edit form
        $('#editTradeId').val(trade.id);
        
        // Create form fields HTML
        let formFieldsHtml = generateEditFormFields(trade);
        $('#editFormFields').html(formFieldsHtml);

        // Show modal
        $('#editTradeModal').modal('show');
    }

    /**
     * Generate edit form fields
     */
    function generateEditFormFields(trade) {
        // Parse timeframes if they exist
        let selectedTimeframes = [];
        if (trade.tf) {
            try {
                selectedTimeframes = typeof trade.tf === 'string' ? JSON.parse(trade.tf) : trade.tf;
                if (!Array.isArray(selectedTimeframes)) {
                    selectedTimeframes = [];
                }
            } catch (e) {
                selectedTimeframes = [];
            }
        }

        return `
            <!-- Trade Basics Section -->
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <label class="form-label">
                        <i class="fas fa-globe me-2 text-primary"></i>Market
                    </label>
                    <select class="form-select form-select-sm" name="market" required>
                        <option value="">Select an option</option>
                        <option value="XAUUSD" ${trade.market === 'XAUUSD' ? 'selected' : ''}>XAUUSD</option>
                        <option value="EU" ${trade.market === 'EU' ? 'selected' : ''}>EURUSD</option>
                        <option value="GU" ${trade.market === 'GU' ? 'selected' : ''}>GBPUSD</option>
                        <option value="UJ" ${trade.market === 'UJ' ? 'selected' : ''}>USDJPY</option>
                        <option value="US30" ${trade.market === 'US30' ? 'selected' : ''}>US30</option>
                        <option value="NAS100" ${trade.market === 'NAS100' ? 'selected' : ''}>NAS100</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">
                        <i class="fas fa-clock me-2 text-primary"></i>Session
                    </label>
                    <select class="form-select form-select-sm" name="session" required>
                        <option value="">Select an option</option>
                        <option value="LO" ${trade.session === 'LO' ? 'selected' : ''}>London</option>
                        <option value="NY" ${trade.session === 'NY' ? 'selected' : ''}>New York</option>
                        <option value="AS" ${trade.session === 'AS' ? 'selected' : ''}>Asia</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">
                        <i class="fas fa-calendar me-2 text-primary"></i>Date
                    </label>
                    <input type="date" class="form-control form-control-sm" name="date" value="${trade.date}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">
                        <i class="fas fa-clock me-2 text-primary"></i>Time
                    </label>
                    <input type="time" class="form-control form-control-sm" name="time" value="${trade.time || ''}">
                </div>
            </div>

            <!-- Performance Section -->
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <label class="form-label">
                        <i class="fas fa-trending-up me-2 text-success"></i>Direction
                    </label>
                    <select class="form-select form-select-sm" name="direction" required>
                        <option value="">Select an option</option>
                        <option value="LONG" ${trade.direction === 'LONG' ? 'selected' : ''}>Long</option>
                        <option value="SHORT" ${trade.direction === 'SHORT' ? 'selected' : ''}>Short</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">
                        <i class="fas fa-shopping-cart me-2 text-info"></i>Order Type
                    </label>
                    <select class="form-select form-select-sm" name="order_type">
                        <option value="">Select order type</option>
                        <option value="Market" ${trade.order_type === 'Market' ? 'selected' : ''}>Market</option>
                        <option value="Limit" ${trade.order_type === 'Limit' ? 'selected' : ''}>Limit</option>
                        <option value="Stop" ${trade.order_type === 'Stop' ? 'selected' : ''}>Stop</option>
                    </select>
                </div>
            </div>

            <!-- Trade Setup Section -->
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label">
                        <i class="fas fa-chess me-2 text-primary"></i>Strategy
                    </label>
                    <input type="text" class="form-control form-control-sm" name="strategy" value="${trade.strategy || ''}" placeholder="e.g., FTR Breakout, Support/Resistance">
                </div>
                <div class="col-md-3">
                    <label class="form-label">
                        <i class="fas fa-shield-alt me-2 text-danger"></i>Stop Loss
                    </label>
                    <input type="number" step="0.00001" class="form-control form-control-sm" name="stop_loss" value="${trade.stop_loss || ''}" placeholder="0.00000">
                </div>
                <div class="col-md-3">
                    <label class="form-label">
                        <i class="fas fa-bullseye me-2 text-success"></i>Take Profit
                    </label>
                    <input type="number" step="0.00001" class="form-control form-control-sm" name="take_profit" value="${trade.take_profit || ''}" placeholder="0.00000">
                </div>
            </div>

            <!-- Price Section -->
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <label class="form-label">
                        <i class="fas fa-dollar-sign me-2 text-success"></i>Entry Price
                    </label>
                    <input type="number" step="0.00001" class="form-control form-control-sm" name="entry_price" value="${trade.entry_price || ''}" placeholder="0.00000">
                </div>
                <div class="col-md-3">
                    <label class="form-label">
                        <i class="fas fa-dollar-sign me-2 text-success"></i>Exit Price
                    </label>
                    <input type="number" step="0.00001" class="form-control form-control-sm" name="exit_price" value="${trade.exit_price || ''}" placeholder="0.00000">
                </div>
                <div class="col-md-3">
                    <label class="form-label">
                        <i class="fas fa-chart-bar me-2 text-success"></i>Outcome
                    </label>
                    <select class="form-select form-select-sm" name="outcome">
                        <option value="">Select an option</option>
                        <option value="W" ${trade.outcome === 'W' ? 'selected' : ''}>Win</option>
                        <option value="L" ${trade.outcome === 'L' ? 'selected' : ''}>Loss</option>
                        <option value="BE" ${trade.outcome === 'BE' ? 'selected' : ''}>Break Even</option>
                        <option value="C" ${trade.outcome === 'C' ? 'selected' : ''}>Cancelled</option>
                    </select>
                </div>
            </div>

            <!-- Metrics Section -->
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <label class="form-label">
                        <i class="fas fa-chart-bar me-2 text-info"></i>P/L %
                    </label>
                    <input type="number" step="0.01" class="form-control form-control-sm" name="pl_percent" value="${trade.pl_percent || ''}" placeholder="0.00">
                </div>
                <div class="col-md-3">
                    <label class="form-label">
                        <i class="fas fa-chart-bar me-2 text-info"></i>RR
                    </label>
                    <input type="number" step="0.01" class="form-control form-control-sm" name="rr" value="${trade.rr || ''}" placeholder="1.0">
                </div>
                <div class="col-md-3">
                    <label class="form-label">
                        <i class="fas fa-dollar-sign me-2 text-success"></i>Absolute P/L
                    </label>
                    <input type="number" step="0.01" class="form-control form-control-sm" name="absolute_pl" value="${trade.absolute_pl || ''}" placeholder="0.00">
                </div>
            </div>

            <!-- Trade Review Section -->
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <label class="form-label">
                        <i class="fas fa-user-check me-2 text-warning"></i>Disciplined
                    </label>
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="disciplined" value="Y" id="editDisciplinedY" ${trade.disciplined === 'Y' ? 'checked' : ''}>
                            <label class="form-check-label" for="editDisciplinedY">Yes</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="disciplined" value="N" id="editDisciplinedN" ${trade.disciplined === 'N' ? 'checked' : ''}>
                            <label class="form-check-label" for="editDisciplinedN">No</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label">
                        <i class="fas fa-clipboard-check me-2 text-info"></i>Followed Rules
                    </label>
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="followed_rules" value="Y" id="editFollowedY" ${trade.followed_rules === 'Y' ? 'checked' : ''}>
                            <label class="form-check-label" for="editFollowedY">Yes</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="followed_rules" value="N" id="editFollowedN" ${trade.followed_rules === 'N' ? 'checked' : ''}>
                            <label class="form-check-label" for="editFollowedN">No</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label">
                        <i class="fas fa-star me-2 text-warning"></i>Rating
                    </label>
                    <select class="form-select form-select-sm" name="rating">
                        <option value="">Select rating</option>
                        <option value="1" ${trade.rating == 1 ? 'selected' : ''}>★☆☆☆☆ (1/5)</option>
                        <option value="2" ${trade.rating == 2 ? 'selected' : ''}>★★☆☆☆ (2/5)</option>
                        <option value="3" ${trade.rating == 3 ? 'selected' : ''}>★★★☆☆ (3/5)</option>
                        <option value="4" ${trade.rating == 4 ? 'selected' : ''}>★★★★☆ (4/5)</option>
                        <option value="5" ${trade.rating == 5 ? 'selected' : ''}>★★★★★ (5/5)</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">
                        <i class="fas fa-search me-2 text-info"></i>Timeframes
                    </label>
                    <div class="row g-2">
                        <div class="col-auto">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="tf[]" value="M1" id="editTfM1" ${selectedTimeframes.includes('M1') ? 'checked' : ''}>
                                <label class="form-check-label" for="editTfM1">M1</label>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="tf[]" value="M5" id="editTfM5" ${selectedTimeframes.includes('M5') ? 'checked' : ''}>
                                <label class="form-check-label" for="editTfM5">M5</label>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="tf[]" value="M15" id="editTfM15" ${selectedTimeframes.includes('M15') ? 'checked' : ''}>
                                <label class="form-check-label" for="editTfM15">M15</label>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="tf[]" value="M30" id="editTfM30" ${selectedTimeframes.includes('M30') ? 'checked' : ''}>
                                <label class="form-check-label" for="editTfM30">M30</label>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="tf[]" value="H1" id="editTfH1" ${selectedTimeframes.includes('H1') ? 'checked' : ''}>
                                <label class="form-check-label" for="editTfH1">H1</label>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="tf[]" value="H4" id="editTfH4" ${selectedTimeframes.includes('H4') ? 'checked' : ''}>
                                <label class="form-check-label" for="editTfH4">H4</label>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="tf[]" value="D1" id="editTfD1" ${selectedTimeframes.includes('D1') ? 'checked' : ''}>
                                <label class="form-check-label" for="editTfD1">D1</label>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="tf[]" value="W1" id="editTfW1" ${selectedTimeframes.includes('W1') ? 'checked' : ''}>
                                <label class="form-check-label" for="editTfW1">W1</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label">
                        <i class="fas fa-chart-line me-2 text-warning"></i>Chart HTF
                    </label>
                    <input type="url" class="form-control form-control-sm" name="chart_htf" value="${trade.chart_htf || ''}" placeholder="https://www.tradingview.com/...">
                </div>
                <div class="col-md-6">
                    <label class="form-label">
                        <i class="fas fa-chart-area me-2 text-warning"></i>Chart LTF
                    </label>
                    <input type="url" class="form-control form-control-sm" name="chart_ltf" value="${trade.chart_ltf || ''}" placeholder="https://www.tradingview.com/...">
                </div>
            </div>

            <!-- Comments Section -->
            <div class="row g-3 mb-4">
                <div class="col-12">
                    <label class="form-label">
                        <i class="fas fa-comment me-2 text-info"></i>Comments
                    </label>
                    <textarea class="form-control form-control-sm" name="comments" rows="3" placeholder="Add any additional notes about this trade...">${trade.comments || ''}</textarea>
                </div>
            </div>
        `;
    }

    /**
     * Save edited trade
     */
    function saveEditedTrade() {
        const form = $('#editTradeForm');
        const formData = new FormData(form[0]);
        const saveBtn = $('#saveEditTrade');
        
        // Show loading state
        saveBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Saving...');
        
        formData.append('action', 'trade_journal_update_trade');
        formData.append('nonce', tradeJournalWP.nonce);
        formData.append('id', $('#editTradeId').val());

        $.ajax({
            url: tradeJournalWP.ajaxUrl,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    $('#editTradeModal').modal('hide');
                    showMessage('Trade updated successfully!', 'success');
                    
                    // Show loading in table during refresh
                    const tbody = $('#tradesTable tbody');
                    tbody.html('<tr><td colspan="22" class="text-center py-4"><i class="fas fa-spinner fa-spin me-2"></i>Refreshing trades...</td></tr>');
                    
                    loadTrades();
                    updateStatistics();
                } else {
                    showMessage(response.data || 'Failed to update trade', 'danger');
                }
            },
            error: function() {
                showMessage('Failed to update trade', 'danger');
            },
            complete: function() {
                // Reset button state
                saveBtn.prop('disabled', false).html('<i class="fas fa-save me-2"></i>Save Changes');
            }
        });
    }

    /**
     * Delete trade
     */
    function deleteTrade(tradeId) {
        $.ajax({
            url: tradeJournalWP.ajaxUrl,
            type: 'POST',
            data: {
                action: 'trade_journal_delete_trade',
                nonce: tradeJournalWP.nonce,
                id: tradeId
            },
            success: function(response) {
                if (response.success) {
                    showMessage(tradeJournalWP.strings.deleteSuccess, 'success');
                    
                    // Show loading in table during refresh
                    const tbody = $('#tradesTable tbody');
                    tbody.html('<tr><td colspan="22" class="text-center py-4"><i class="fas fa-spinner fa-spin me-2"></i>Refreshing trades...</td></tr>');
                    
                    loadTrades();
                    updateStatistics();
                } else {
                    showMessage(response.data || tradeJournalWP.strings.deleteFailed, 'danger');
                }
            },
            error: function() {
                showMessage(tradeJournalWP.strings.deleteFailed, 'danger');
            }
        });
    }

    /**
     * Update statistics display
     */
    function updateStatistics() {
        // This would typically reload statistics from server
        // For now, we'll calculate basic stats from loaded trades
        const stats = calculateBasicStats(trades);
        updateStatsDisplay(stats);
    }

    /**
     * Calculate basic statistics
     */
    function calculateBasicStats(tradesData) {
        if (!tradesData || tradesData.length === 0) {
            return {
                totalTrades: 0,
                wins: 0,
                losses: 0,
                breakEven: 0,
                cancelled: 0,
                winRate: 0,
                totalPL: 0
            };
        }

        const stats = {
            totalTrades: tradesData.length,
            wins: tradesData.filter(t => t.outcome === 'W').length,
            losses: tradesData.filter(t => t.outcome === 'L').length,
            breakEven: tradesData.filter(t => t.outcome === 'BE').length,
            cancelled: tradesData.filter(t => t.outcome === 'C').length,
            totalPL: tradesData.reduce((sum, t) => sum + (parseFloat(t.pl_percent) || 0), 0)
        };

        stats.winRate = stats.totalTrades > 0 ? (stats.wins / stats.totalTrades) * 100 : 0;

        return stats;
    }

    /**
     * Update stats display
     */
    function updateStatsDisplay(stats) {
        $('#totalTrades').text(stats.totalTrades.toLocaleString());
        $('#winRate').text(stats.winRate.toFixed(1) + '%');
        $('#winsCount').text(stats.wins);
        $('#lossesCount').text(stats.losses);
        $('#breakEvenCount').text(stats.breakEven);
        $('#cancelledCount').text(stats.cancelled);
        
        const accountGain = $('#accountGain');
        if (accountGain.length) {
            accountGain.text(stats.totalPL.toFixed(2) + '%');
            accountGain.removeClass('badge-phoenix-success badge-phoenix-danger');
            accountGain.addClass(stats.totalPL >= 0 ? 'badge-phoenix-success' : 'badge-phoenix-danger');
        }
    }

    /**
     * Update checklist progress
     */
    function updateChecklistProgress() {
        const totalChecks = $('#strategyChecklist input[type="checkbox"]').length;
        const completedChecks = $('#strategyChecklist input[type="checkbox"]:checked').length;
        const percentage = totalChecks > 0 ? (completedChecks / totalChecks) * 100 : 0;

        $('#checklistScore').text(completedChecks + '/' + totalChecks);
        $('#checklistProgress').css('width', percentage + '%');

        const status = $('#checklistStatus');
        const proceedBtn = $('#proceedToTrade');

        if (percentage >= 80) {
            status.text('Ready to Trade').removeClass('bg-secondary bg-warning').addClass('bg-success');
            proceedBtn.prop('disabled', false);
        } else if (percentage >= 60) {
            status.text('Almost Ready').removeClass('bg-secondary bg-success').addClass('bg-warning');
            proceedBtn.prop('disabled', true);
        } else {
            status.text('Not Ready').removeClass('bg-success bg-warning').addClass('bg-secondary');
            proceedBtn.prop('disabled', true);
        }
    }

    /**
     * Save checklist state to localStorage
     */
    function saveChecklistState() {
        const checklistState = {};
        $('#strategyChecklist input[type="checkbox"]').each(function() {
            checklistState[this.id] = this.checked;
        });
        localStorage.setItem('trade_journal_wp_checklist', JSON.stringify(checklistState));
    }

    /**
     * Load checklist state from localStorage
     */
    function loadChecklistState() {
        const saved = localStorage.getItem('trade_journal_wp_checklist');
        if (saved) {
            const checklistState = JSON.parse(saved);
            Object.keys(checklistState).forEach(function(id) {
                $('#' + id).prop('checked', checklistState[id]);
            });
        }
    }

    /**
     * Save form draft to localStorage
     */
    function saveDraft() {
        const formData = $('#tradeJournalForm').serialize();
        localStorage.setItem('trade_journal_wp_draft', formData);
    }

    /**
     * Load form draft from localStorage
     */
    function loadDraft() {
        const draft = localStorage.getItem('trade_journal_wp_draft');
        if (draft) {
            const params = new URLSearchParams(draft);
            params.forEach(function(value, key) {
                const input = $('[name="' + key + '"]');
                if (input.is(':checkbox')) {
                    input.prop('checked', true);
                } else {
                    // Only override if we have a meaningful value and it's not date/time fields with current defaults
                    if (value && value.trim() !== '') {
                        // For date/time fields, only override if the saved value is different from current defaults
                        if (key === 'date' || key === 'time') {
                            const currentValue = input.val();
                            if (currentValue !== value) {
                                input.val(value);
                            }
                        } else {
                            input.val(value);
                        }
                    }
                }
            });
        }
    }

    /**
     * Clear saved draft
     */
    function clearDraft() {
        localStorage.removeItem('trade_journal_wp_draft');
    }

    /**
     * Setup auto-refresh for statistics
     */
    // function setupAutoRefresh() {
    //     $('#autoRefreshStats').on('change', function() {
    //         if (this.checked) {
    //             setInterval(function() {
    //                 updateStatistics();
    //             }, 30000); // Refresh every 30 seconds
    //         }
    //     });
    // }

    /**
     * Update trades count display
     */
    function updateTradesCount() {
        $('#tradesCount').text(filteredTrades.length);
    }

    /**
     * Update pagination
     */
    function updatePagination() {
        const totalPages = Math.ceil(filteredTrades.length / tradesPerPage);
        const pagination = $('#tradesPagination');
        
        if (totalPages <= 1) {
            pagination.empty();
            return;
        }

        let html = '';
        for (let i = 1; i <= totalPages; i++) {
            const activeClass = i === currentPage ? 'active' : '';
            html += `<li class="page-item ${activeClass}"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
        }

        pagination.html(html);

        // Handle pagination clicks
        pagination.find('a').on('click', function(e) {
            e.preventDefault();
            currentPage = parseInt($(this).data('page'));
            displayTrades();
        });
    }

    /**
     * Show message to user
     */
    function showMessage(message, type) {
        const alertHtml = `
            <div class="alert alert-subtle-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        const container = $('#tradeJournalMessages');
        container.html(alertHtml);
        
        // Auto-dismiss after 5 seconds
        setTimeout(function() {
            container.find('.alert').alert('close');
        }, 5000);
    }

    // Utility functions
    function escapeHtml(unsafe) {
        if (!unsafe) return '';
        return unsafe
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    function formatDate(date) {
        return new Date(date).toLocaleDateString('en-US', { 
            month: 'short', 
            day: 'numeric', 
            year: 'numeric' 
        });
    }

    function formatPrice(price) {
        if (!price || price === null || price === '') return '-';
        
        // Truncate to 3 decimals without rounding (like PHP)
        const multiplier = Math.pow(10, 3);
        const truncated = Math.floor(parseFloat(price) * multiplier) / multiplier;
        
        return truncated.toFixed(3);
    }

    function formatPercentage(value) {
        return value ? parseFloat(value).toFixed(2) + '%' : '-';
    }

    function formatRR(value) {
        if (!value || value === null || value === '') return '-';
        
        return parseFloat(value).toFixed(2) + ':1';
    }

    function getOutcomeBadgeClass(outcome) {
        const classes = {
            'W': 'badge-phoenix-success',
            'L': 'badge-phoenix-danger', 
            'BE': 'badge-phoenix-secondary',
            'C': 'badge-phoenix-warning'
        };
        return classes[outcome] || 'badge-phoenix-secondary';
    }

    function getOutcomeLabel(outcome) {
        const labels = {
            'W': 'Win',
            'L': 'Loss',
            'BE': 'Break Even',
            'C': 'Cancelled'
        };
        return labels[outcome] || outcome;
    }

    function getSessionLabel(session) {
        const labels = {
            'LO': 'London',
            'NY': 'New York',
            'AS': 'Asia'
        };
        return labels[session] || session;
    }

})(jQuery);
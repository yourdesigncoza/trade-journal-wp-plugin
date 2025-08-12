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
        loadTrades();
        setupAutoRefresh();
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
            } else if (['entry_price', 'exit_price', 'pl_percent', 'rr'].includes(sortConfig.field)) {
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
        const tbody = $('#tradesTableBody');
        
        if (filteredTrades.length === 0) {
            tbody.html('<tr id="noTradesRow"><td colspan="10" class="text-center text-muted py-4">No trades found.</td></tr>');
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
    }

    /**
     * Build HTML for trade row
     */
    function buildTradeRow(trade) {
        const outcomeClass = getOutcomeBadgeClass(trade.outcome);
        const directionClass = trade.direction === 'LONG' ? 'bg-success' : 'bg-danger';
        const plClass = trade.pl_percent >= 0 ? 'text-success' : 'text-danger';

        return `
            <tr data-trade-id="${trade.id}" class="fade-in">
                <td>${formatDate(trade.date)}</td>
                <td><span class="badge bg-primary">${trade.market}</span></td>
                <td>${getSessionLabel(trade.session)}</td>
                <td><span class="badge ${directionClass}">${trade.direction}</span></td>
                <td>${formatPrice(trade.entry_price)}</td>
                <td>${formatPrice(trade.exit_price)}</td>
                <td>${trade.outcome ? `<span class="badge ${outcomeClass}">${getOutcomeLabel(trade.outcome)}</span>` : '<span class="text-muted">-</span>'}</td>
                <td>${trade.pl_percent ? `<span class="${plClass}">${formatPercentage(trade.pl_percent)}</span>` : '<span class="text-muted">-</span>'}</td>
                <td>${formatRR(trade.rr)}</td>
                <td>
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-outline-primary btn-edit" data-trade-id="${trade.id}" title="Edit Trade">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-outline-danger btn-delete" data-trade-id="${trade.id}" title="Delete Trade">
                            <i class="fas fa-trash"></i>
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
        return `
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label">Market</label>
                    <select class="form-select" name="market" required>
                        <option value="XAUUSD" ${trade.market === 'XAUUSD' ? 'selected' : ''}>XAUUSD</option>
                        <option value="EU" ${trade.market === 'EU' ? 'selected' : ''}>EURUSD</option>
                        <option value="GU" ${trade.market === 'GU' ? 'selected' : ''}>GBPUSD</option>
                        <option value="UJ" ${trade.market === 'UJ' ? 'selected' : ''}>USDJPY</option>
                        <option value="US30" ${trade.market === 'US30' ? 'selected' : ''}>US30</option>
                        <option value="NAS100" ${trade.market === 'NAS100' ? 'selected' : ''}>NAS100</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Session</label>
                    <select class="form-select" name="session" required>
                        <option value="LO" ${trade.session === 'LO' ? 'selected' : ''}>London</option>
                        <option value="NY" ${trade.session === 'NY' ? 'selected' : ''}>New York</option>
                        <option value="AS" ${trade.session === 'AS' ? 'selected' : ''}>Asia</option>
                    </select>
                </div>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label">Date</label>
                    <input type="date" class="form-control" name="date" value="${trade.date}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Time</label>
                    <input type="time" class="form-control" name="time" value="${trade.time || ''}">
                </div>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label">Direction</label>
                    <select class="form-select" name="direction" required>
                        <option value="LONG" ${trade.direction === 'LONG' ? 'selected' : ''}>Long</option>
                        <option value="SHORT" ${trade.direction === 'SHORT' ? 'selected' : ''}>Short</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Outcome</label>
                    <select class="form-select" name="outcome">
                        <option value="">Select outcome</option>
                        <option value="W" ${trade.outcome === 'W' ? 'selected' : ''}>Win</option>
                        <option value="L" ${trade.outcome === 'L' ? 'selected' : ''}>Loss</option>
                        <option value="BE" ${trade.outcome === 'BE' ? 'selected' : ''}>Break Even</option>
                        <option value="C" ${trade.outcome === 'C' ? 'selected' : ''}>Cancelled</option>
                    </select>
                </div>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label">Entry Price</label>
                    <input type="number" step="0.00001" class="form-control" name="entry_price" value="${trade.entry_price || ''}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Exit Price</label>
                    <input type="number" step="0.00001" class="form-control" name="exit_price" value="${trade.exit_price || ''}">
                </div>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label">P/L %</label>
                    <input type="number" step="0.01" class="form-control" name="pl_percent" value="${trade.pl_percent || ''}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">RR</label>
                    <input type="number" step="0.01" class="form-control" name="rr" value="${trade.rr || ''}">
                </div>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-12">
                    <label class="form-label">Comments</label>
                    <textarea class="form-control" name="comments" rows="3">${trade.comments || ''}</textarea>
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
                    loadTrades();
                    updateStatistics();
                } else {
                    showMessage(response.data || 'Failed to update trade', 'danger');
                }
            },
            error: function() {
                showMessage('Failed to update trade', 'danger');
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
                    input.val(value);
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
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
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
    function formatDate(date) {
        return new Date(date).toLocaleDateString('en-US', { 
            month: 'short', 
            day: 'numeric', 
            year: 'numeric' 
        });
    }

    function formatPrice(price) {
        return price ? parseFloat(price).toFixed(5) : '-';
    }

    function formatPercentage(value) {
        return value ? parseFloat(value).toFixed(2) + '%' : '-';
    }

    function formatRR(value) {
        return value ? parseFloat(value).toFixed(2) + ':1' : '-';
    }

    function getOutcomeBadgeClass(outcome) {
        const classes = {
            'W': 'bg-success',
            'L': 'bg-danger',
            'BE': 'bg-secondary',
            'C': 'bg-warning'
        };
        return classes[outcome] || 'bg-secondary';
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
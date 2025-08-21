jQuery(document).ready(function($) {
    'use strict';

    const loginForm = $('#trade-journal-login-form');
    const submitButton = $('#trade-journal-login-submit');
    const messagesContainer = $('#trade-journal-login-messages');
    const loginText = submitButton.find('.login-text');
    const loginSpinner = submitButton.find('.login-spinner');

    // Password visibility toggle
    $('[data-password-toggle]').on('click', function(e) {
        e.preventDefault();
        const container = $(this).closest('[data-password]');
        const passwordInput = container.find('[data-password-input]');
        const showIcon = $(this).find('.show');
        const hideIcon = $(this).find('.hide');

        if (passwordInput.attr('type') === 'password') {
            passwordInput.attr('type', 'text');
            showIcon.addClass('d-none');
            hideIcon.removeClass('d-none');
        } else {
            passwordInput.attr('type', 'password');
            showIcon.removeClass('d-none');
            hideIcon.addClass('d-none');
        }
    });

    // Form submission handler
    loginForm.on('submit', function(e) {
        e.preventDefault();
        
        // Clear previous messages
        clearMessages();
        
        // Disable form and show loading state
        setLoadingState(true);
        
        const formData = {
            action: 'trade_journal_login',
            nonce: tradeJournalLogin.nonce,
            username: $('#trade-journal-username').val().trim(),
            password: $('#trade-journal-password').val(),
            remember: $('#trade-journal-remember').is(':checked') ? '1' : '0',
            redirect_to: $('input[name="redirect_to"]').val()
        };

        // Basic client-side validation
        if (!formData.username || !formData.password) {
            showMessage('Please enter both username and password.', 'error');
            setLoadingState(false);
            return;
        }

        // AJAX login request
        $.ajax({
            url: tradeJournalLogin.ajaxUrl,
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    showMessage(response.data.message, 'success');
                    
                    // Redirect after short delay
                    setTimeout(function() {
                        if (response.data.redirect_to) {
                            window.location.href = response.data.redirect_to;
                        } else {
                            location.reload();
                        }
                    }, 1500);
                } else {
                    showMessage(response.data.message, 'error');
                    setLoadingState(false);
                }
            },
            error: function(xhr, status, error) {
                console.error('Login AJAX Error:', error);
                showMessage('An error occurred during login. Please try again.', 'error');
                setLoadingState(false);
            }
        });
    });

    // Show message function
    function showMessage(message, type) {
        const alertClass = type === 'success' ? 'alert-subtle-success' : 'alert-subtle-danger';
        const iconClass = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
        
        const messageHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                <i class="fas ${iconClass} me-2"></i>
                <span class="fw-semibold">${message}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        
        messagesContainer.html(messageHtml);
        
        // Scroll to message if not visible
        if (messagesContainer.offset().top < $(window).scrollTop()) {
            $('html, body').animate({
                scrollTop: messagesContainer.offset().top - 20
            }, 500);
        }
        
        // Auto-hide success messages after 5 seconds
        if (type === 'success') {
            setTimeout(function() {
                messagesContainer.find('.alert').fadeOut();
            }, 5000);
        }
    }

    // Clear messages
    function clearMessages() {
        messagesContainer.empty();
    }

    // Set loading state
    function setLoadingState(loading) {
        if (loading) {
            submitButton.prop('disabled', true);
            loginText.addClass('d-none');
            loginSpinner.removeClass('d-none');
            loginForm.find('input').prop('disabled', true);
        } else {
            submitButton.prop('disabled', false);
            loginText.removeClass('d-none');
            loginSpinner.addClass('d-none');
            loginForm.find('input').prop('disabled', false);
        }
    }

    // Handle Enter key in form fields
    loginForm.find('input').on('keypress', function(e) {
        if (e.which === 13) { // Enter key
            e.preventDefault();
            loginForm.submit();
        }
    });

    // Focus on first input field
    $('#trade-journal-username').focus();

    // Clear form on page load if needed
    if (window.performance && window.performance.navigation.type === 2) {
        // Page was loaded from back/forward cache
        loginForm[0].reset();
        clearMessages();
        setLoadingState(false);
    }
});
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
        if (type === 'success') {
            // Ultra-minimal inline success message
            const messageHtml = `
                <div class="text-center mb-3">
                    <span class="d-inline-flex align-items-center text-success fs-9 py-1">
                        <i class="fas fa-check-circle me-1 fs-10"></i>
                        ${message}
                    </span>
                </div>
            `;
            messagesContainer.html(messageHtml);
        } else {
            // Keep alert for errors as they need more attention
            const messageHtml = `
                <div class="alert alert-subtle-danger alert-dismissible fade show py-2 fs-9" role="alert">
                    <i class="fas fa-exclamation-circle me-2 fs-10"></i>
                    <span class="fw-semibold fs-9">${message}</span>
                    <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;
            messagesContainer.html(messageHtml);
        }
        
        // Auto-hide success messages after 3 seconds (reduced from 5)
        if (type === 'success') {
            setTimeout(function() {
                messagesContainer.fadeOut(300);
            }, 3000);
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

    // Forgot Password Form Handler
    const forgotPasswordForm = $('#trade-journal-forgot-password-form');
    const forgotPasswordSubmit = $('#trade-journal-forgot-password-submit');
    const forgotPasswordMessages = $('#trade-journal-forgot-password-messages');
    const resetText = forgotPasswordSubmit.find('.reset-text');
    const resetSpinner = forgotPasswordSubmit.find('.reset-spinner');

    if (forgotPasswordForm.length) {
        forgotPasswordForm.on('submit', function(e) {
            e.preventDefault();
            
            // Clear previous messages
            forgotPasswordMessages.empty();
            
            // Set loading state
            setForgotPasswordLoadingState(true);
            
            const formData = {
                action: 'trade_journal_forgot_password',
                nonce: tradeJournalLogin.forgotPasswordNonce,
                user_email: $('#trade-journal-user-email').val().trim()
            };

            // Basic validation
            if (!formData.user_email) {
                showForgotPasswordMessage('Please enter your email address.', 'error');
                setForgotPasswordLoadingState(false);
                return;
            }

            // AJAX forgot password request
            $.ajax({
                url: tradeJournalLogin.ajaxUrl,
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        showForgotPasswordMessage(response.data.message, 'success');
                        $('#trade-journal-user-email').val(''); // Clear email field
                        setForgotPasswordLoadingState(false);
                    } else {
                        showForgotPasswordMessage(response.data.message, 'error');
                        setForgotPasswordLoadingState(false);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Forgot Password AJAX Error:', error);
                    showForgotPasswordMessage('An error occurred. Please try again.', 'error');
                    setForgotPasswordLoadingState(false);
                }
            });
        });

        // Focus on email field for forgot password form
        $('#trade-journal-user-email').focus();
    }

    // Forgot Password Helper Functions
    function showForgotPasswordMessage(message, type) {
        if (type === 'success') {
            // Ultra-minimal inline success message
            const messageHtml = `
                <div class="text-center mb-3">
                    <span class="d-inline-flex align-items-center text-success fs-9 py-1">
                        <i class="fas fa-check-circle me-1 fs-10"></i>
                        ${message}
                    </span>
                </div>
            `;
            forgotPasswordMessages.html(messageHtml);
        } else {
            // Keep alert for errors
            const messageHtml = `
                <div class="alert alert-subtle-danger alert-dismissible fade show py-2 fs-9 mb-3" role="alert">
                    <i class="fas fa-exclamation-circle me-2 fs-10"></i>
                    <span class="fw-semibold fs-9">${message}</span>
                    <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;
            forgotPasswordMessages.html(messageHtml);
        }
        
        // Auto-hide success messages after 5 seconds
        if (type === 'success') {
            setTimeout(function() {
                forgotPasswordMessages.fadeOut(300);
            }, 5000);
        }
    }

    function setForgotPasswordLoadingState(loading) {
        if (loading) {
            forgotPasswordSubmit.prop('disabled', true);
            resetText.addClass('d-none');
            resetSpinner.removeClass('d-none');
            $('#trade-journal-user-email').prop('disabled', true);
        } else {
            forgotPasswordSubmit.prop('disabled', false);
            resetText.removeClass('d-none');
            resetSpinner.addClass('d-none');
            $('#trade-journal-user-email').prop('disabled', false);
        }
    }

    // Registration Form Handler
    const registerForm = $('#trade-journal-register-form');
    const registerSubmit = $('#trade-journal-register-submit');
    const registerMessages = $('#trade-journal-register-messages');
    const registerText = registerSubmit.find('.register-text');
    const registerSpinner = registerSubmit.find('.register-spinner');

    if (registerForm.length) {
        registerForm.on('submit', function(e) {
            e.preventDefault();
            
            // Clear previous messages
            registerMessages.empty();
            
            // Set loading state
            setRegisterLoadingState(true);
            
            const formData = {
                action: 'trade_journal_register',
                nonce: tradeJournalLogin.registerNonce,
                name: $('#trade-journal-name').val().trim(),
                email: $('#trade-journal-email').val().trim(),
                password: $('#trade-journal-register-password').val(),
                confirm_password: $('#trade-journal-confirm-password').val(),
                terms_accepted: $('#trade-journal-terms').is(':checked') ? '1' : '0',
                redirect_to: $('input[name="redirect_to"]').val()
            };

            // Basic client-side validation
            if (!formData.name || !formData.email || !formData.password || !formData.confirm_password) {
                showRegisterMessage('Please fill in all required fields.', 'error');
                setRegisterLoadingState(false);
                return;
            }

            if (formData.password !== formData.confirm_password) {
                showRegisterMessage('Passwords do not match.', 'error');
                setRegisterLoadingState(false);
                return;
            }

            if (formData.password.length < 6) {
                showRegisterMessage('Password must be at least 6 characters long.', 'error');
                setRegisterLoadingState(false);
                return;
            }

            if (formData.terms_accepted !== '1') {
                showRegisterMessage('You must accept the terms and privacy policy.', 'error');
                setRegisterLoadingState(false);
                return;
            }

            // AJAX register request
            $.ajax({
                url: tradeJournalLogin.ajaxUrl,
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        showRegisterMessage(response.data.message, 'success');
                        
                        // Redirect after short delay
                        setTimeout(function() {
                            if (response.data.redirect_to) {
                                window.location.href = response.data.redirect_to;
                            } else {
                                location.reload();
                            }
                        }, 1500);
                    } else {
                        showRegisterMessage(response.data.message, 'error');
                        setRegisterLoadingState(false);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Register AJAX Error:', error);
                    showRegisterMessage('An error occurred during registration. Please try again.', 'error');
                    setRegisterLoadingState(false);
                }
            });
        });

        // Focus on first input field
        $('#trade-journal-name').focus();

        // Real-time password matching validation
        $('#trade-journal-register-password, #trade-journal-confirm-password').on('input', function() {
            const password = $('#trade-journal-register-password').val();
            const confirmPassword = $('#trade-journal-confirm-password').val();
            const confirmField = $('#trade-journal-confirm-password');
            
            if (confirmPassword && password !== confirmPassword) {
                confirmField.addClass('is-invalid');
            } else {
                confirmField.removeClass('is-invalid');
            }
        });
    }

    // Registration Helper Functions
    function showRegisterMessage(message, type) {
        if (type === 'success') {
            // Ultra-minimal inline success message
            const messageHtml = `
                <div class="text-center mb-3">
                    <span class="d-inline-flex align-items-center text-success fs-9 py-1">
                        <i class="fas fa-check-circle me-1 fs-10"></i>
                        ${message}
                    </span>
                </div>
            `;
            registerMessages.html(messageHtml);
        } else {
            // Keep alert for errors
            const messageHtml = `
                <div class="alert alert-subtle-danger alert-dismissible fade show py-2 fs-9 mb-3" role="alert">
                    <i class="fas fa-exclamation-circle me-2 fs-10"></i>
                    <span class="fw-semibold fs-9">${message}</span>
                    <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;
            registerMessages.html(messageHtml);
        }
        
        // Auto-hide success messages after 5 seconds
        if (type === 'success') {
            setTimeout(function() {
                registerMessages.fadeOut(300);
            }, 5000);
        }
    }

    function setRegisterLoadingState(loading) {
        if (loading) {
            registerSubmit.prop('disabled', true);
            registerText.addClass('d-none');
            registerSpinner.removeClass('d-none');
            registerForm.find('input').prop('disabled', true);
        } else {
            registerSubmit.prop('disabled', false);
            registerText.removeClass('d-none');
            registerSpinner.addClass('d-none');
            registerForm.find('input').prop('disabled', false);
        }
    }
});
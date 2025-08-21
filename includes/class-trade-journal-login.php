<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Trade_Journal_Login {

    private $plugin_url;
    private $plugin_version;

    public function __construct( $plugin_url, $plugin_version ) {
        $this->plugin_url = $plugin_url;
        $this->plugin_version = $plugin_version;

        add_action( 'init', array( $this, 'init' ) );
        add_action( 'wp_ajax_nopriv_trade_journal_login', array( $this, 'handle_ajax_login' ) );
        add_action( 'wp_ajax_trade_journal_login', array( $this, 'handle_ajax_login' ) );
        add_action( 'wp_ajax_nopriv_trade_journal_forgot_password', array( $this, 'handle_ajax_forgot_password' ) );
        add_action( 'wp_ajax_trade_journal_forgot_password', array( $this, 'handle_ajax_forgot_password' ) );
        add_action( 'wp_ajax_nopriv_trade_journal_register', array( $this, 'handle_ajax_register' ) );
        add_action( 'wp_ajax_trade_journal_register', array( $this, 'handle_ajax_register' ) );
        
        // Redirect wp-login.php to custom login page
        add_action( 'login_init', array( $this, 'redirect_wp_login_to_custom' ) );
        
        // Check for protected pages
        add_action( 'template_redirect', array( $this, 'check_page_protection' ) );
        
        // Handle logout redirect
        add_action( 'wp_logout', array( $this, 'handle_logout_redirect' ) );
        add_filter( 'logout_redirect', array( $this, 'custom_logout_redirect' ), 10, 3 );
    }

    public function init() {
        add_shortcode( 'trade_journal_login', array( $this, 'render_login_shortcode' ) );
        add_shortcode( 'trade_journal_forgot_password', array( $this, 'render_forgot_password_shortcode' ) );
        add_shortcode( 'trade_journal_register', array( $this, 'render_register_shortcode' ) );
    }

    public function render_login_shortcode( $atts ) {
        if ( is_user_logged_in() ) {
            return '<div class="alert alert-subtle-info alert-dismissible fade show" role="alert"><i class="fas fa-info-circle me-2"></i>You are already logged in.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        }

        $atts = shortcode_atts( array(
            'redirect_to' => '',
            'class' => '',
            'show_title' => 'true'
        ), $atts );

        $this->enqueue_assets();

        ob_start();
        $this->render_login_form( $atts );
        return ob_get_clean();
    }

    private function render_login_form( $atts ) {
        // Check for default redirect page setting
        $default_redirect_page_id = get_option( 'trade_journal_wp_default_redirect_page', '' );
        $default_redirect_url = $default_redirect_page_id ? get_permalink( $default_redirect_page_id ) : home_url();
        
        $redirect_to = ! empty( $atts['redirect_to'] ) ? esc_url( $atts['redirect_to'] ) : $default_redirect_url;
        $show_title = $atts['show_title'] === 'true';
        $custom_class = ! empty( $atts['class'] ) ? esc_attr( $atts['class'] ) : '';
        
        // Get URLs for custom pages or fallback to default
        $forgot_password_url = $this->get_forgot_password_page_url() ?: wp_lostpassword_url();
        $register_url = $this->get_register_page_url() ?: wp_registration_url();

        include plugin_dir_path( __FILE__ ) . '../views/frontend/login-form.php';
    }

    public function render_forgot_password_shortcode( $atts ) {
        $atts = shortcode_atts( array(
            'redirect_to' => '',
            'class' => '',
            'show_title' => 'true'
        ), $atts );

        $this->enqueue_assets();

        ob_start();
        $this->render_forgot_password_form( $atts );
        return ob_get_clean();
    }

    private function render_forgot_password_form( $atts ) {
        $redirect_to = ! empty( $atts['redirect_to'] ) ? esc_url( $atts['redirect_to'] ) : home_url();
        $show_title = $atts['show_title'] === 'true';
        $custom_class = ! empty( $atts['class'] ) ? esc_attr( $atts['class'] ) : '';
        $login_url = wp_login_url();

        include plugin_dir_path( __FILE__ ) . '../views/frontend/forgot-password-form.php';
    }

    public function render_register_shortcode( $atts ) {
        if ( is_user_logged_in() ) {
            return '<div class="alert alert-subtle-info alert-dismissible fade show" role="alert"><i class="fas fa-info-circle me-2"></i>You are already logged in.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        }

        // Check if user registration is enabled
        if ( ! get_option( 'users_can_register' ) ) {
            return '<div class="alert alert-subtle-warning alert-dismissible fade show" role="alert"><i class="fas fa-exclamation-triangle me-2"></i>User registration is currently disabled.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        }

        $atts = shortcode_atts( array(
            'redirect_to' => '',
            'class' => '',
            'show_title' => 'true'
        ), $atts );

        $this->enqueue_assets();

        ob_start();
        $this->render_register_form( $atts );
        return ob_get_clean();
    }

    private function render_register_form( $atts ) {
        // Check for default redirect page setting
        $default_redirect_page_id = get_option( 'trade_journal_wp_default_redirect_page', '' );
        $default_redirect_url = $default_redirect_page_id ? get_permalink( $default_redirect_page_id ) : home_url();
        
        $redirect_to = ! empty( $atts['redirect_to'] ) ? esc_url( $atts['redirect_to'] ) : $default_redirect_url;
        $show_title = $atts['show_title'] === 'true';
        $custom_class = ! empty( $atts['class'] ) ? esc_attr( $atts['class'] ) : '';
        $login_url = wp_login_url();

        include plugin_dir_path( __FILE__ ) . '../views/frontend/register-form.php';
    }

    public function handle_ajax_login() {
        if ( ! wp_verify_nonce( $_POST['nonce'], 'trade_journal_login_nonce' ) ) {
            wp_send_json_error( array( 'message' => 'Security check failed.' ) );
        }

        $username = sanitize_user( $_POST['username'] );
        $password = $_POST['password'];
        $remember = isset( $_POST['remember'] ) && $_POST['remember'] === '1';
        
        // Get default redirect page
        $default_redirect_page_id = get_option( 'trade_journal_wp_default_redirect_page', '' );
        $default_redirect_url = $default_redirect_page_id ? get_permalink( $default_redirect_page_id ) : home_url();
        
        $redirect_to = isset( $_POST['redirect_to'] ) && ! empty( $_POST['redirect_to'] ) 
            ? esc_url_raw( $_POST['redirect_to'] ) 
            : $default_redirect_url;

        if ( empty( $username ) || empty( $password ) ) {
            wp_send_json_error( array( 'message' => 'Please enter both username and password.' ) );
        }

        $creds = array(
            'user_login'    => $username,
            'user_password' => $password,
            'remember'      => $remember,
        );

        $user = wp_signon( $creds, false );

        if ( is_wp_error( $user ) ) {
            $error_message = $this->get_friendly_error_message( $user->get_error_code() );
            wp_send_json_error( array( 'message' => $error_message ) );
        }

        wp_send_json_success( array( 
            'message' => 'Login successful! Redirecting...',
            'redirect_to' => $redirect_to
        ) );
    }

    public function handle_ajax_forgot_password() {
        if ( ! wp_verify_nonce( $_POST['nonce'], 'trade_journal_forgot_password_nonce' ) ) {
            wp_send_json_error( array( 'message' => 'Security check failed.' ) );
        }

        $user_email = sanitize_email( $_POST['user_email'] );

        if ( empty( $user_email ) ) {
            wp_send_json_error( array( 'message' => 'Please enter your email address.' ) );
        }

        if ( ! is_email( $user_email ) ) {
            wp_send_json_error( array( 'message' => 'Please enter a valid email address.' ) );
        }

        // Check if user exists
        if ( ! email_exists( $user_email ) ) {
            wp_send_json_error( array( 'message' => 'No account found with this email address.' ) );
        }

        // Use WordPress core function to send reset email
        $result = retrieve_password( $user_email );

        if ( is_wp_error( $result ) ) {
            $error_message = $this->get_friendly_password_reset_error( $result->get_error_code() );
            wp_send_json_error( array( 'message' => $error_message ) );
        }

        wp_send_json_success( array( 
            'message' => 'Password reset link sent! Check your email.'
        ) );
    }

    public function handle_ajax_register() {
        if ( ! wp_verify_nonce( $_POST['nonce'], 'trade_journal_register_nonce' ) ) {
            wp_send_json_error( array( 'message' => 'Security check failed.' ) );
        }

        // Check if registration is enabled
        if ( ! get_option( 'users_can_register' ) ) {
            wp_send_json_error( array( 'message' => 'User registration is currently disabled.' ) );
        }

        $name = sanitize_text_field( $_POST['name'] );
        $email = sanitize_email( $_POST['email'] );
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        $terms_accepted = isset( $_POST['terms_accepted'] ) && $_POST['terms_accepted'] === '1';
        
        // Get default redirect page
        $default_redirect_page_id = get_option( 'trade_journal_wp_default_redirect_page', '' );
        $default_redirect_url = $default_redirect_page_id ? get_permalink( $default_redirect_page_id ) : home_url();
        
        $redirect_to = isset( $_POST['redirect_to'] ) && ! empty( $_POST['redirect_to'] )
            ? esc_url_raw( $_POST['redirect_to'] ) 
            : $default_redirect_url;

        // Validation
        if ( empty( $name ) || empty( $email ) || empty( $password ) || empty( $confirm_password ) ) {
            wp_send_json_error( array( 'message' => 'Please fill in all required fields.' ) );
        }

        if ( ! is_email( $email ) ) {
            wp_send_json_error( array( 'message' => 'Please enter a valid email address.' ) );
        }

        if ( email_exists( $email ) ) {
            wp_send_json_error( array( 'message' => 'An account with this email address already exists.' ) );
        }

        if ( $password !== $confirm_password ) {
            wp_send_json_error( array( 'message' => 'Passwords do not match.' ) );
        }

        if ( strlen( $password ) < 6 ) {
            wp_send_json_error( array( 'message' => 'Password must be at least 6 characters long.' ) );
        }

        if ( ! $terms_accepted ) {
            wp_send_json_error( array( 'message' => 'You must accept the terms and privacy policy.' ) );
        }

        // Create username from email (can be customized)
        $username = sanitize_user( $email );
        if ( username_exists( $username ) ) {
            // If email as username exists, create unique username
            $username = sanitize_user( explode( '@', $email )[0] );
            $counter = 1;
            $original_username = $username;
            while ( username_exists( $username ) ) {
                $username = $original_username . $counter;
                $counter++;
            }
        }

        // Create user
        $user_id = wp_create_user( $username, $password, $email );

        if ( is_wp_error( $user_id ) ) {
            wp_send_json_error( array( 'message' => $user_id->get_error_message() ) );
        }

        // Update user meta with display name
        wp_update_user( array(
            'ID' => $user_id,
            'display_name' => $name,
            'first_name' => $name
        ) );

        // Send new user notification
        wp_new_user_notification( $user_id, null, 'user' );

        // Auto-login the user
        wp_set_current_user( $user_id );
        wp_set_auth_cookie( $user_id );

        wp_send_json_success( array( 
            'message' => 'Account created successfully! Redirecting...',
            'redirect_to' => $redirect_to
        ) );
    }

    private function get_friendly_error_message( $error_code ) {
        switch ( $error_code ) {
            case 'invalid_username':
                return 'Invalid username. Please check your username and try again.';
            case 'incorrect_password':
                return 'Incorrect password. Please check your password and try again.';
            case 'empty_username':
                return 'Please enter your username.';
            case 'empty_password':
                return 'Please enter your password.';
            case 'invalid_email':
                return 'Invalid email address.';
            default:
                return 'Login failed. Please check your credentials and try again.';
        }
    }

    private function get_friendly_password_reset_error( $error_code ) {
        switch ( $error_code ) {
            case 'invalidcombo':
                return 'No account found with this email address.';
            case 'invalid_email':
                return 'Please enter a valid email address.';
            case 'no_password_reset':
                return 'Password reset is disabled for this user.';
            default:
                return 'Unable to send reset email. Please try again later.';
        }
    }

    private function enqueue_assets() {
        wp_enqueue_style(
            'trade-journal-login',
            $this->plugin_url . 'assets/css/login.css',
            array(),
            $this->plugin_version
        );

        wp_enqueue_script(
            'trade-journal-login',
            $this->plugin_url . 'assets/js/login.js',
            array( 'jquery' ),
            $this->plugin_version,
            true
        );

        wp_localize_script( 'trade-journal-login', 'tradeJournalLogin', array(
            'ajaxUrl' => admin_url( 'admin-ajax.php' ),
            'nonce' => wp_create_nonce( 'trade_journal_login_nonce' ),
            'forgotPasswordNonce' => wp_create_nonce( 'trade_journal_forgot_password_nonce' ),
            'registerNonce' => wp_create_nonce( 'trade_journal_register_nonce' ),
        ) );
    }

    public function redirect_wp_login_to_custom() {
        // Get the current login page slug/URL where your shortcode is placed
        $custom_login_page = $this->get_custom_login_page_url();
        
        if ( ! $custom_login_page ) {
            return; // No custom login page found, use default
        }
        
        // Check if we're on wp-login.php for login, lostpassword, or register actions
        $action = isset( $_REQUEST['action'] ) ? $_REQUEST['action'] : 'login';
        
        if ( $action === 'login' || $action === 'lostpassword' || $action === 'register' ) {
            // Preserve query parameters like loggedout, wp_lang, redirect_to
            $query_params = array();
            
            if ( isset( $_GET['loggedout'] ) ) {
                $query_params['loggedout'] = sanitize_text_field( $_GET['loggedout'] );
            }
            
            if ( isset( $_GET['wp_lang'] ) ) {
                $query_params['wp_lang'] = sanitize_text_field( $_GET['wp_lang'] );
            }
            
            if ( isset( $_GET['redirect_to'] ) ) {
                $query_params['redirect_to'] = urlencode( $_GET['redirect_to'] );
            }
            
            // Build redirect URL based on action
            if ( $action === 'lostpassword' ) {
                $forgot_password_page = $this->get_forgot_password_page_url();
                $redirect_url = $forgot_password_page ? $forgot_password_page : $custom_login_page;
                $query_params['action'] = 'lostpassword'; // Add action to query params
            } elseif ( $action === 'register' ) {
                $register_page = $this->get_register_page_url();
                $redirect_url = $register_page ? $register_page : $custom_login_page;
                $query_params['action'] = 'register'; // Add action to query params
            } else {
                $redirect_url = $custom_login_page;
            }
            
            if ( ! empty( $query_params ) ) {
                $redirect_url = add_query_arg( $query_params, $redirect_url );
            }
            
            wp_redirect( $redirect_url );
            exit;
        }
    }
    
    private function get_custom_login_page_url() {
        // Try to find a page with the trade_journal_login shortcode
        $pages = get_posts( array(
            'post_type' => 'page',
            'post_status' => 'publish',
            'meta_query' => array(),
            'posts_per_page' => -1,
        ) );
        
        foreach ( $pages as $page ) {
            if ( has_shortcode( $page->post_content, 'trade_journal_login' ) ) {
                return get_permalink( $page->ID );
            }
        }
        
        // Fallback: check if there's an option set for custom login page
        $login_page_id = get_option( 'trade_journal_wp_login_page' );
        if ( $login_page_id ) {
            return get_permalink( $login_page_id );
        }
        
        return false;
    }
    
    private function get_forgot_password_page_url() {
        // Try to find a page with the trade_journal_forgot_password shortcode
        $pages = get_posts( array(
            'post_type' => 'page',
            'post_status' => 'publish',
            'meta_query' => array(),
            'posts_per_page' => -1,
        ) );
        
        foreach ( $pages as $page ) {
            if ( has_shortcode( $page->post_content, 'trade_journal_forgot_password' ) ) {
                return get_permalink( $page->ID );
            }
        }
        
        // Fallback: check if there's an option set for forgot password page
        $forgot_password_page_id = get_option( 'trade_journal_wp_forgot_password_page' );
        if ( $forgot_password_page_id ) {
            return get_permalink( $forgot_password_page_id );
        }
        
        return false;
    }
    
    private function get_register_page_url() {
        // Try to find a page with the trade_journal_register shortcode
        $pages = get_posts( array(
            'post_type' => 'page',
            'post_status' => 'publish',
            'meta_query' => array(),
            'posts_per_page' => -1,
        ) );
        
        foreach ( $pages as $page ) {
            if ( has_shortcode( $page->post_content, 'trade_journal_register' ) ) {
                return get_permalink( $page->ID );
            }
        }
        
        // Fallback: check if there's an option set for register page
        $register_page_id = get_option( 'trade_journal_wp_register_page' );
        if ( $register_page_id ) {
            return get_permalink( $register_page_id );
        }
        
        return false;
    }

    /**
     * Check if current page is protected and redirect if needed
     */
    public function check_page_protection() {
        // Only check for pages, not posts or other content types
        if ( ! is_page() ) {
            return;
        }
        
        // Don't redirect if user is already logged in
        if ( is_user_logged_in() ) {
            return;
        }
        
        // Don't redirect administrators (they can always access pages)
        if ( current_user_can( 'administrator' ) ) {
            return;
        }
        
        $current_page_id = get_the_ID();
        $protected_pages = get_option( 'trade_journal_wp_protected_pages', array() );
        
        // Ensure we have an array
        if ( ! is_array( $protected_pages ) ) {
            return;
        }
        
        // Check if current page is in the protected pages list
        if ( in_array( $current_page_id, $protected_pages ) ) {
            // Get custom login page URL
            $login_page_url = $this->get_custom_login_page_url();
            
            // Fallback to default WordPress login if no custom page
            if ( ! $login_page_url ) {
                $login_page_url = wp_login_url();
            }
            
            // Add current page as redirect_to parameter so user returns here after login
            $current_url = get_permalink( $current_page_id );
            $redirect_url = add_query_arg( 'redirect_to', urlencode( $current_url ), $login_page_url );
            
            // Redirect to login page
            wp_redirect( $redirect_url );
            exit;
        }
    }

    /**
     * Handle logout redirect based on admin settings
     */
    public function handle_logout_redirect() {
        // Store logout redirect preference in a transient for 10 seconds
        // This is needed because wp_logout happens before logout_redirect filter
        $logout_redirect_page_id = get_option( 'trade_journal_wp_logout_redirect_page', '' );
        
        if ( $logout_redirect_page_id ) {
            set_transient( 'trade_journal_logout_redirect_' . get_current_user_id(), $logout_redirect_page_id, 10 );
        }
    }

    /**
     * Filter the logout redirect URL
     */
    public function custom_logout_redirect( $redirect_to, $requested_redirect_to, $user ) {
        // If a specific redirect was requested, honor it
        if ( ! empty( $requested_redirect_to ) && $requested_redirect_to !== $redirect_to ) {
            return $requested_redirect_to;
        }
        
        // Get the logout redirect setting directly (more reliable than transients)
        $logout_redirect_page_id = get_option( 'trade_journal_wp_logout_redirect_page', '' );
        
        if ( ! empty( $logout_redirect_page_id ) ) {
            if ( $logout_redirect_page_id === 'home' ) {
                return add_query_arg( 'loggedout', 'true', home_url() );
            } elseif ( is_numeric( $logout_redirect_page_id ) ) {
                $redirect_url = get_permalink( $logout_redirect_page_id );
                if ( $redirect_url ) {
                    return add_query_arg( 'loggedout', 'true', $redirect_url );
                }
            }
        }
        
        // Default to login page if it exists
        $login_page_url = $this->get_custom_login_page_url();
        if ( $login_page_url ) {
            return add_query_arg( 'loggedout', 'true', $login_page_url );
        }
        
        // Fallback to WordPress default
        return $redirect_to;
    }

    /**
     * Get logout URL with proper redirect
     */
    public static function get_logout_url( $redirect_to = '' ) {
        if ( empty( $redirect_to ) ) {
            $logout_redirect_page_id = get_option( 'trade_journal_wp_logout_redirect_page', '' );
            
            if ( $logout_redirect_page_id === 'home' ) {
                $redirect_to = home_url();
            } elseif ( is_numeric( $logout_redirect_page_id ) ) {
                $redirect_to = get_permalink( $logout_redirect_page_id );
            } else {
                // Try to get custom login page
                $login_instance = new self( '', '' );
                $redirect_to = $login_instance->get_custom_login_page_url() ?: wp_login_url();
            }
            
            $redirect_to = add_query_arg( 'loggedout', 'true', $redirect_to );
        }
        
        return wp_logout_url( $redirect_to );
    }

    public static function get_asset_version( $file_path ) {
        $full_path = plugin_dir_path( __DIR__ ) . $file_path;
        return file_exists( $full_path ) ? filemtime( $full_path ) : time();
    }
}
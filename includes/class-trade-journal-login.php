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
    }

    public function init() {
        add_shortcode( 'trade_journal_login', array( $this, 'render_login_shortcode' ) );
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
        $redirect_to = ! empty( $atts['redirect_to'] ) ? esc_url( $atts['redirect_to'] ) : home_url();
        $show_title = $atts['show_title'] === 'true';
        $custom_class = ! empty( $atts['class'] ) ? esc_attr( $atts['class'] ) : '';

        include plugin_dir_path( __FILE__ ) . '../views/frontend/login-form.php';
    }

    public function handle_ajax_login() {
        if ( ! wp_verify_nonce( $_POST['nonce'], 'trade_journal_login_nonce' ) ) {
            wp_send_json_error( array( 'message' => 'Security check failed.' ) );
        }

        $username = sanitize_user( $_POST['username'] );
        $password = $_POST['password'];
        $remember = isset( $_POST['remember'] ) && $_POST['remember'] === '1';
        $redirect_to = isset( $_POST['redirect_to'] ) ? esc_url_raw( $_POST['redirect_to'] ) : home_url();

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
        ) );
    }

    public static function get_asset_version( $file_path ) {
        $full_path = plugin_dir_path( __DIR__ ) . $file_path;
        return file_exists( $full_path ) ? filemtime( $full_path ) : time();
    }
}
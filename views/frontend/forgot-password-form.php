<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$site_name = get_bloginfo( 'name' );
$site_logo = get_custom_logo();
?>

<div class="trade-journal-login-container <?php echo esc_attr( $custom_class ); ?>">
    <div class="container-fluid">
        <div class="row flex-center position-relative min-vh-100 g-0 py-5">
            <div class="col-11 col-sm-10 col-xl-8">
                <div class="card border border-translucent auth-card">
                    <div class="card-body pe-md-0">
                        <div class="row align-items-center gx-0 gy-7">
                            
                            <!-- Left Side Panel -->
                            <div class="col-auto bg-body-highlight rounded-3 position-relative overflow-hidden auth-title-box">
                                <div class="bg-holder" style="background-image:url(<?php echo esc_url( plugin_dir_url( dirname( dirname( __FILE__ ) ) ) . 'assets/img/bg/38.png' ); ?>);"></div>
                                <div class="position-relative px-4 px-lg-7 pt-7 pb-7 pb-sm-5 text-center text-md-start pb-lg-7 pb-md-7">
                                    <?php if ( $show_title ) : ?>
                                        <h3 class="mb-3 text-body-emphasis fs-7"><?php echo esc_html( $site_name ); ?> Authentication</h3>
                                        <p class="text-body-tertiary">Reset your password and regain access to your trading journal!</p>
                                    <?php endif; ?>
                                    <ul class="list-unstyled mb-0 w-max-content w-md-auto">
                                        <li class="d-flex align-items-center">
                                            <span class="uil uil-check-circle text-success me-2"></span>
                                            <span class="text-body-tertiary fw-semibold">Secure</span>
                                        </li>
                                        <li class="d-flex align-items-center">
                                            <span class="uil uil-check-circle text-success me-2"></span>
                                            <span class="text-body-tertiary fw-semibold">Fast</span>
                                        </li>
                                        <li class="d-flex align-items-center">
                                            <span class="uil uil-check-circle text-success me-2"></span>
                                            <span class="text-body-tertiary fw-semibold">Reliable</span>
                                        </li>
                                    </ul>
                                </div>
                                <div class="position-relative z-n1 mb-6 d-none d-md-block text-center mt-md-15">
                                    <img class="auth-title-box-img d-dark-none" src="<?php echo esc_url( plugin_dir_url( dirname( dirname( __FILE__ ) ) ) . 'assets/img/spot-illustrations/auth.png' ); ?>" alt="Authentication" />
                                    <img class="auth-title-box-img d-light-none" src="<?php echo esc_url( plugin_dir_url( dirname( dirname( __FILE__ ) ) ) . 'assets/img/spot-illustrations/auth-dark.png' ); ?>" alt="Authentication" />
                                </div>
                            </div>

                            <!-- Right Side Forgot Password Form -->
                            <div class="col mx-auto">
                                <div class="auth-form-box">
                                    <div class="text-center">
                                        <?php if ( $site_logo ) : ?>
                                            <a class="d-flex flex-center text-decoration-none mb-4" href="<?php echo esc_url( home_url() ); ?>">
                                                <?php echo $site_logo; ?>
                                            </a>
                                        <?php else : ?>
                                            <a class="d-flex flex-center text-decoration-none mb-4" href="<?php echo esc_url( home_url() ); ?>">
                                                <div class="d-flex align-items-center fw-bolder fs-6 d-inline-block">
                                                    <i class="fas fa-chart-line text-primary me-2"></i>
                                                    <?php echo esc_html( $site_name ); ?>
                                                </div>
                                            </a>
                                        <?php endif; ?>
                                        
                                        <h4 class="text-body-highlight">Forgot your password?</h4>
                                        <p class="text-body-tertiary mb-5">Enter your email below and we will send you a reset link</p>
                                    </div>

                                    <!-- Forgot Password Messages -->
                                    <div id="trade-journal-forgot-password-messages"></div>

                                    <!-- Forgot Password Form -->
                                    <form id="trade-journal-forgot-password-form" method="post" class="d-flex align-items-center mb-5">
                                        <?php wp_nonce_field( 'trade_journal_forgot_password_nonce', 'trade_journal_forgot_password_nonce' ); ?>
                                        <input type="hidden" name="action" value="trade_journal_forgot_password">

                                        <div class="form-icon-container flex-1">
                                            <input 
                                                class="form-control form-icon-input" 
                                                id="trade-journal-user-email" 
                                                name="user_email"
                                                type="email" 
                                                placeholder="Enter your email address" 
                                                required 
                                            />
                                            <span class="fas fa-envelope text-body fs-9 form-icon"></span>
                                        </div>

                                        <button class="btn btn-primary ms-2" type="submit" id="trade-journal-forgot-password-submit">
                                            <span class="reset-text">Send</span>
                                            <span class="reset-spinner d-none">
                                                <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                                                Sending...
                                            </span>
                                            <span class="fas fa-chevron-right ms-2"></span>
                                        </button>
                                    </form>

                                    <!-- Back to Login Link -->
                                    <div class="text-center">
                                        <a class="btn btn-link fs-9 fw-bold p-0" href="<?php echo esc_url( $login_url ); ?>">
                                            <i class="fas fa-arrow-left me-1"></i>
                                            Back to Sign In
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
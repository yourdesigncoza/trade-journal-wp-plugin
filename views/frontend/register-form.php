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
                                        <p class="text-body-tertiary">Join our trading community and start tracking your success!</p>
                                    <?php endif; ?>
                                    <ul class="list-unstyled mb-0 w-max-content w-md-auto">
                                        <li class="d-flex align-items-center">
                                            <span class="uil uil-check-circle text-success me-2"></span>
                                            <span class="text-body-tertiary fw-semibold">Fast</span>
                                        </li>
                                        <li class="d-flex align-items-center">
                                            <span class="uil uil-check-circle text-success me-2"></span>
                                            <span class="text-body-tertiary fw-semibold">Simple</span>
                                        </li>
                                        <li class="d-flex align-items-center">
                                            <span class="uil uil-check-circle text-success me-2"></span>
                                            <span class="text-body-tertiary fw-semibold">Secure</span>
                                        </li>
                                    </ul>
                                </div>
                                <div class="position-relative z-n1 mb-6 d-none d-md-block text-center mt-md-15">
                                    <img class="auth-title-box-img d-dark-none" src="<?php echo esc_url( plugin_dir_url( dirname( dirname( __FILE__ ) ) ) . 'assets/img/spot-illustrations/auth.png' ); ?>" alt="Authentication" />
                                    <img class="auth-title-box-img d-light-none" src="<?php echo esc_url( plugin_dir_url( dirname( dirname( __FILE__ ) ) ) . 'assets/img/spot-illustrations/auth-dark.png' ); ?>" alt="Authentication" />
                                </div>
                            </div>

                            <!-- Right Side Registration Form -->
                            <div class="col mx-auto">
                                <div class="auth-form-box">
                                    <div class="text-center mb-7">
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
                                        
                                        <h3 class="text-body-highlight">Sign Up</h3>
                                        <p class="text-body-tertiary">Create your account today</p>
                                    </div>

                                    <!-- Registration Messages -->
                                    <div id="trade-journal-register-messages"></div>

                                    <!-- Registration Form -->
                                    <form id="trade-journal-register-form" method="post">
                                        <?php wp_nonce_field( 'trade_journal_register_nonce', 'trade_journal_register_nonce' ); ?>
                                        <input type="hidden" name="action" value="trade_journal_register">
                                        <input type="hidden" name="redirect_to" value="<?php echo esc_attr( $redirect_to ); ?>">

                                        <!-- Name Field -->
                                        <div class="mb-3 text-start">
                                            <label class="form-label" for="trade-journal-name">Name</label>
                                            <input class="form-control" id="trade-journal-name" name="name" type="text" placeholder="Enter your full name" required />
                                        </div>

                                        <!-- Email Field -->
                                        <div class="mb-3 text-start">
                                            <label class="form-label" for="trade-journal-email">Email address</label>
                                            <input class="form-control" id="trade-journal-email" name="email" type="email" placeholder="name@example.com" required />
                                        </div>

                                        <!-- Password Fields -->
                                        <div class="row g-3 mb-3">
                                            <div class="col-sm-6">
                                                <label class="form-label" for="trade-journal-register-password">Password</label>
                                                <div class="position-relative" data-password="data-password">
                                                    <input class="form-control form-icon-input pe-6" id="trade-journal-register-password" name="password" type="password" placeholder="Password" data-password-input="data-password-input" required />
                                                    <button class="btn px-3 py-0 h-100 position-absolute top-0 end-0 fs-7 text-body-tertiary" type="button" data-password-toggle="data-password-toggle">
                                                        <span class="uil uil-eye show"></span>
                                                        <span class="uil uil-eye-slash hide"></span>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <label class="form-label" for="trade-journal-confirm-password">Confirm Password</label>
                                                <div class="position-relative" data-password="data-password">
                                                    <input class="form-control form-icon-input pe-6" id="trade-journal-confirm-password" name="confirm_password" type="password" placeholder="Confirm" data-password-input="data-password-input" required />
                                                    <button class="btn px-3 py-0 h-100 position-absolute top-0 end-0 fs-7 text-body-tertiary" type="button" data-password-toggle="data-password-toggle">
                                                        <span class="uil uil-eye show"></span>
                                                        <span class="uil uil-eye-slash hide"></span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Terms Checkbox -->
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" id="trade-journal-terms" name="terms_accepted" type="checkbox" value="1" required />
                                            <label class="form-label fs-9 text-transform-none" for="trade-journal-terms">
                                                I accept the <a href="<?php echo esc_url( get_privacy_policy_url() ?: '#' ); ?>" target="_blank">terms and privacy policy</a>
                                            </label>
                                        </div>

                                        <!-- Submit Button -->
                                        <button class="btn btn-primary w-100 mb-3" type="submit" id="trade-journal-register-submit">
                                            <span class="register-text">Sign Up</span>
                                            <span class="register-spinner d-none">
                                                <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                                Creating Account...
                                            </span>
                                        </button>

                                        <!-- Login Link -->
                                        <div class="text-center">
                                            <a class="fs-9 fw-bold" href="<?php echo esc_url( $login_url ); ?>">Sign in to an existing account</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
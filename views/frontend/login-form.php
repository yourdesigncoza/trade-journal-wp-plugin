<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$site_name = get_bloginfo( 'name' );
$site_logo = get_custom_logo();
$forgot_password_url = wp_lostpassword_url();
?>

<div class="trade-journal-login-container <?php echo esc_attr( $custom_class ); ?>">
    <div class="container-fluid">
        <div class="bg-holder bg-auth-card-overlay" style="background-image:url(<?php echo esc_url( plugin_dir_url( dirname( dirname( __FILE__ ) ) ) . 'assets/img/bg/37.png' ); ?>);"></div>
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
                                        <p class="text-body-tertiary">Access your trading journal and manage your trading performance!</p>
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
                                            <span class="text-body-tertiary fw-semibold">Responsive</span>
                                        </li>
                                    </ul>
                                </div>
                                <div class="position-relative z-n1 mb-6 d-none d-md-block text-center mt-md-15">
                                    <img class="auth-title-box-img d-dark-none" src="<?php echo esc_url( plugin_dir_url( dirname( dirname( __FILE__ ) ) ) . 'assets/img/spot-illustrations/auth.png' ); ?>" alt="Authentication" />
                                    <img class="auth-title-box-img d-light-none" src="<?php echo esc_url( plugin_dir_url( dirname( dirname( __FILE__ ) ) ) . 'assets/img/spot-illustrations/auth-dark.png' ); ?>" alt="Authentication" />
                                </div>
                            </div>

                            <!-- Right Side Login Form -->
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
                                        
                                        <h3 class="text-body-highlight">Sign In</h3>
                                        <p class="text-body-tertiary">Get access to your account</p>
                                    </div>

                                    <!-- Login Messages -->
                                    <div id="trade-journal-login-messages"></div>

                                    <!-- Login Form -->
                                    <form id="trade-journal-login-form" method="post">
                                        <?php wp_nonce_field( 'trade_journal_login_nonce', 'trade_journal_login_nonce' ); ?>
                                        <input type="hidden" name="action" value="trade_journal_login">
                                        <input type="hidden" name="redirect_to" value="<?php echo esc_attr( $redirect_to ); ?>">

                                        <!-- Username Field -->
                                        <div class="mb-3 text-start">
                                            <label class="form-label" for="trade-journal-username">Username or Email</label>
                                            <div class="form-icon-container">
                                                <input 
                                                    class="form-control form-icon-input" 
                                                    id="trade-journal-username" 
                                                    name="username"
                                                    type="text" 
                                                    placeholder="Enter your username or email" 
                                                    required 
                                                />
                                                <span class="fas fa-user text-body fs-9 form-icon"></span>
                                            </div>
                                        </div>

                                        <!-- Password Field -->
                                        <div class="mb-3 text-start">
                                            <label class="form-label" for="trade-journal-password">Password</label>
                                            <div class="form-icon-container" data-password="data-password">
                                                <input 
                                                    class="form-control form-icon-input pe-6" 
                                                    id="trade-journal-password" 
                                                    name="password"
                                                    type="password" 
                                                    placeholder="Enter your password" 
                                                    required
                                                    data-password-input="data-password-input" 
                                                />
                                                <span class="fas fa-key text-body fs-9 form-icon"></span>
                                                <button 
                                                    class="btn btn-subtle-secondary px-3 py-0 h-100 position-absolute top-0 end-0 fs-7" 
                                                    type="button"
                                                    data-password-toggle="data-password-toggle"
                                                >
                                                    <span class="uil uil-eye show"></span>
                                                    <span class="uil uil-eye-slash hide"></span>
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Remember Me & Forgot Password -->
                                        <div class="row flex-between-center mb-7">
                                            <div class="col-auto">
                                                <div class="form-check mb-0">
                                                    <input class="form-check-input" id="trade-journal-remember" name="remember" type="checkbox" value="1" />
                                                    <label class="form-check-label mb-0" for="trade-journal-remember">Remember me</label>
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <a class="btn btn-link fs-9 fw-semibold p-0" href="<?php echo esc_url( $forgot_password_url ); ?>">Forgot Password?</a>
                                            </div>
                                        </div>

                                        <!-- Submit Button -->
                                        <button class="btn btn-primary w-100 mb-3" type="submit" id="trade-journal-login-submit">
                                            <span class="login-text">Sign In</span>
                                            <span class="login-spinner d-none">
                                                <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                                Signing In...
                                            </span>
                                        </button>

                                        <!-- Register Link -->
                                        <?php if ( get_option( 'users_can_register' ) ) : ?>
                                            <div class="text-center">
                                                <a class="btn btn-link fs-9 fw-bold p-0" href="<?php echo esc_url( wp_registration_url() ); ?>">Create an account</a>
                                            </div>
                                        <?php endif; ?>
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
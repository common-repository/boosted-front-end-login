<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<div <?php echo wp_kses_data( get_block_wrapper_attributes() ); ?>>
    <?php if (is_user_logged_in()): ?>
        <p class="boosted-front-end-already-logged-in">
            <?php 
            $current_user = wp_get_current_user();
            $username = $current_user->user_login;
            $logout_url = wp_logout_url( home_url() );
            printf(
                wp_kses_post(
                    /* translators: 1: username, 2: username, 3: logout URL */
                    __('Hello <span>%1$s</span> (not <span>%2$s</span>? <a href="%3$s">Log out</a>)', 'boosted-front-end-login')
                ),
                esc_html( $username ),
                esc_html( $username ),
                esc_url( $logout_url )
            );
            ?>
        </p>
    <?php else: ?>
        <?php
        $form_id = $attributes['uniqueId'];
        $transient_id = 'boosted_frontend_login_login_error_' . $form_id;
        $login_error = get_transient($transient_id);

        if ($login_error ) :
        $allowed_html = array(
            'a' => array(
                'href' => array(),
                'title' => array()
            ),
            'br' => array(),
            'em' => array(),
            'strong' => array(),
        );
        ?>
            <div class="boosted-front-end-login-error" role="alert">
                <?php echo wp_kses(apply_filters('boosted_frontend_login_error_message', $login_error), $allowed_html); ?>
                <?php delete_transient('boosted_frontend_login_login_error_' . $form_id); ?>
            </div>
        <?php endif; ?>
        <form class="boosted-front-end boosted-front-end-login" method="post" action="<?php echo esc_url(add_query_arg(array('form_id' => $form_id, 't' => time()), admin_url('admin-post.php'))); ?>" name="Login-Form">
            <input type="hidden" name="form_id" value="<?php echo esc_attr( $form_id ); ?>">
            <?php wp_nonce_field( 'front_end_login_action', 'front_end_login_nonce' ); ?>
            <input type="hidden" name="action" value="front_end_login">
            <input type="hidden" name="redirectURL" value="<?php echo esc_attr($attributes['redirectURL'] ?? 'referral_page'); ?>">
            <?php do_action('boosted_front_end_login_form_before_fields'); ?>
            <p class="boosted-front-end-username">
                <?php if ( ! empty( $attributes['usernameLabel'] ) ) : ?><label for="username"><?php echo wp_kses_post( $attributes['usernameLabel'] ); ?></label><?php endif; ?>
                <input class="boosted-front-end-username-field" type="text" id="username" name="username" required placeholder="<?php echo esc_attr( $attributes['usernamePlaceholder'] ); ?>">
            </p>
            <p class="boosted-front-end-password">
                <?php if ( ! empty( $attributes['passwordLabel'] ) ) : ?><label for="password"><?php echo wp_kses_post( $attributes['passwordLabel'] ); ?></label><?php endif; ?>
                <input class="boosted-front-end-password-field" type="password" id="password" name="password" required placeholder="<?php echo esc_attr( $attributes['passwordPlaceholder'] ); ?>">
            </p>
            <?php if ( ! empty( $attributes['rememberMeLabel'] ) ) : ?>
                <p class="boosted-front-end-remember">
                    <label class="boosted-front-end-remember-field">
                        <input name="remember" type="checkbox" id="rememberme" value="forever"> <?php echo wp_kses_post( $attributes['rememberMeLabel'] ); ?>
                    </label>
                </p>
            <?php endif; ?>
            <p class="boosted-front-end-submit">
                <input class="boosted-front-end-submit-btn" type="submit" value="<?php echo esc_attr( $attributes['loginButtonLabel'] ); ?>" aria-label="<?php esc_html_e( 'Log in to your account', 'boosted-front-end-login' ); ?>">
            </p>
            <nav class="boosted-front-end-navigation">
                <?php if ( get_option( 'users_can_register' ) && ! empty( $attributes['registerLabel'] ) ): ?>
                    <a class="boosted-front-end-navigation-register" href="<?php echo esc_url(!empty($attributes['registerDisplay']) && !empty($attributes['registerLink']) ? $attributes['registerLink'] : wp_registration_url()); ?>">
                        <?php echo wp_kses_post( $attributes['registerLabel'] ); ?></a> <span> | </span>
                <?php endif; ?>
                <?php if ( ! empty( $attributes['lostPasswordLabel'] ) ) : ?>
                    <a class="boosted-front-end-navigation-lost" href="<?php echo esc_url(!empty($attributes['lostPasswordDisplay']) && !empty($attributes['lostPasswordLink']) ? $attributes['lostPasswordLink'] : wp_lostpassword_url()); ?>"><?php echo wp_kses_post( $attributes['lostPasswordLabel'] ); ?></a>
                <?php endif; ?>
            </nav>
        </form>
    <?php endif; ?>
</div>

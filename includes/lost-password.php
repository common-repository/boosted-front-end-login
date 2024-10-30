<?php
/**
 *
 * @package Boosted
 */

namespace BoostedFrontEndLogin;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function front_end_lost_password() {
    if (isset($_POST['user_login']) && isset($_POST['form_id'])) {
        
        if (!isset($_POST['front_end_lost_password_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['front_end_lost_password_nonce'])), 'front_end_lost_password_action')) {
            wp_die( esc_html__( 'Nonce verification failed', 'boosted-front-end-login' ) );
        }

        $user_login = sanitize_user($_POST['user_login']);
        $form_id = sanitize_text_field($_POST['form_id']);
        $user = get_user_by('login', $user_login);
        if (!$user && strpos($user_login, '@')) {
            $user = get_user_by('email', $user_login);
        }

        if ($user) {
            $reset_key = get_password_reset_key($user);
            if (!is_wp_error($reset_key)) {
                $reset_url = network_site_url("wp-login.php?action=rp&key=$reset_key&login=" . rawurlencode($user->user_login), 'login');
                $message = __('Someone has requested a password reset for the following account:', 'boosted-front-end-login') . "\r\n\r\n";
                $message .= network_home_url('/') . "\r\n\r\n";
                // Translators: %s is the username.
                $message .= sprintf(__('Username: %s', 'boosted-front-end-login'), $user->user_login) . "\r\n\r\n";
                $message .= __('If this was a mistake, just ignore this email and nothing will happen.', 'boosted-front-end-login') . "\r\n\r\n";
                $message .= __('To reset your password, visit the following address:', 'boosted-front-end-login') . "\r\n\r\n";
                $message .= '<' . $reset_url . ">\r\n";

                if (wp_mail($user->user_email, __('Password Reset Request', 'boosted-front-end-login'), $message)) {
                    set_transient( 'boosted_frontend_login_lost_password_message_' . $form_id, __('Password reset email has been sent.', 'boosted-front-end-login'), 60 );
                    do_action('boosted_front_end_lost_password_email_sent', $user, $reset_url);
                } else {
                    set_transient( 'boosted_frontend_login_lost_password_error_' . $form_id, __('Failed to send password reset email.', 'boosted-front-end-login'), 60 );
                    do_action('boosted_front_end_lost_password_email_failed', $user);
                }
            } else {
                set_transient( 'boosted_frontend_login_lost_password_error_' . $form_id, $reset_key->get_error_message(), 60 );
            }
        } else {
            set_transient( 'boosted_frontend_login_lost_password_error_' . $form_id, __('Invalid username or email.', 'boosted-front-end-login'), 60 );
        }
    }
    wp_redirect( add_query_arg(array('form_id' => sanitize_text_field($_POST['form_id']), 't' => time()), esc_url_raw($_SERVER['HTTP_REFERER'])) );
    exit;
}
add_action('admin_post_nopriv_front_end_lost_password', __NAMESPACE__ . '\\front_end_lost_password');
add_action('admin_post_front_end_lost_password', __NAMESPACE__ . '\\front_end_lost_password');
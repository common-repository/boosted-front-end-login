<?php
/**
 *
 * @package Boosted
 */

namespace BoostedFrontEndLogin;

if (!defined('ABSPATH')) {
    exit;
}

function front_end_login() {
    if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['form_id'])) {

        if (!isset($_POST['front_end_login_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['front_end_login_nonce'])), 'front_end_login_action')) {
            wp_die(esc_html__('Nonce verification failed', 'boosted-front-end-login'));
        }

        $creds = array(
            'user_login' => sanitize_user($_POST['username']),
            'user_password' => sanitize_text_field($_POST['password']),
            'remember' => isset($_POST['remember']) && $_POST['remember'] === 'forever'
        );

        $user = wp_signon($creds, false);
        $form_id = sanitize_text_field($_POST['form_id']);
        $transient_id = 'boosted_frontend_login_login_error_' . $form_id;

        if (is_wp_error($user)) {
            set_transient($transient_id, $user->get_error_message(), 60);
            wp_redirect(add_query_arg(array('form_id' => $form_id, 't' => time()), esc_url_raw($_SERVER['HTTP_REFERER'])));
            exit;
        } else {
            wp_set_current_user($user->ID);
            wp_set_auth_cookie($user->ID, $creds['remember']);
            do_action('wp_login', $user->user_login, $user);
            do_action('boosted_front_end_login_success', $user);

            $redirect_url = !empty($_POST['redirectURL']) && $_POST['redirectURL'] !== 'referral_page' ? esc_url_raw($_POST['redirectURL']) : (wp_get_referer() ? wp_get_referer() : home_url());
            wp_redirect(esc_url_raw($redirect_url));
            exit;
        }
    } else {
        error_log('Missing username, password, or form_id in POST request.');
        wp_redirect(esc_url_raw($_SERVER['HTTP_REFERER']));
        exit;
    }
}
add_action('admin_post_nopriv_front_end_login', __NAMESPACE__ . '\\front_end_login');
add_action('admin_post_front_end_login', __NAMESPACE__ . '\\front_end_login');

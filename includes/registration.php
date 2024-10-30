<?php
/**
 *
 * @package Boosted
 */

namespace BoostedFrontEndLogin;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function front_end_register() {
    if (isset($_POST['user_login']) && isset($_POST['user_email']) && isset($_POST['user_pass']) && isset($_POST['user_pass_confirm']) && isset($_POST['form_id'])) {

        if (!isset($_POST['front_end_register_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['front_end_register_nonce'])), 'front_end_register_action')) {
            wp_die( esc_html__( 'Nonce verification failed', 'boosted-front-end-login' ) );
        }

        $user_login = sanitize_user( $_POST['user_login'] );
        $user_email = sanitize_email( $_POST['user_email'] );
        $user_pass = sanitize_text_field($_POST['user_pass']);
        $user_pass_confirm = sanitize_text_field($_POST['user_pass_confirm']);
        $form_id = sanitize_text_field($_POST['form_id']);

        if ( $user_pass !== $user_pass_confirm ) {
            set_transient( 'boosted_frontend_login_registration_error_' . $form_id, __('Passwords do not match.', 'boosted-front-end-login'), 60 );
            wp_redirect( add_query_arg(array('form_id' => $form_id, 't' => time()), esc_url_raw( $_SERVER['HTTP_REFERER'] )) );
            exit;
        }

        $userdata = array(
            'user_login' => $user_login,
            'user_email' => $user_email,
            'user_pass'  => $user_pass,
            'role'       => 'pending',
        );

        $user_id = wp_insert_user( $userdata );

        if ( is_wp_error( $user_id ) ) {
            set_transient( 'boosted_frontend_login_registration_error_' . $form_id, $user_id->get_error_message(), 60 );
            wp_redirect( add_query_arg(array('form_id' => $form_id, 't' => time()), esc_url_raw( $_SERVER['HTTP_REFERER'] )) );
            exit;
        } else {
            $verification_key = wp_generate_password( 20, false );
            update_user_meta( $user_id, 'email_verification_key', $verification_key );
            $registration_page_id = get_option( 'boosted_frontend_login_registration_page_id' );
            $registration_url = $registration_page_id ? get_permalink( $registration_page_id ) : home_url();

            $verification_url = add_query_arg(
                array(
                    'action' => 'verify_email',
                    'key'    => $verification_key,
                    'user'   => $user_id,
                    '_wpnonce' => wp_create_nonce('verify_email_' . $user_id),
                ),
                $registration_url
            );

            // Translators: %s is the verification URL that the user needs to click to verify their email.
            $message = sprintf( __( 'Please verify your email by clicking the following link: %s', 'boosted-front-end-login' ), $verification_url );
            wp_mail( $user_email, __( 'Email Verification', 'boosted-front-end-login' ), $message );

            set_transient( 'boosted_frontend_login_registration_message_' . $form_id, __('Registration complete. Please check your email to verify your account.', 'boosted-front-end-login'), 60 );
            do_action('boosted_front_end_register_success', $user_id, $userdata, $verification_url);
            wp_redirect( add_query_arg(array('form_id' => $form_id, 't' => time()), esc_url_raw( $_SERVER['HTTP_REFERER'] )) );
            exit;
        }
    }
}
add_action('admin_post_nopriv_front_end_register', __NAMESPACE__ . '\\front_end_register');
add_action('admin_post_front_end_register', __NAMESPACE__ . '\\front_end_register');

function verify_email() {
    if ( isset( $_GET['key'] ) && isset( $_GET['user'] ) && isset( $_GET['_wpnonce'] ) ) {
        
        if (!wp_verify_nonce(sanitize_text_field(wp_unslash($_GET['_wpnonce'])), 'verify_email_' . intval($_GET['user']))) {
            wp_die( esc_html__( 'Nonce verification failed', 'boosted-front-end-login' ) );
        }

        $verification_key = sanitize_text_field( $_GET['key'] );
        $user_id = intval( $_GET['user'] );
        $stored_key = get_user_meta( $user_id, 'email_verification_key', true );
        $registration_page_id = get_option( 'boosted_frontend_login_registration_page_id' );
        $registration_url = $registration_page_id ? get_permalink( $registration_page_id ) : home_url();

        if ( $stored_key === $verification_key ) {
            delete_user_meta( $user_id, 'email_verification_key' );
            wp_update_user( array( 'ID' => $user_id, 'role' => 'subscriber' ) );

            set_transient( 'boosted_frontend_login_verification_message_' . $user_id, __( 'Your email has been verified. You can now log in.', 'boosted-front-end-login' ), 60 );
            do_action('boosted_front_end_email_verified', $user_id);
            wp_redirect( add_query_arg( 'verified', 1, $registration_url ) );
            exit;
        } else {
            set_transient( 'boosted_frontend_login_verification_error_' . $user_id, __( 'Invalid verification key.', 'boosted-front-end-login' ), 60 );
            do_action('boosted_front_end_email_verification_failed', $user_id);
            wp_redirect( add_query_arg( 'verified', 0, $registration_url ) );
            exit;
        }
    }
}
add_action( 'init', __NAMESPACE__ . '\\verify_email' );

function prevent_pending_role_login( $user ) {
    if ( is_wp_error( $user ) ) {
        return $user;
    }

    if ( in_array( 'pending', (array) $user->roles ) ) {
        return new \WP_Error( 'pending_role', __( 'Your account is still pending approval.', 'boosted-front-end-login' ) );
    }

    return $user;
}
add_filter( 'wp_authenticate_user', __NAMESPACE__ . '\\prevent_pending_role_login', 10, 1 );
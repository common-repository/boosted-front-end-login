<?php
/**
 *
 * @package Boosted
 */

namespace BoostedFrontEndLogin;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function check_if_page_exists($title, $content, $option_name) {
    $query = new \WP_Query( array(
        'post_type'      => 'page',
        'post_status'    => 'publish',
        'title'          => $title,
        'posts_per_page' => 1,
    ) );

    if ( ! $query->have_posts() ) {
        $page = array(
            'post_title'    => $title,
            'post_content'  => $content,
            'post_status'   => 'publish',
            'post_type'     => 'page',
        );

        $post_id = wp_insert_post( $page );

        if ($post_id) {
            update_option( $option_name, $post_id );
        }
    } else {
        $page = $query->posts[0];
        update_option( $option_name, $page->ID );
    }
}

function create_login_pages() {
    check_if_page_exists('Login', '<!-- wp:boosted/boosted-front-end-login /-->', 'boosted_frontend_login_login_page_id');
    check_if_page_exists('Lost Password?', '<!-- wp:boosted/boosted-lost-password /-->', 'boosted_frontend_login_lost_password_page_id');
    check_if_page_exists('Register', '<!-- wp:boosted/boosted-registration /-->', 'boosted_frontend_login_registration_page_id');
}

function lostpassword_page_link($lostpassword_url) {
    $lost_page_id = get_option( 'boosted_frontend_login_lost_password_page_id' );

    if ( $lost_page_id ) {
        return get_permalink( $lost_page_id );
    }

    return $lostpassword_url;
}

function registration_page_link($register_url) {
    $registration_page_id = get_option( 'boosted_frontend_login_registration_page_id' );

    if ( $registration_page_id ) {
        return get_permalink( $registration_page_id );
    }

    return $register_url;
}

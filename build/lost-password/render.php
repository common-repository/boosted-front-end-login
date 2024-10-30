<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<div <?php echo wp_kses_data( get_block_wrapper_attributes() ); ?>>
    <?php
    $form_id = $attributes['uniqueId'];
    $lost_password_error = get_transient('boosted_frontend_login_lost_password_error_' . $form_id);
    $lost_password_message = get_transient('boosted_frontend_login_lost_password_message_' . $form_id);

    if ( $lost_password_error ) :
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
            <?php echo wp_kses(apply_filters('boosted_frontend_login_lost_password_error_message_', $lost_password_error), $allowed_html); ?>
            <?php delete_transient( 'boosted_frontend_login_lost_password_error_' . $form_id ); ?>
        </div>
    <?php endif; ?>

    <?php if ( $lost_password_message ) : 
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
        <div class="boosted-front-end-login-success" role="alert">
            <?php echo wp_kses(apply_filters('boosted_lost_password_success_message', $lost_password_message), $allowed_html); ?>
            <?php delete_transient( 'boosted_frontend_login_lost_password_message_' . $form_id ); ?>
        </div>
    <?php endif; ?>

    <form class="boosted-front-end boosted-front-end-lost-password" method="post" action="<?php echo esc_url(add_query_arg(array('t' => time()), admin_url('admin-post.php'))); ?>" name="Lost-Password-Form">
        <?php wp_nonce_field( 'front_end_lost_password_action', 'front_end_lost_password_nonce' ); ?>
        <input type="hidden" name="action" value="front_end_lost_password">
        <input type="hidden" name="form_id" value="<?php echo esc_attr( $form_id ); ?>">
        <?php do_action('boosted_front_end_lost_password_form_before_fields'); ?>
        <?php if ( ! empty( $attributes['lostDescription'] ) ) : ?>
            <p class="boosted-front-end-form-lost-description"><?php echo wp_kses_post( $attributes['lostDescription'] ); ?></p>
        <?php endif; ?>
        <p class="boosted-front-end-form-username">
            <?php if ( ! empty( $attributes['usernameLabel'] ) ) : ?><label for="user_login"><?php echo wp_kses_post( $attributes['usernameLabel'] ); ?></label><?php endif; ?>
            <input class="boosted-front-end-username-field" type="text" id="user_login" name="user_login" placeholder="<?php echo esc_attr( $attributes['usernamePlaceholder'] ); ?>" required>
        </p>
        <p class="boosted-front-end-form-submit">
            <input class="boosted-front-end-submit-btn" type="submit" value="<?php echo esc_attr( $attributes['resetButtonLabel'] ); ?>" aria-label="<?php esc_html_e('Request a password reset', 'boosted-front-end-login'); ?>">
        </p>
    </form>
</div>

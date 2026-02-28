<?php
/**
 * Feedback tab renderer.
 *
 * Displays a form so site admins can send feedback
 * directly to the theme authors.
 *
 * @package WpRentals Core
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Render the feedback form tab.
 *
 * Outputs a small form that sends an email to the
 * theme authors when submitted.
 */
function wprentals_ptc_render_feedback_tab() {
    if ( isset( $_GET['sent'] ) ) {
        echo '<div class="notice notice-success"><p>' . esc_html__( 'Feedback sent. Thank you!', 'wprentals-core' ) . '</p></div>';
    }
    ?>
    <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
        <?php wp_nonce_field( 'wprentals_feedback_nonce_action', 'wprentals_feedback_nonce' ); ?>
        <div class="wprentals-form wprentals-third">
            <div class="wprentals-row wprentals-column">
                <label class="wprentals-label-full" for="wprentals_feedback_name"><?php echo esc_html__( 'Your Name', 'wprentals-core' ); ?></label>
                <input type="text" name="wprentals_feedback_name" id="wprentals_feedback_name" class="wprentals-2025-input" />
            </div>
            <div class="wprentals-row wprentals-column">
                <label class="wprentals-label-full" for="wprentals_feedback_email"><?php echo esc_html__( 'Your Email', 'wprentals-core' ); ?></label>
                <input type="email" name="wprentals_feedback_email" id="wprentals_feedback_email" class="wprentals-2025-input" />
            </div>
            <div class="wprentals-row wprentals-column">
                <label class="wprentals-label-full" for="wprentals_feedback_message"><?php echo esc_html__( 'Message', 'wprentals-core' ); ?></label>
                <textarea name="wprentals_feedback_message" id="wprentals_feedback_message" class="wprentals-2025-input" rows="5"></textarea>
            </div>
        </div>
        <input type="hidden" name="action" value="wprentals_feedback_submit">
        <?php 
        submit_button( 
            esc_html__( 'Send Feedback', 'wprentals-core' ), 
            'primary wprentals_button' 
        ); 
        ?>
    </form>
    <?php
}

/**
 * Handle submission of the feedback form.
 *
 * Validates the request and emails the provided
 * details to the theme author.
 */
function wprentals_ptc_handle_feedback() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( __( 'You do not have permission to perform this action.', 'wprentals-core' ) );
    }

    $wprentals_branding = wprentals_theme_branding(); // Uses the filter system

    check_admin_referer( 'wprentals_feedback_nonce_action', 'wprentals_feedback_nonce' );

    $name    = isset( $_POST['wprentals_feedback_name'] ) ? sanitize_text_field( wp_unslash( $_POST['wprentals_feedback_name'] ) ) : '';
    $email   = isset( $_POST['wprentals_feedback_email'] ) ? sanitize_email( wp_unslash( $_POST['wprentals_feedback_email'] ) ) : '';
    $message = isset( $_POST['wprentals_feedback_message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['wprentals_feedback_message'] ) ) : '';

    $subject = 'WpRentals Feedback';

    $headers = array( 'Content-Type: text/html; charset=UTF-8' );
    if ( $email ) {
        $headers[] = 'Reply-To: ' . $name . ' <' . $email . '>';
    }

    wp_mail( 'crerem@gmail.com', $subject, nl2br( $message ), $headers );

    wp_redirect( add_query_arg( array(
        'page' => 'wprentals-post-type-control-feedback',
        'sent' => '1',
    ), admin_url( 'admin.php' ) ) );
    exit;
}

<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class CML_Form_Handler {

    public function __construct() {
        add_shortcode( 'contact_manager', array( $this, 'render_form' ) );
        add_action( 'admin_post_cml_submit_form', array( $this, 'handle_form_submission' ) );
        add_action( 'admin_post_nopriv_cml_submit_form', array( $this, 'handle_form_submission' ) );
    }

    public function render_form() {
        ob_start();

        // Display status feedback messages if present in the URL query
        if ( isset( $_GET['cml_status'] ) ) {
            $status = sanitize_text_field( wp_unslash( $_GET['cml_status'] ) );
            if ( 'success' === $status ) {
                echo '<p style="color: green;">Thank you! Your message has been sent successfully.</p>';
            } else {
                echo '<p style="color: red;">Please fill in all required fields correctly.</p>';
            }
        }
        ?>
        <form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
            <input type="hidden" name="action" value="cml_submit_form">
            <?php wp_nonce_field( 'cml_form_nonce_action', 'cml_nonce' ); ?>
            
            <p>
                <label for="cml_name">Name *</label><br>
                <input type="text" id="cml_name" name="cml_name" required>
            </p>
            <p>
                <label for="cml_email">Email *</label><br>
                <input type="email" id="cml_email" name="cml_email" required>
            </p>
            <p>
                <label for="cml_subject">Subject</label><br>
                <input type="text" id="cml_subject" name="cml_subject">
            </p>
            <p>
                <label for="cml_message">Message *</label><br>
                <textarea id="cml_message" name="cml_message" required></textarea>
            </p>
            <p>
                <input type="submit" value="Send Message">
            </p>
        </form>
        <?php
        return ob_get_clean();
    }

    public function handle_form_submission() {
        // 1. Verify nonce existence, type, and validity using wp_unslash()
        if ( ! isset( $_POST['cml_nonce'] ) || ! is_string( $_POST['cml_nonce'] ) ) {
            wp_die( 'Security check failed.' );
        }

        if ( ! wp_verify_nonce( wp_unslash( $_POST['cml_nonce'] ), 'cml_form_nonce_action' ) ) {
            wp_die( 'Security check failed.' );
        }

        // 2. Handle missing or invalid referrer URLs safely
        $sendback = wp_get_referer();
        if ( ! $sendback || ! wp_validate_redirect( $sendback, home_url() ) ) {
            $sendback = home_url();
        }

        // 3. Validate that all expected POST values are strings before sanitization
        $fields = array( 'cml_name', 'cml_email', 'cml_subject', 'cml_message' );
        foreach ( $fields as $field ) {
            if ( isset( $_POST[ $field ] ) && ! is_string( $_POST[ $field ] ) ) {
                $redirect_url = add_query_arg( 'cml_status', 'error', $sendback );
                wp_safe_redirect( $redirect_url );
                exit;
            }
        }

        // Extract and sanitize inputs safely
        $name    = isset( $_POST['cml_name'] ) ? sanitize_text_field( wp_unslash( $_POST['cml_name'] ) ) : '';
        $email   = isset( $_POST['cml_email'] ) ? sanitize_email( wp_unslash( $_POST['cml_email'] ) ) : '';
        $subject = isset( $_POST['cml_subject'] ) ? sanitize_text_field( wp_unslash( $_POST['cml_subject'] ) ) : '';
        $message = isset( $_POST['cml_message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['cml_message'] ) ) : '';

        // Validate required fields and email structure
        if ( empty( $name ) || empty( $email ) || empty( $message ) || ! is_email( $email ) ) {
            $redirect_url = add_query_arg( 'cml_status', 'error', $sendback );
            wp_safe_redirect( $redirect_url );
            exit;
        }

        // Save into the custom database table
        $inserted = CML_DB::insert_message( array(
            'name'    => $name,
            'email'   => $email,
            'subject' => $subject,
            'message' => $message,
        ) );

        $status       = $inserted ? 'success' : 'error';
        $redirect_url = add_query_arg( 'cml_status', $status, $sendback );

        // 4. Use wp_safe_redirect() instead of raw wp_redirect()
        wp_safe_redirect( $redirect_url );
        exit;
    }
}
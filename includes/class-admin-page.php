<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class CML_Admin_Page {

    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
    }

    public function add_admin_menu() {
        add_menu_page(
            'Contact Messages',
            'Contact Messages',
            'manage_options',
            'contact-manager-lite',
            array( $this, 'render_admin_page' ),
            'dashicons-email-alt',
            26
        );
    }

    public function render_admin_page() {

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'You are not allowed to access this page.', 'contact-manager-lite' ) );
        }

        $messages = CML_DB::get_messages();

        ?>
        <div class="wrap">
            <h1><?php echo esc_html__( 'Contact Messages', 'contact-manager-lite' ); ?></h1>

            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Subject</th>
                        <th>Message</th>
                        <th>Date</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if ( is_array( $messages ) && ! empty( $messages ) ) : ?>

                        <?php foreach ( $messages as $msg ) : ?>
                            <tr>
                                <td><?php echo esc_html( $msg->id ); ?></td>
                                <td><?php echo esc_html( $msg->name ); ?></td>
                                <td><?php echo esc_html( $msg->email ); ?></td>
                                <td><?php echo esc_html( $msg->subject ); ?></td>
                                <td><?php echo esc_html( $msg->message ); ?></td>
                                <td><?php echo esc_html( $msg->created_at ); ?></td>
                            </tr>
                        <?php endforeach; ?>

                    <?php else : ?>

                        <tr>
                            <td colspan="6">
                                <?php echo esc_html__( 'No messages found.', 'contact-manager-lite' ); ?>
                            </td>
                        </tr>

                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php
    }
}
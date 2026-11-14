<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class CML_DB {

    public static function create_table() {
        global $wpdb;

        $table_name      = $wpdb->prefix . 'contact_messages';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            name varchar(100) NOT NULL,
            email varchar(100) NOT NULL,
            subject varchar(255) NOT NULL,
            message text NOT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta( $sql );
    }

    public static function insert_message( $data ) {
        global $wpdb;

        if ( ! is_array( $data ) ) {
            return false;
        }

        $required_fields = array(
            'name',
            'email',
            'subject',
            'message',
        );

        foreach ( $required_fields as $field ) {
            if ( ! isset( $data[ $field ] ) || ! is_string( $data[ $field ] ) ) {
                return false;
            }
        }

        $table_name = $wpdb->prefix . 'contact_messages';

        return $wpdb->insert(
            $table_name,
            array(
                'name'    => $data['name'],
                'email'   => $data['email'],
                'subject' => $data['subject'],
                'message' => $data['message'],
            ),
            array( '%s', '%s', '%s', '%s' )
        );
    }

    public static function get_messages() {
        global $wpdb;

        $table_name = $wpdb->prefix . 'contact_messages';

        return $wpdb->get_results(
            "SELECT id, name, email, subject, message, created_at
            FROM $table_name
            ORDER BY created_at DESC"
        );
    }
}
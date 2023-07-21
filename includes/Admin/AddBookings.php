<?php
namespace ONSBKS_Slots\Includes\Admin;

/**
 * Class AddBookings
 * @package ONSBKS_Slots\Includes\Admin
 * add new bookings per day
 * @since 1.0.0
 */
class AddBookings {

    /**
     * add new booking slot date from admin
     *
     * @since 1.0.0
     */
    function add_new_booking() {
        if ( ! isset( $_POST['sbks_submit_meta'] ) ) {
              return;
        }

        if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'sbks_add_nonce' ) ) {
              wp_die( 'Are you cheating ?' );
        }

        if ( ! current_user_can('manage_options') ) {
              wp_die( 'Are you cheating ?' );     
        }
        $time_hours = $this->get_time_hour();
        $product_id = $_POST['sbks_product_id'];
        $date = 'sbks_product_date'.$_POST['sbks_product_date'];
        $meta_value = array();
        foreach ( $time_hours as $time_hour ) {
            $meta_key = 'sbks_' . $time_hour;
            $value = $_POST[$meta_key];
            if( $_POST[$meta_key] == '' || empty($_POST[$meta_key] || $_POST[$meta_key] == null)) {
                $value = 0;
            }
            $meta_value[$time_hour] = $value;
        }
        $meta = add_post_meta( $product_id, $date, $meta_value, true );
        if ( ! $meta ) {
            echo "<div style='position: fixed; left:45%'>Already exists in record... please update it <a href=". admin_url('/admin.php?page=reactions-address-book') .">here...</a></div>";
        }
    }

    /**
     * retrieves the array with product_id, title, meta_key
     *
     * @since 1.0.0
     *
     * @param string $offset
     * @param string $limit
     *
     * @return array
     */
    public function get_product_id_title_sbks_product_date($offset = '0', $limit = '10'): array {
        global $wpdb;
        return $results = $wpdb->get_results("SELECT {$wpdb->prefix}posts.ID, {$wpdb->prefix}posts.post_title, {$wpdb->prefix}postmeta.meta_key FROM {$wpdb->prefix}posts INNER JOIN {$wpdb->prefix}postmeta ON {$wpdb->prefix}posts.ID = {$wpdb->prefix}postmeta.post_id AND {$wpdb->prefix}postmeta.meta_key LIKE '%sbks_product_date%' ORDER BY {$wpdb->prefix}postmeta.meta_key DESC  LIMIT {$offset},{$limit}", 'ARRAY_A');
    }

    /**
     * get all required time slots
     *
     * @since 1.0.0
     * 
     * @return array
     */
    public function get_time_hour(): array {
        return array(
            '12:00_am',
            '01:00_am',
            '02:00_am',
            '03:00_am',
            '04:00_am',
            '05:00_am',
            '06:00_am',
            '07:00_am',
            '08:00_am',
            '09:00_am',
            '10:00_am',
            '11:00_am',
            '12:00_pm',
            '01:00_pm',
            '02:00_pm',
            '03:00_pm',
            '04:00_pm',
            '05:00_pm',
            '06:00_pm',
            '07:00_pm',
            '08:00_pm',
            '09:00_pm',
            '10:00_pm',
            '11:00_pm'
        );
    }
}

<?php

namespace ONSBKS_Slots\Includes\Admin;

/**
 * Class Ajax
 * @package ONSBKS_Slots\Includes\Admin
 * handles the Admin ajax requests
 * @since 1.0.0
 */
class Ajax {

    /**
     * Ajax constructor.
     *
     * @since 1.0.0
     */
    function __construct() {
        add_action( 'wp_ajax_sbks_admin_slot_list', [ $this, 'sbks_admin_slot_list' ] );
        add_action( 'wp_ajax_sbks_admin_update_list', [ $this, 'sbks_admin_update_list' ] );
    }

    /**
     * retrieves the meta_value of meta_key sbks_product_date%DATE%
     *
     * @since 1.0.0
     *
     * @action sbks_admin_slot_list
     */
    public function sbks_admin_slot_list() {
        if( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'sbks_admin_slot_list' ) ) {
            wp_send_json_error( array(
                'message' => 'nonce verification failed'
            ) );
        }

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error(
                array(
                    'message'   =>  'You are not allowed to do this task ! next time it will crash your device'
                )
            );
        }
        $product_id = sanitize_text_field( $_REQUEST['product_id'] );
        $meta_key = sanitize_text_field( $_REQUEST['meta_key'] );
        $meta_value = get_post_meta( $product_id, $meta_key, true );
        if ( ! $meta_value ) {
            wp_send_json_error( array(
                'message' => 'No results found'
            ) );
        } else {
            $slots = [];
            foreach ( $meta_value as $key => $value ) {
                array_push( $slots, [ 'time' => $key, 'slot' => $value ] );
            }
            wp_send_json_success(
                array(
                    'message'   => 'Request successful',
                    'slots'=> $slots
                )
            );
        }
    }

    /**
     * update sbks_product_date%DATE% postmeta of products
     *
     * @since 1.0.0
     *
     * @action sbks_admin_update_list
     */
    public function sbks_admin_update_list() {
        if ( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'sbks_admin_update_list' ) ) {
            wp_send_json_error( array(
                'message'   => 'Verification failed...'
            ) );
        }
        if( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( array(
                'message'   =>    'You are not permitted to make this request'
            ));
        }
        $product_id = sanitize_text_field( $_REQUEST['product_id'] );
        $meta_key = sanitize_text_field( $_REQUEST['meta_key'] );
        $base_slots = $this->get_time_hour();
        $slots = [];
        foreach ( $_REQUEST['form'] as $value ) {
            $slots[$value['name']] = $value['value'];
        }
        $meta_value = array_merge( $base_slots, $slots );
        $result = update_post_meta( $product_id, $meta_key, $meta_value );
        if( $result ) {
            wp_send_json_success( array(
                'message'   =>  'Updated successfully...',
                'product_id'=>  $product_id,
                'meta_key'  =>  $meta_key,
                'slots'     =>  $slots
            ));
        } else {
            wp_send_json_error( array(
                'message'   =>    'Failed to update information to database'
            ));
        }
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
            '12:00_am' => 0,
            '01:00_am' => 0,
            '02:00_am' => 0,
            '03:00_am' => 0,
            '04:00_am' => 0,
            '05:00_am' => 0,
            '06:00_am' => 0,
            '07:00_am' => 0,
            '08:00_am' => 0,
            '09:00_am' => 0,
            '10:00_am' => 0,
            '11:00_am' => 0,
            '12:00_pm' => 0,
            '01:00_pm' => 0,
            '02:00_pm' => 0,
            '03:00_pm' => 0,
            '04:00_pm' => 0,
            '05:00_pm' => 0,
            '06:00_pm' => 0,
            '07:00_pm' => 0,
            '08:00_pm' => 0,
            '09:00_pm' => 0,
            '10:00_pm' => 0,
            '11:00_pm' => 0
        );
    }
}
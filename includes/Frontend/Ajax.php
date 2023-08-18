<?php

namespace ONSBKS_Slots\Includes\Frontend;

/**
 * Class Ajax
 * @package ONSBKS_Slots\Includes\Frontend
 * handle frontend ajax
 * @since 1.0.0
 */
class Ajax {

    /**
     * @var string date_time
     *
     * @since 1.0.0
     */
    public $date_time = '';

    /**
     * @var array date_time_collector
     *
     * @since 1.0.0
     */
    public $date_time_collector = array();

    /**
     * initialize the class and ajax action hooks
     *
     * @since 1.0.0
     */
    public function __construct() {
        add_action( 'wp_ajax_sbks_select_action', [ $this, 'sbks_select_action' ] );
        add_action( 'wp_ajax_nopriv_sbks_select_action', [ $this, 'sbks_select_action' ] );

        add_action( 'wp_ajax_onsbks_cart_action', [ $this, 'onsbks_cart_action' ] );
        add_action( 'wp_ajax_nopriv_onsbks_cart_action', [ $this, 'onsbks_cart_action' ] );
        add_filter( 'woocommerce_add_cart_item_data', [ $this, 'add_booked_slots'], 1, 3 );

        add_filter( 'woocommerce_add_cart_item_data', [ $this, 'sbks_add_cart_item_data'], 1, 3 );
        add_filter( 'woocommerce_get_item_data', [ $this, 'sbks_get_item_data'], 1, 3 );
        add_action( 'woocommerce_checkout_create_order_line_item', [ $this, 'sbks_checkout_create_order_line_item'], 10, 4 );
    }


    /**
     * adds cart-items and cart-itemmeta by ajax
     *
     * @author Naim-Ul-Hassan
     *
     * @since 1.4.1
     */
    public function onsbks_cart_action() {
        if( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'onsbks_react_nonce' ) ) {
            wp_send_json(prepare_result(null, "nonce verification failed", false));
        }
        wp_send_json(prepare_result(['url' => wc_get_cart_url()], "slots are saved for cart successfully"));
    }


    /**
     * add meta data to cart item
     *
     * @author Naim-Ul-Hassan
     *
     * @since 1.1.1
     *
     * @param $cart_item_data
     * @param $product_id
     * @param $variation_id
     *
     * @return array cart_item_data
     */
    public function add_booked_slots( $cart_item_data, $product_id, $variation_id ): array {
        $value = $this->get_date_time();
        $cart_item_data['date_time'] =  $value;
        return $cart_item_data;
    }

    /**
     * return date_time
     *
     * @since 1.0.0
     *
     * @return string
     */
    public function get_date_time(): string {
        return $this->date_time;
    }

    /**
     * return date_time_collector
     *
     * @since 1.0.0
     *
     * @return array
     */
    public function get_date_time_collector() : array {
        return $this->date_time_collector;
    }

    /**
     * adds cart-items and cart-itemmeta by ajax
     *
     * @since 1.0.0
     */
    public function sbks_select_action() {
        if( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'sbks_select_nonce' ) ) {
            wp_send_json_error( array(
                'message' => "nonce verification failed"
            ) );
        }

        if( isset( $_REQUEST['slots'] ) ) {
            if ( is_array( $_REQUEST['slots'] ) ) {
                $date_time_collector = $this->get_date_time_collector();
                $product_id = sanitize_text_field( $_REQUEST['product_id'] );
                $non_sanitized_slots = wp_unslash( $_REQUEST['slots'] );
                WC()->cart->empty_cart();
                foreach ( $non_sanitized_slots as $ns_slot ) {
                    array_push( $date_time_collector, sanitize_text_field( $ns_slot['date_time']) );
                    $this->date_time = sanitize_text_field( $ns_slot['date_time'] );
                    WC()->cart->add_to_cart( $product_id );
                }

                wp_send_json_success( array(
                    'message' => "slots are saved for cart successfully",
                    'cart'    => $date_time_collector,
                    'url'     => wc_get_cart_url()
                ) );
            } else {
                wp_send_json_error( array(
                    'message' => 'please select at least one slot to proceed to payment'
                ) );
            }
        } else {
            wp_send_json_error( array(
                'message' => 'please select at least one slot to proceed to payment'
            ) );
        }
    }

    /**
     * add meta data to cart item
     *
     * @since 1.0.0
     *
     * @param $cart_item_data
     * @param $product_id
     * @param $variation_id
     *
     * @return array cart_item_data
     */
    public function sbks_add_cart_item_data( $cart_item_data, $product_id, $variation_id ): array {
        $value = $this->get_date_time();
        $cart_item_data['date_time'] =  $value;
        return $cart_item_data;
    }

    /**
     * get meta data of cart item
     *
     * @since 1.0.0
     *
     * @param $item_data
     * @param $cart_item_data
     *
     * @return mixed
     */
    public function sbks_get_item_data( $item_data, $cart_item_data ) {
        if( isset( $cart_item_data['date_time'] ) ) {
            $item_data[] = array(
                'key' => 'Your slot',
                'value' => wc_clean( $cart_item_data['date_time'] )
            );
        }
        return $item_data;
    }

    /**
     * creates per item meta data for orders
     *
     * @since 1.0.0
     *
     * @param $item
     * @param $cart_item_key
     * @param $values
     * @param $order
     */
    public function sbks_checkout_create_order_line_item( $item, $cart_item_key, $values, $order ) {
        $item->add_meta_data(
            'Your slot',
            $values['date_time'],
            true
        );
    }
}
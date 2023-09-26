<?php

namespace ONSBKS_Slots\Includes\Frontend;

use ONSBKS_Slots\Includes\Constants;
use ONSBKS_Slots\RestApi\Repositories\BookingRepository;
use Exception;
/**
 * Class Ajax
 * @package ONSBKS_Slots\Includes\Frontend
 * handle frontend ajax
 * @since 1.0.0
 */
class Ajax {

    public int $bookingId = 0;

    /**
     * initialize the class and ajax action hooks
     *
     * @since 1.0.0
     */
    public function __construct() {
        add_action( 'wp_ajax_onsbks_add_to_cart', [ $this, 'addToCart'] );
        add_action( 'wp_ajax_nopriv_onsbks_add_to_cart', [ $this, 'addToCart'] );

        add_filter( 'woocommerce_add_cart_item_data', [ $this, 'addCartItemData'], 1, 3 );

        add_filter( 'woocommerce_get_item_data', [ $this, 'showCartItemData'], 1, 3 );

        add_action( 'woocommerce_checkout_create_order_line_item', [ $this, 'checkoutCreateOrderLineItem'], 10, 4 );
    }


    /**
     * adds cart-items and cart-itemmeta by ajax
     *
     * @since 1.3.1
     * @return void
     */
    public function addToCart(): void
    {
        if( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'onsbks_react_nonce' ) ) {
            wp_send_json_error( array(
                'message' => "nonce verification failed",
                'result'  => null,
                'success' => false
            ), 401 );
        }

        if( isset( $_REQUEST[Constants::BOOKING_ID_KEY]) ) {
            try{
                $bookingId = $_REQUEST[Constants::BOOKING_ID_KEY];

                $bookingRepo = new BookingRepository();
                $booking = $bookingRepo->findById($bookingId);

                WC()->cart->empty_cart();
                $this->bookingId = $bookingId;
                WC()->cart->add_to_cart( $booking->getProductId(), $booking->getSeats());


                wp_send_json_success( array(
                    'message' => "slots are saved for cart successfully",
                    'result'  => wc_get_checkout_url(),
                    'success' => true
                ) );
            } catch (Exception $e) {
                wp_send_json_error( array(
                    'message' => 'something went wrong',
                    'result'  => null,
                    'success' => false
                ), 500 );
            }
        } else {
            wp_send_json_error( array(
                'message' => 'bookingId is not valid',
                'result'  => null,
                'success' => false
            ), 400 );
        }
    }


    public function addCartItemData($cart_item_data, $product_id, $variation_id ): array
    {
        $cart_item_data[Constants::BOOKING_ID_KEY] =  $this->bookingId;
        return $cart_item_data;
    }

    /**
     * get meta data of cart item
     *
     * @since 1.3.1
     *
     * @param $item_data
     * @param $cart_item_data
     *
     * @return mixed
     */
    public function showCartItemData($item_data, $cart_item_data ): array
    {
        if( isset( $cart_item_data[Constants::BOOKING_ID_KEY] ) ) {
            $item_data[] = array(
                'key' => Constants::BOOKING_ID_KEY,
                'value' => wc_clean( $cart_item_data[Constants::BOOKING_ID_KEY] )
            );
        }
        return $item_data;
    }

    /**
     * creates per item meta data for orders
     *
     * @since 1.3.1
     *
     * @param $item
     * @param $cart_item_key
     * @param $values
     * @param $order
     */
    public function checkoutCreateOrderLineItem($item, $cart_item_key, $values, $order ): void
    {
        $item->add_meta_data(
            Constants::BOOKING_ID_KEY,
            $values[Constants::BOOKING_ID_KEY],
            true
        );
    }

}
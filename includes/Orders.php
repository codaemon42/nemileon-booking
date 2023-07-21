<?php
namespace ONSBKS_Slots\Includes;

/**
 * Class Orders
 * @package ONSBKS_Slots\Includes
 * orders handler class
 *
 * @since 1.0.0
 */
class Orders {

    /**
     * initialize the Orders class
     *
     * @since 1.0.0
     */
    function __construct() {
        add_action('woocommerce_after_checkout_validation', [$this, 'sbks_checkout_validation']);
        add_action('woocommerce_order_status_completed', [$this, 'sbks_update_product_meta_data'], 10, 1);
    }

    /**
     * validate slots are available before order process
     *
     * @since 1.0.0
     */
    public function sbks_checkout_validation() {
        $cart_items = WC()->cart->get_cart();
        foreach ( $cart_items as $cart_item_data) {
            $date_time = $cart_item_data['date_time'];
            $product_id = $cart_item_data['product_id'];
            $quantity = $cart_item_data['quantity'];

            $meta = explode('__', $date_time); // 2021-03-24__02:00_am
            $time = $meta[1];  // 02:00_am
            $meta_key = 'sbks_product_date' . $meta[0];  // sbks_product_date2021-03-24
            $meta_value = get_post_meta($product_id, $meta_key, true);

            if ($meta_value[$time] < $quantity) {
                if ($meta_value[$time] == 0) {
                    wc_add_notice(__("Sorry !!! Slot: {$date_time} has already being booked.", 'woocommerce'), 'error');
                } else {
                    wc_add_notice(__("Hurry !!! Only {$meta_value[$time]} slots left for {$date_time}  ", 'woocommerce'), 'error');
                }
            }
        }
    }

    /**
     * update sbks_product_date%2021-03-24% meta data
     *
     * @param $order_id
     *
     * @return void
     * @since 1.0.0
     *
     */
    function sbks_update_product_meta_data($order_id) {
        $order = wc_get_order($order_id);
        foreach ($order->get_items() as $item) {
            $item_data = $item->get_data();
            $product_id = $item_data['product_id'];
            $quantity = $item_data['quantity'];
            $dateTime = $item->get_meta('Your slot');

            $meta = explode('__', $dateTime); // 2021-03-24__02:00_am
            $time = $meta[1];  // 02:00_am
            $meta_key = 'sbks_product_date' . $meta[0];  // sbks_product_date2021-03-24
            $meta_value = get_post_meta($product_id, $meta_key, true);

            $meta_value[$time] = $meta_value[$time] - $quantity;

            update_post_meta($product_id, $meta_key, $meta_value);
        }
    }
}
<?php
namespace ONSBKS_Slots\Includes;

use ONSBKS_Slots\Includes\WooCommerce\BookingSlotProduct;
use ONSBKS_Slots\RestApi\Exceptions\InvalidBookingStatusException;
use ONSBKS_Slots\RestApi\Repositories\BookingRepository;
use PHPUnit\Exception;

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
     * @modified 1.3.1
     */
    function __construct() {
        add_action('woocommerce_after_checkout_validation', [$this, 'validateBeforeOrder']);

        add_action('woocommerce_before_order_item_line_item_html', [$this, 'insertPreviewInOrderDetail'], 10, 3);
    }


    /**
     * Insert the React App for booking preview before order line item
     *
     * @since 1.3.1
     * @author Naim-Ul-Hassan
     *
     * @param $item_id
     * @param $item
     * @param $order
     * @return void
     */
    public function insertPreviewInOrderDetail($item_id, $item, $order): void
    {
            $bookingId = $item->get_meta("BookingId", true);
            if($bookingId){
                wp_enqueue_script('sbks-frontend-react-script');
                wp_enqueue_style('sbks-frontend-react-style');
                echo '<div style="padding: 10px 20px 20px 20px">';
                echo '<div id="ONSBKS_BOOKING_SECTION" data-booking-id="'.$bookingId.'"></div>';
                echo '</div>';
            }

    }

    /**
     * validate slots are not corrupted in cart before Order
     *
     * @since 1.3.1
     * @author Naim-Ul-Hassan
     *
     * @return void
     */
    public function validateBeforeOrder(): void
    {
        $cart_items = WC()->cart->get_cart();
        foreach ( $cart_items as $cart_item_data) {
            if(isset($cart_item_data['BookingId'])){
                $BookingId = $cart_item_data['BookingId'];
                $quantity = $cart_item_data['quantity'];

                $bookingRepo = new BookingRepository();

                try {
                    $booking = $bookingRepo->findById($BookingId);
                    if ($booking->getSeats() != $quantity) {
                        wc_add_notice("Booking Seats mismatched ", 'error');
                    }
                } catch (\Exception $e) {
                    wc_add_notice("Booking Error Occurred", 'error');
                }
            }
        }
    }
}
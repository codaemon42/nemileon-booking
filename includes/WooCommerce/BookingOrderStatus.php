<?php

namespace ONSBKS_Slots\Includes\WooCommerce;

use ONSBKS_Slots\Includes\Constants;

class BookingOrderStatus
{

    public function __construct()
    {
        add_action('woocommerce_order_status_changed', [$this, 'handleBookingOnOrderStatusChange'], 10, 4 );
    }

    public function handleBookingOnOrderStatusChange($order_id, $old_status, $new_status, \WC_Order $order): void
    {
        $items = $order->get_items();
        foreach ($items as $item)
        {
            $bookingId = strval($item->get_meta(Constants::BOOKING_ID_KEY));
            if(!$bookingId) error_log("No BookingId Found");


            error_log(" oldStatus: $old_status newStatus: $new_status bookingId: " + strval($bookingId));
        }
    }
}
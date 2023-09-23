<?php

namespace ONSBKS_Slots\Includes\WooCommerce;

use ONSBKS_Slots\Includes\Constants;
use ONSBKS_Slots\Includes\Log;
use ONSBKS_Slots\Includes\Models\Settings;
use ONSBKS_Slots\Includes\State;
use ONSBKS_Slots\Includes\Status\BookingStatus;
use ONSBKS_Slots\RestApi\Repositories\BookingRepository;

class BookingOrderStatus
{

    private BookingRepository $bookingRepository;


    public function __construct()
    {
        add_action('woocommerce_order_status_changed', [$this, 'handleBookingOnOrderStatusChange'], 10, 4 );
        $this->bookingRepository = new BookingRepository();
    }

    public function handleBookingOnOrderStatusChange($order_id, $old_status, $new_status, \WC_Order $order): void
    {

        $items = $order->get_items();
        foreach ($items as $item)
        {
            $bookingId = strval($item->get_meta(Constants::BOOKING_ID_KEY));
            if(!$bookingId) error_log("No BookingId Found");

            Log::info(" oldStatus: %s newStatus: %s bookingId: %d", $old_status, $new_status, $bookingId );

            $settings = new Settings(State::$SETTINGS);

            $statuses = explode(",", $settings->getBookingOrderPaidStatuses());
            if(in_array(ucfirst($new_status), $statuses)) {

                $booking = $this->bookingRepository->findById(strval($bookingId));
                if(!$booking) {
                    Log::warn("No Booking Found with id %d", $bookingId);
                    return;
                }

                $booking->setStatus(BookingStatus::COMPLETED);

                $this->bookingRepository->update($booking->getId(), $booking->getData());
            }
        }
    }
}
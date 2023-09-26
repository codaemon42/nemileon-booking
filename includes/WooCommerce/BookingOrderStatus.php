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

                $this->sendTicketConfirmEmail($bookingId, $order);
            }
        }
    }

    public function sendTicketConfirmEmail($bookingId, \WC_Order $order): void
    {
        $to = $order->get_billing_email();
        $subject = 'New Booking Ticket';
        $siteTitle = get_bloginfo();
        $mainBody = "Your booking at $siteTitle is confirmed. you can download the ticket from the link below.
        <pre> Note:  Open the link as a loggedin user. if you are not an user, open it in the same browser from where you booked. It's for your security of the ticket.</pre>";

        // Load your HTML email template
        $html_template = file_get_contents(ONSBKS_URL . '/includes/Admin/views/ticket-confirm-email.php');



        // Replace placeholders in the template with dynamic data
        $issuer_name = $order->get_formatted_billing_full_name(); // Replace with the actual issuer's name
        $ticket_number = $bookingId; // Replace with the actual ticket number
        $ticket_download_link = site_url() . "/booking-slot/?booking_ticket=".$bookingId; // Replace with the actual download link

        $html_template = str_replace('{{NAME}}', $issuer_name, $html_template);
        $html_template = str_replace('{{TITLE}}', $subject, $html_template);
        $html_template = str_replace('{{MAIN_BODY}}', $mainBody, $html_template);
        $html_template = str_replace('{{TICKET_NUMBER}}', $ticket_number, $html_template);
        $html_template = str_replace('{{TICKET_LINK}}', $ticket_download_link, $html_template);
        $html_template = str_replace('{{SITE_URL}}', site_url(), $html_template);
        $html_template = str_replace('{{SITE_ICON}}', get_site_icon_url(), $html_template);

        error_log($html_template);
        // Set email headers
        $headers = array('Content-Type: text/html; charset=UTF-8');

        // Send the email
        wp_mail($to, $subject, $html_template, $headers);

    }
}

<?php

namespace ONSBKS_Slots\RestApi\Controllers;

use ONSBKS_Slots\Includes\Entities\BookingsEntity;
use ONSBKS_Slots\Includes\Models\ProductTemplate;
use ONSBKS_Slots\Includes\Models\Slot;
use ONSBKS_Slots\RestApi\Exceptions\NotBookableException;
use ONSBKS_Slots\RestApi\Services\BookingService;
use ONSBKS_Slots\RestApi\Services\ProductService;
use WP_REST_Request;

class BookingController
{


    private BookingService $bookingService;

    public function __construct()
    {
        $this->bookingService = new BookingService();
    }

    public function findAllBookings(WP_REST_Request $req): void
    {
        try{
            $query = $req->get_query_params();
            $bookings = $this->bookingService->findAll($query);
            wp_send_json_success(prepare_result($bookings));
        }
        catch (\Exception $e) {
            wp_send_json(prepare_result(false, $e->getMessage(), false), 500);
        }
    }

    public function findBookingByBookingId(WP_REST_Request $req): void
    {
        try{
            $bookingId = $req->get_param("id");
            $booking = $this->bookingService->findBookingByBookingId($bookingId, true);
            wp_send_json_success(prepare_result($booking->getData()));
        }
        catch (\Exception $e) {
            wp_send_json(prepare_result(false, $e->getMessage(), false), 500);
        }
    }

    public function createBooking(WP_REST_Request $req): void
    {
        try {
            $productTemplate = new ProductTemplate( $req->get_json_params() );

            $booking_id = $this->bookingService->createBooking( $productTemplate );
            $booking = $this->bookingService->findBookingByBookingId($booking_id);
            wp_send_json(prepare_result($booking));
        }
        catch (\Exception $e) {
            wp_send_json(prepare_result(false, $e->getMessage(), false), 500);
        }
    }

    public function updateBookingByBookingId(WP_REST_Request $req): void
    {
        try{
            $bookingId = $req->get_param("id");
            $data = $req->get_body_params();
            $booking = $this->bookingService->updateBookingByBookingId($bookingId, $data);
            wp_send_json_success(prepare_result($booking->getData()));
        }
        catch (\Exception $e) {
            wp_send_json(prepare_result(false, $e->getMessage(), false), 500);
        }
    }
}
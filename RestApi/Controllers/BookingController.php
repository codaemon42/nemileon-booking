<?php

namespace ONSBKS_Slots\RestApi\Controllers;

use ONSBKS_Slots\Includes\Models\ProductTemplate;
use ONSBKS_Slots\RestApi\Services\BookingService;
use WP_REST_Request;

/**
 * Booking Controller
 * @api ("/bookings")
 */
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
            wp_send_json(prepare_result($bookings));
        }
        catch (\Exception $e) {
            wp_send_json(prepare_result(false, $e->getMessage(), false), 500);
        }
    }

    /**
     * Find All bookings By UserId or the device fingerPrint
     *
     * This endpoint retrieves the booking list with pagination
     * @route GET /onsbks/v2/bookings/{id}
     * @param WP_REST_Request $req
     * @header finger_print
     * @header user_id
     * @header Anonymous
     *
     * @since 1.3.1
     * @return void
     */
    public function findAllBookingsByUserIdOrFingerPrint(WP_REST_Request $req): void
    {
        try{
            $fingerPrint = strval($req->get_header('finger_print'));
            $userId = $req->get_header('user_id');

            $queryParams = $req->get_query_params();
            $paged = $queryParams['paged'] ?? 1;
            $perPage = $queryParams['per_page'] ?? 10;

            $bookings = $this->bookingService->findAllByUserIdOrFingerPrint($userId, $fingerPrint, $perPage, $paged);

            wp_send_json(prepare_result($bookings));
        }
        catch (\Exception $e) {
            wp_send_json(prepare_result(false, $e->getMessage(), false), $e->getCode());
        }
    }

    /**
     * Find By Booking Id
     * @api ("/{id})
     * @param WP_REST_Request $req
     * @query_param id
     * @return void
     */
    public function findBookingByBookingId(WP_REST_Request $req): void
    {
        try{
            $bookingId = $req->get_param("id");
            $booking = $this->bookingService->findBookingByBookingId($bookingId, true);
            wp_send_json(prepare_result($booking->getData()));
        }
        catch (\Exception $e) {
            wp_send_json(prepare_result(false, $e->getMessage(), false), $e->getCode());
        }
    }

    /**
     * Creates new booking
     * @since 1.3.1
     * @param WP_REST_Request $req
     * @return void
     */
    public function createBooking(WP_REST_Request $req): void
    {
        try {
            $fingerPrint = $req->get_header('fingerprint');
            $userId = $req->get_header('user_id') ?: 0;
            $productTemplate = new ProductTemplate( $req->get_json_params() );

            $booking = $this->bookingService->createBooking( $productTemplate, $userId, $fingerPrint );
            wp_send_json(prepare_result($booking->getData()));
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
            wp_send_json(prepare_result($booking->getData()));
        }
        catch (\Exception $e) {
            wp_send_json(prepare_result(false, $e->getMessage(), false), 500);
        }
    }
}
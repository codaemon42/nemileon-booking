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


    public function createBooking(WP_REST_Request $req): void
    {
        try {
            $productTemplate = new ProductTemplate( $req->get_json_params() );

            $this->bookingService->createBooking( $productTemplate );
            wp_send_json(prepare_result([]));
        }
        catch (NotBookableException $e) {
            wp_send_json($e->getData(), $e->getCode());
        }
        catch (\Exception $e) {
            wp_send_json(prepare_result(false, 'Something went wrong', false), 500);
        }

    }
}
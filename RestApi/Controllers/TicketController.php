<?php

namespace ONSBKS_Slots\RestApi\Controllers;

use ONSBKS_Slots\Includes\Log;
use ONSBKS_Slots\RestApi\Exceptions\BookingNotFound;
use ONSBKS_Slots\RestApi\Exceptions\InvalidBookingStatusException;
use ONSBKS_Slots\RestApi\Exceptions\TicketNotValid;
use ONSBKS_Slots\RestApi\Services\TickerService;

class TicketController
{

    private TickerService $tickerService;

    public function __construct()
    {
        $this->tickerService = new TickerService();
    }

    /**
     * @throws TicketNotValid
     * @throws BookingNotFound
     * @throws InvalidBookingStatusException
     */
    public function verifyTicket(\WP_REST_Request $req)
    {
        try {
            Log::info("TicketController::verifyTicket start ticket verification");
            $bookingId = strval($req->get_param("id"));
            $ticket = $this->tickerService->verifyTicket($bookingId);

            wp_send_json(onsbks_prepare_result($ticket->getData()));
        }
        catch (\Exception $e) {
            wp_send_json(onsbks_prepare_result(false, $e->getMessage(), false), $e->getCode());
        }
    }

    /**
     * @throws BookingNotFound
     * @throws InvalidBookingStatusException
     */
    public function findTicket(\WP_REST_Request $req)
    {
        try{
            $fingerPrint = strval($req->get_header('fingerprint'));
            $userId = $req->get_header('user_id') ?: 0;
            $bookingId = strval($req->get_param("id"));

            $ticket = $this->tickerService->findTicket($bookingId, $userId, $fingerPrint);
            wp_send_json(onsbks_prepare_result($ticket->getData()));
        }
        catch (\Exception $e) {
            wp_send_json(onsbks_prepare_result(false, $e->getMessage(), false), $e->getCode());
        }
    }
}
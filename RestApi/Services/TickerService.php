<?php

namespace ONSBKS_Slots\RestApi\Services;

use ONSBKS_Slots\Includes\Converter\ProductTemplateConverter;
use ONSBKS_Slots\Includes\Converter\TicketConverter;
use ONSBKS_Slots\Includes\Log;
use ONSBKS_Slots\Includes\Models\BookingModel;
use ONSBKS_Slots\Includes\Models\Ticket;
use ONSBKS_Slots\RestApi\Exceptions\BookingNotFound;
use ONSBKS_Slots\RestApi\Exceptions\InvalidBookingStatusException;
use ONSBKS_Slots\RestApi\Exceptions\TicketNotValid;
use ONSBKS_Slots\RestApi\Repositories\BookingRepository;
use ONSBKS_Slots\RestApi\Repositories\ProductRepository;
use ONSBKS_Slots\RestApi\Repositories\TicketRepository;

class TickerService
{

    private TicketRepository $ticketRepository;
    public BookingRepository $bookingRepository;
    public ProductRepository $productRepository;
    public TicketConverter $ticketConverter;

    public function __construct()
    {
        $this->ticketRepository         = new TicketRepository();
        $this->bookingRepository        = new BookingRepository();
        $this->productRepository        = new ProductRepository();
        $this->ticketConverter = new TicketConverter();
    }

    /**
     * @throws TicketNotValid
     * @throws InvalidBookingStatusException
     * @throws BookingNotFound
     */
    public function verifyTicket(string $bookingId): Ticket
    {
        Log::info("TicketService::verifyTicket started verifying ticket %s", $bookingId);
        $bookingModel = $this->bookingRepository->findById($bookingId, true);

        $order = $this->ticketRepository->findOrderByBookingId($bookingId);

        $ticket = $this->ticketConverter->fromBookingModelAndWCOrder($bookingModel, $order);

        return $ticket;
    }

    /**
     * @throws BookingNotFound
     * @throws InvalidBookingStatusException
     * @throws TicketNotValid
     */
    public function findTicket(string $bookingId, int $userId, string $fingerPrint): Ticket
    {
        $bookingModel = $this->bookingRepository->findBookingByBookingIdAndUserIdOrFingerPrint($bookingId, $userId, $fingerPrint, true);

        $order = $this->ticketRepository->findOrderByBookingId($bookingId);

        $ticket = $this->ticketConverter->fromBookingModelAndWCOrder($bookingModel, $order);

        error_log("[INFO] ticket details :: $ticket");

        return $ticket;
    }
}

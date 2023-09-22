<?php

namespace ONSBKS_Slots\Includes\Converter;

use ONSBKS_Slots\Includes\Models\BookingModel;
use ONSBKS_Slots\Includes\Models\Ticket;

class TicketConverter
{

    public function fromBookingModel(BookingModel $bookingModel): Ticket
    {
        $ticket = new Ticket();
        $ticket->setId($bookingModel->getId());
        $ticket->setProductName($bookingModel->getName());
        $ticket->setSeats($bookingModel->getSeats());
        $ticket->setTotalPrice($bookingModel->getTotalPrice());
        $ticket->setBookingDate($bookingModel->getBookingDate());

        return $ticket;
    }

    public function fromWCOrder(\WC_Order $order): Ticket
    {
        $ticket = new Ticket();
        $ticket->setName($order->get_formatted_billing_full_name());
        $ticket->setEmail($order->get_billing_email());
        $ticket->setPhone($order->get_billing_phone());

        return $ticket;
    }

    public function fromBookingModelAndWCOrder(BookingModel $bookingModel, \WC_Order $order): Ticket
    {
        $ticket = new Ticket();

        $ticket->setId($bookingModel->getId());
        $ticket->setProductName($bookingModel->getName());
        $ticket->setSeats($bookingModel->getSeats());
        $ticket->setTotalPrice($bookingModel->getTotalPrice());
        $ticket->setBookingDate($bookingModel->getBookingDate());
        $ticket->setTemplate($bookingModel->getTemplate());

        $ticket->setName($order->get_formatted_billing_full_name());
        $ticket->setEmail($order->get_billing_email());
        $ticket->setPhone($order->get_billing_phone());

        return $ticket;
    }

}
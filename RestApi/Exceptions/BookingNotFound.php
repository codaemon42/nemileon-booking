<?php

namespace ONSBKS_Slots\RestApi\Exceptions;

class BookingNotFound extends RestException
{
    public function __construct($message = "Booking Not Found")
    {
        parent::__construct($message, 'BOOKING', onsbks_prepare_result(false, $message, false), 404);
    }
}
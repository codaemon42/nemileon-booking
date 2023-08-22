<?php

namespace ONSBKS_Slots\RestApi\Exceptions;

class InvalidBookingStatusException extends \WpOrg\Requests\Exception
{
    public function __construct($message = "Invalid Booking Status")
    {
        parent::__construct($message, 'BOOKING', prepare_result(false, $message, false), 500);
    }
}

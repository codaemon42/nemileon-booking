<?php
namespace ONSBKS_Slots\RestApi\Exceptions;


class NotBookableException extends RestException
{
    public function __construct($message = "Not Bookable")
    {
        parent::__construct($message, 'BOOKING', onsbks_prepare_result(false, $message, false), 500);
    }
}
<?php

namespace ONSBKS_Slots\RestApi\Exceptions;

class NoFingerPrintException extends RestException
{

    public function __construct($message = "No valid finger print detected")
    {
        parent::__construct($message, 'BOOKING', onsbks_prepare_result(false, $message, false), 401);
    }

}
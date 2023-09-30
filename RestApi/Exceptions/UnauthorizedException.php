<?php

namespace ONSBKS_Slots\RestApi\Exceptions;

class UnauthorizedException extends RestException
{
    public function __construct($message = "Unauthorized access")
    {
        parent::__construct($message, 'BOOKING', onsbks_prepare_result(false, $message, false), 400);
    }
}
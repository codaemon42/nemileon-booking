<?php

namespace ONSBKS_Slots\RestApi\Exceptions;

class TicketNotFound extends RestException
{
    public function __construct($message = "Ticket Not Found")
    {
        parent::__construct($message, 'TICKET', onsbks_prepare_result(false, $message, false), 404);
    }
}
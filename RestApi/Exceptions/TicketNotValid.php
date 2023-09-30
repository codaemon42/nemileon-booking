<?php

namespace ONSBKS_Slots\RestApi\Exceptions;

class TicketNotValid extends RestException
{
    public function __construct($message = "Ticket is Invalid")
    {
        parent::__construct($message, 'TICKET', onsbks_prepare_result(false, $message, false), 400);
    }
}
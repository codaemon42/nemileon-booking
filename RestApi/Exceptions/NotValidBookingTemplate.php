<?php

namespace ONSBKS_Slots\RestApi\Exceptions;

class NotValidBookingTemplate extends RestException
{
	public function __construct($message)
	{
		parent::__construct($message, 'BOOKING', onsbks_prepare_result(false, $message, false), 400);
	}
}
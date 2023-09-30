<?php

namespace ONSBKS_Slots\RestApi\Exceptions;

class BookingSlotRecyclingException  extends RestException
{
	public function __construct($message = "Booking Recycling Error")
	{
		parent::__construct($message, 'BOOKING', onsbks_prepare_result(false, $message, false), 500);
	}
}
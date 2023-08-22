<?php

namespace ONSBKS_Slots\Includes\Status;

use ONSBKS_Slots\RestApi\Exceptions\InvalidBookingStatusException;

class BookingStatus
{
    const PENDING_PAYMENT = "PENDING_PAYMENT";
    const ACTIVE = "ACTIVE";
    const COMPLETED = "COMPLETED";
    const CANCELLED = "CANCELLED";


    /**
     * @throws InvalidBookingStatusException
     */
    public static function parse(string $status): string
    {
        $reflectionClass = new \ReflectionClass(__CLASS__);
        $constants = $reflectionClass->getConstants();
        if(!in_array($status, $constants)) throw new InvalidBookingStatusException();

        return $reflectionClass->getConstant($status);
    }
}
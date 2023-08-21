<?php

namespace ONSBKS_Slots\Includes\Status;

class BookingStatus
{
    const PENDING_PAYMENT = "PENDING_PAYMENT";
    const ACTIVE = "ACTIVE";
    const COMPLETED = "COMPLETED";
    const CANCELLED = "CANCELLED";

    public static function isValid(string $status): bool
    {
        $reflectionClass = new \ReflectionClass(__CLASS__);
        $constants = $reflectionClass->getConstants();

        return in_array($status, $constants);
    }
}
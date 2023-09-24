<?php

namespace ONSBKS_Slots\Includes\Cron;

use ONSBKS_Slots\Includes\Cron;
use ONSBKS_Slots\RestApi\Exceptions\BookingProcessException;
use ONSBKS_Slots\RestApi\Exceptions\InvalidBookingStatusException;
use ONSBKS_Slots\RestApi\Services\BookingService;

class AutoCancelCronJob extends CronJob
{
    private BookingService $bookingService;

    public function __construct()
    {
        parent::__construct('onsbks_auto_cancel_cron', Cron::QUARTER);
        add_action($this->cronHook, [$this, 'autoCancelCronHandler']);

        $this->bookingService = new BookingService();
    }

    /**
     * CronJob Handler
     * CronJob Handler for onsbks_auto_cancel_cron cronjob
     * @throws BookingProcessException
     * @throws InvalidBookingStatusException
     */
    public function autoCancelCronHandler(): void
    {
        error_log("[INFO]:: CRON $this->cronHook STARTED FOR CLEANING GARBAGE BOOKINGS");
         $this->bookingService->autoCancelBookings(100, 1);
    }
}

<?php

namespace ONSBKS_Slots\RestApi\Services;

use ONSBKS_Slots\Includes\Models\Analytics;
use ONSBKS_Slots\RestApi\Repositories\AnalyticsRepository;
use ONSBKS_Slots\RestApi\Repositories\IAnalyticsRepository;

class AnalyticsService implements IAnalyticsService
{

    private IAnalyticsRepository $analyticsRepository;


    public function __construct(IAnalyticsRepository $analyticsRepository)
    {
        $this->analyticsRepository =  $analyticsRepository;
    }


    /**
     * @return Analytics[]
     */
    function bookingsSeatAnalyticsGroupedByDate(): array
    {
        return $this->analyticsRepository->findBookingsSeatAnalyticsGroupedByDate();
    }

    function bookingsSeatAnalyticsGroupedByProductId(): array
    {
        // TODO: Implement bookingsSeatAnalyticsGroupedByProductId() method.
    }

    function bookingsSeatMonthlyAnalyticsGroupedByDate(): array
    {
        // TODO: Implement bookingsSeatMonthlyAnalyticsGroupedByDate() method.
    }

    function bookingsSeatAnalyticsGroupedByProductIdAndDate(): array
    {
        // TODO: Implement bookingsSeatAnalyticsGroupedByProductIdAndDate() method.
    }

    public function bookingsSeatAnalyticsGroupedByDateAndStatus(): array
    {
        return $this->analyticsRepository->findBookingsSeatAnalyticsGroupedByDateAndStatus();
    }
}
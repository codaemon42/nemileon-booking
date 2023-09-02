<?php

namespace ONSBKS_Slots\RestApi\Repositories;

use ONSBKS_Slots\Includes\Models\Analytics;


interface IAnalyticsRepository
{
    /**
     * @return Analytics[]
     */
    function findBookingsSeatAnalyticsGroupedByDate(): array;


    /**
     * @return Analytics[]
     */
    function findBookingsSeatAnalyticsGroupedByProductId(): array;


    /**
     * @return Analytics[]
     */
    function findBookingsSeatMonthlyAnalyticsGroupedByDate(): array;


    /**
     * @return Analytics[]
     */
    function findBookingsSeatAnalyticsGroupedByProductIdAndDate(): array;

    /**
     * @return Analytics[]
     */
    public function findBookingsSeatAnalyticsGroupedByDateAndStatus(): array;

}
<?php

namespace ONSBKS_Slots\RestApi\Services;

use ONSBKS_Slots\Includes\Models\Analytics;



interface IAnalyticsService
{

    /**
     * @return Analytics[]
     */
    function bookingsSeatAnalyticsGroupedByDate(): array;


    /**
     * @return Analytics[]
     */
    function bookingsSeatAnalyticsGroupedByProductId(): array;


    /**
     * @return Analytics[]
     */
    function bookingsSeatMonthlyAnalyticsGroupedByDate(): array;


    /**
     * @return Analytics[]
     */
    function bookingsSeatAnalyticsGroupedByProductIdAndDate(): array;

    /**
     * @return Analytics[]
     */
    public function bookingsSeatAnalyticsGroupedByDateAndStatus(): array;

}

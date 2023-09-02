<?php

namespace ONSBKS_Slots\RestApi\Repositories;

use ONSBKS_Slots\Includes\Models\Analytics;

class AnalyticsRepository implements IAnalyticsRepository
{

    public $_wpdb;
    public string $table_name = "";

    public function __construct()
    {
        global $wpdb;
        $this->_wpdb = $wpdb;
        $this->table_name = $this->_wpdb->prefix . "nemileon_bookings";

    }

    /**
     * @return Analytics[]
     */
    public function findBookingsSeatAnalyticsGroupedByDate(): array
    {

        $query =
            $this->_wpdb->prepare(
                "SELECT booking_date as xAxis, SUM(seats) AS yAxis, status as type  FROM $this->table_name GROUP BY xAxis;"
            );

        $analytics = $this->_wpdb->get_results($query, ARRAY_A);
        return $analytics;
    }
    //  WHERE MONTH(booking_date) = 9 AND YEAR(booking_date) = 2023


    /**
     * @return Analytics[]
     */
    public function findBookingsSeatAnalyticsGroupedByProductId(): array
    {

        $query =
            $this->_wpdb->prepare(
                "SELECT product_id as xaxis, SUM(seats) AS yaxis, booking_date as type  FROM `wps_nemileon_bookings` GROUP BY xaxis;"
            );

        $analytics = $this->_wpdb->get_results($query, ARRAY_A);
        return Analytics::List($analytics);
    }

    /**
     * @return Analytics[]
     */
    public function findBookingsSeatMonthlyAnalyticsGroupedByDate(int $month = 9, int $year = 2023 ): array
    {
        $query =
            $this->_wpdb->prepare(
                "SELECT booking_date as xaxis, SUM(seats) AS yaxis, product_id as type  FROM `wps_nemileon_bookings` WHERE MONTH(booking_date) = %d AND YEAR(booking_date) = %d GROUP BY xaxis;",
                $month,
                $year
            );

        $analytics = $this->_wpdb->get_results($query, ARRAY_A);
        return Analytics::List($analytics);
    }

    /**
     * @return Analytics[]
     */
    public function findBookingsSeatAnalyticsGroupedByProductIdAndDate(): array
    {

        $query =
            $this->_wpdb->prepare(
                "SELECT booking_date as xaxis, SUM(seats) AS yaxis, product_id as type  FROM `wps_nemileon_bookings` GROUP BY xaxis, type;"
            );

        $analytics = $this->_wpdb->get_results($query, ARRAY_A);
        return Analytics::List($analytics);
    }

    public function findBookingsSeatAnalyticsGroupedByDateAndStatus(): array
    {
        $query =
            $this->_wpdb->prepare(
                "SELECT booking_date as xAxis, SUM(seats) AS yAxis, status as type  FROM $this->table_name GROUP BY type, xAxis;"
            );

        $analytics = $this->_wpdb->get_results($query, ARRAY_A);
        return $analytics;
    }
}
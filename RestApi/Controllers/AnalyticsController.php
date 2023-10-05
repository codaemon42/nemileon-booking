<?php

namespace ONSBKS_Slots\RestApi\Controllers;

use ONSBKS_Slots\RestApi\Services\IAnalyticsService;


class AnalyticsController
{
    private IAnalyticsService $analyticsService;

    public function __construct(IAnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    public function findBookingAnalyticsByDate(\WP_REST_Request $req): void
    {
        try{
            $analytics = $this->analyticsService->bookingsSeatAnalyticsGroupedByDate();
            wp_send_json(onsbks_prepare_result($analytics));
        }
        catch (\Exception $e) {
            wp_send_json(onsbks_prepare_result(false, $e->getMessage(), false), 500);
        }
    }

    public function findBookingAnalyticsByDateAndStatus(\WP_REST_Request $req): void
    {
        try{
            $analytics = $this->analyticsService->bookingsSeatAnalyticsGroupedByDateAndStatus();
            wp_send_json(onsbks_prepare_result($analytics));
        }
        catch (\Exception $e) {
            wp_send_json(onsbks_prepare_result(false, $e->getMessage(), false), 500);
        }
    }

}

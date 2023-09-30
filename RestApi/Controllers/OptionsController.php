<?php

namespace ONSBKS_Slots\RestApi\Controllers;

use ONSBKS_Slots\Includes\Models\Settings;
use ONSBKS_Slots\RestApi\Services\OptionsService;
use WP_REST_Request;
use Exception;

class OptionsController
{

    private OptionsService $optionsService;

    public function __construct()
    {
        $this->optionsService = new OptionsService();
    }

    public function setOption(WP_REST_Request $request): void
    {
        try {
            $body = $request->get_json_params();
            $option_name = $body["key"];
            $option_value = $body['value'];
            $response = $this->optionsService->updateOption($option_name, $option_value);
            wp_send_json(onsbks_prepare_result($response));
        } catch (Exception $error) {
            wp_send_json(onsbks_prepare_result(false, $error->getMessage(), false), 500);
        }
    }

    public function getOption(WP_REST_Request $request): void
    {
        try {
            $key = 'key';
            $query_params = $request->get_query_params();
            $response = $this->optionsService->getOption($query_params[$key]);
            wp_send_json(onsbks_prepare_result($response));
        } catch (Exception $error) {
            wp_send_json(onsbks_prepare_result(false, $error->getMessage(), false), 500);
        }
    }

    public function findSettings(WP_REST_Request $request): void
    {
        try {
            $response = $this->optionsService->getSettings();
            wp_send_json(onsbks_prepare_result($response));
        } catch (Exception $error) {
            wp_send_json(onsbks_prepare_result(false, $error->getMessage(), false), 500);
        }
    }

    public function saveSettings(WP_REST_Request $request): void
    {
        try {
            $settings = $request->get_json_params();
            $response = $this->optionsService->saveSettings($settings);
            wp_send_json(onsbks_prepare_result($response));
        } catch (Exception $error) {
            wp_send_json(onsbks_prepare_result(false, $error->getMessage(), false), 500);
        }
    }
}

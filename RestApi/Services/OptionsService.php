<?php

namespace ONSBKS_Slots\RestApi\Services;

use ONSBKS_Slots\Includes\Models\Settings;
use ONSBKS_Slots\RestApi\Exceptions\OptionNotFound;
use ONSBKS_Slots\RestApi\Exceptions\OptionUpdateException;

class OptionsService
{

    const SETTINGS_KEY = 'nml_settings';


    /**
     * @throws OptionNotFound
     */
    public function getSettings()
    {
        $settings = get_option(self::SETTINGS_KEY);
        if(!$settings) {
            throw new OptionNotFound();
        }
        return $settings;
    }

    /**
     * @throws OptionUpdateException
     */
    public function saveSettings($settings): array
    {
        $dbSettings =  new Settings(get_option(self::SETTINGS_KEY));
        $updatedSettings = array_merge($dbSettings->getData(), $settings);
        $settings = update_option(self::SETTINGS_KEY, $updatedSettings);
        if(!$settings) {
            throw new OptionUpdateException("Can not be updated");
        }

        return get_option(self::SETTINGS_KEY);
    }


    public function getOption(String $key)
    {
        return get_option($key);
    }


    /**
     * @throws OptionUpdateException
     */
    public function updateOption(String $key, $value): bool
    {
        $response = update_option($key, $value);
        if(!$response) {
            throw new OptionUpdateException("Can not be updated");
        }

        return $response;
    }

}

<?php

namespace ONSBKS_Slots\RestApi\Services;

use ONSBKS_Slots\Includes\State;
use ONSBKS_Slots\RestApi\Exceptions\OptionUpdateException;

class OptionsService
{

    public function getSettings()
    {
        return State::$SETTINGS;
    }


    public function saveSettings($settings): array
    {
        State::setSettingsState($settings);
        return State::$SETTINGS;
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

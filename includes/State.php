<?php

namespace ONSBKS_Slots\Includes;

use ONSBKS_Slots\Includes\Models\Settings;

class State
{

    const SETTINGS_KEY = 'nml_settings';

    public static $SETTINGS;

    public function __construct()
    {
        $this->initState();
    }

    public function initState(): void
    {
        $settings = get_option(self::SETTINGS_KEY);
        if( !$settings ){
            $settings = new Settings();
            update_option(self::SETTINGS_KEY, $settings->getData());
        } else {
            $settings = new Settings($settings);
        }
        self::$SETTINGS = $settings->getData();
    }

    public static function setSettingsState(array $settings): void
    {
        $dbSettings =  new Settings(get_option(self::SETTINGS_KEY));
        $updatedSettings = array_merge($dbSettings->getData(), $settings);
        $settings = update_option(self::SETTINGS_KEY, $updatedSettings);
        if(!$settings) {
            error_log("[ERROR] :: SETTINGS UPSERT ERROR [State::setSettingsState]");
        }
        self::$SETTINGS = get_option(self::SETTINGS_KEY);
    }
}

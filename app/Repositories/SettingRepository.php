<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;

class SettingRepository extends BaseRepository
{
    public static $settingsPrefix = 'wp_base_plugin_';
    public static function getSetting($key)
    {
        return get_option(self::$settingsPrefix.$key, '');

    }

    public static function addSetting($key, $value)
    {
        return add_option(self::$settingsPrefix.$key, $value);
    }

    public static function updateSetting($key, $value)
    {
        return update_option(self::$settingsPrefix.$key, $value);   
    }

}

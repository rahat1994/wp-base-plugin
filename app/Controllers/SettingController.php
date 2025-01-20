<?php

namespace App\Controllers;

use App\Traits\CanValidateInputs;

if (!defined('ABSPATH')) {
    exit;
}

use App\Controllers\BaseController;
use App\Traits\CanInteractWithSettings;

class SettingController extends BaseController
{
    use CanInteractWithSettings;
    use CanValidateInputs;
    public function index()
    {
        if (!current_user_can('manage_options')) {
            return wp_send_json_error('Unauthorized', 401);
        }

        $inputs = $this->validateAndSanitize([
            'settingKeys' => 'string'
        ]);

        try {
            $settingKeys = $inputs['settingKeys'];
            $args = explode(',', $settingKeys);

            $settings = $this->getSettings($args);
            return wp_send_json_success([
                'success' => true,
                'data' => wp_json_encode($settings),
            ]);
        } catch (\Throwable $th) {
            wp_send_json_error([
                'success' => false,
                'message' => $th->getMessage(),
            ], 400);
        }
    }

    public function store()
    {
        if (!current_user_can('manage_options')) {
            return wp_send_json_error('Unauthorized', 401);
        }

        $input = $this->validateAndSanitize([
            'settings' => 'string',
        ]);

        try {
            $settings = $input['settings'];
            $args = json_decode($settings, true);

            $settings = $this->getSettings(array_keys($args));

            foreach ($settings as $key => $value) {
                if (isset($args[$key]) && $args[$key] !== null) {
                    $this->updateSettings([$key => $args[$key]]);
                }
            }

            if ($this->addSettings($args)) {
                wp_send_json_success([
                    'success' => true,
                    'settings' => 'Settings added successfully!',
                ]);
            }

            throw new \Exception("Settings not added", 1);
        } catch (\Exception $e) {
            wp_send_json_error([
                'success' => false,
                'settings' => $e->getMessage(),
            ]);
        }
    }
}

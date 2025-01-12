<?php

namespace App\Controllers;

if (!defined('ABSPATH')) {
    exit;
}

use App\Controllers\BaseController;
use App\Traits\CanInteractWithSettings;

class SettingController extends BaseController
{
    use CanInteractWithSettings;
    public function index()
    {

        if (!isset($_GET['settingKeys'])) {
            wp_send_json_success([
                'success' => false,
                'feed'    => 'settingKeys is required!',
            ]);
        }
        $_GET['settingKeys'] = sanitize_text_field($_GET['settingKeys']);
        $args = explode(',', $_GET['settingKeys']);

        $settings = $this->getSettings($args);
        return wp_send_json_success([
            'success' => true,
            'data'    => json_encode($settings),
        ]);
    }

    public function store()
    {
        if (!isset($_POST['settings'])) {
            wp_send_json_success([
                'success' => false,
                'settings'    => 'settings field is required!',
            ]);
        }
        try {
            $_POST['settings'] = wp_unslash($_POST['settings']);
            $args = json_decode(sanitize_text_field($_POST['settings']), true);

            $settings = $this->getSettings(array_keys($args));

            foreach ($settings as $key => $value) {
                if (isset($args[$key]) && $args[$key] !== null) {
                    $this->updateSettings([$key => $args[$key]]);
                }
            }

            if ($this->addSettings($args)) {
                wp_send_json_success([
                    'success' => true,
                    'settings'    => 'Settings added successfully!',
                ]);
            }

            throw new \Exception("Settings not added", 1);
        } catch (\Exception $e) {
            wp_send_json_success([
                'success' => false,
                'settings'    => $e->getMessage(),
            ]);
        }
    }
}

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
    public function index(){

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

    public function updateSetting(){

    }

    public function store(){
        if (!isset($_POST['settings'])) {
            wp_send_json_success([
                'success' => false,
                'settings'    => 'settings field is required!',
            ]);
        }
        try {
            $_POST['settings'] = wp_unslash($_POST['settings']);
            $args = json_decode(sanitize_text_field($_POST['settings']), true);

            error_log(print_r($args, true));
            if($this->addSettings($args)){
                wp_send_json_success([
                    'success' => true,
                    'settings'    => 'Settings added successfully!',
                ]);
            } else {
                wp_send_json_success([
                    'success' => false,
                    'settings'    => 'Failed to add settings!',
                ]);
            }
        } catch (\Exception $e) {
            wp_send_json_success([
                'success' => false,
                'settings'    => $e->getMessage(),
            ]);
        }
        
        
    }
}
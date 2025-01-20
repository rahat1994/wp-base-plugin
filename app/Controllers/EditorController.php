<?php

namespace App\Controllers;

use App\Traits\CanValidateInputs;

if (!defined('ABSPATH')) {
    exit;
}

class EditorController
{
    use CanValidateInputs;

    public function getFeed()
    {
        if (!current_user_can('manage_options')) {
            return wp_send_json_error('Unauthorized', 401);
        }

        $inputs = $this->validateAndSanitize([
            'feed' => 'string',
        ]);

        try {
            $feed = $inputs['feed'];

            return wp_send_json_success([
                'success' => true,
                'feed' => $feed,
            ]);
        } catch (\Throwable $th) {
            return wp_send_json_error([
                'success' => false,
                'message' => $th->getMessage(),
            ], 400);
        }
    }

    public function saveFeedConfig()
    {
        if (!current_user_can('manage_options')) {
            return wp_send_json_error('Unauthorized', 401);
        }

        if (!isset($_POST['feed']) && !isset($_POST['config'])) {
            return wp_send_json_error('Missing field', 400);
        }

        $inputs = $this->validateAndSanitize([
            'feed' => 'string',
            'config' => 'array',
        ]);

        $feed = $inputs['feed'];
        $config = $inputs['config'];

        try {
            update_post_meta($feed, '_wprb_feed_config', $config);

            return wp_send_json_success([
                'success' => true,
                'feed' => $feed,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return wp_send_json_error([
                'success' => false,
                'feed' => $feed,
                'message' => $th->getMessage(),
            ], 400);
        }
    }

    public function getFeedConfig()
    {
        if (!current_user_can('manage_options')) {
            return wp_send_json_error('Unauthorized', 401);
        }

        $inputs = $this->validateAndSanitize([
            'feed' => 'string',
        ]);

        try {
            $feed = $inputs['feed'];
            $config = get_post_meta($feed, '_wprb_feed_config', true);

            if ($config === '') {
                $config = wprb_feed_default_config();
            }

            return wp_send_json_success([
                'success' => true,
                'feed' => $feed,
                'config' => $config,
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return wp_send_json_error([
                'success' => false,
                'feed' => $feed,
                'message' => $th->getMessage(),
            ], 400);
        }
    }
}

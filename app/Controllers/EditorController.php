<?php

namespace App\Controllers;

if (!defined('ABSPATH')) {
	exit;
}

class EditorController {

    public function getFeed(){
        if (!current_user_can('manage_options')) {
            return wp_send_json_error('Unauthorized', 401);
        }

        if (!isset($_POST['feed'])) {
            return wp_send_json_error('Missing feed attribute', 400);
        }

        $feed = sanitize_text_field($_POST['feed']);

        return wp_send_json_success([
            'success' => true,
            'feed'    => $feed,
        ]);
    }

    public function saveFeedConfig(){
        if (!current_user_can('manage_options')) {
            return wp_send_json_error('Unauthorized', 401);
        }

        if (!isset($_POST['feed']) && !isset($_POST['config'])) {
            return wp_send_json_error('Missing field', 400);
        }

        $feed = sanitize_text_field($_POST['feed']);
        $config = sanitize_text_field($_POST['config']);

        update_post_meta($feed, '_wprb_feed_config', $config);

        return wp_send_json_success([
            'success' => true,
            'feed'    => $feed,
        ]);
    }

    public function getFeedConfig(){
        if (!current_user_can('manage_options')) {
            return wp_send_json_error('Unauthorized', 401);
        }

        if (!isset($_GET['feed'])) {
            return wp_send_json_error('Missing feed attribute', 400);
        }

        try {
            $feed = sanitize_text_field($_GET['feed']);
            $config = get_post_meta($feed, '_wprb_feed_config', true);

            if($config === ''){
                $config = wprb_feed_default_config();
            }
    
            return wp_send_json_success([
                'success' => true,
                'feed'    => $feed,
                'config'  => $config,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return wp_send_json_success([
                'success' => false,
                'feed'    => $feed,
                'message'  => $th->getMessage(),
            ]);
        }
    }
}

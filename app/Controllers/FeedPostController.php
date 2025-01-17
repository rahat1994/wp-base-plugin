<?php

namespace App\Controllers;

if (!defined('ABSPATH')) {
	exit;
}

class FeedPostController extends BaseController {

    public function index(){

        if (empty($_GET['feed_id'])) {
            wp_send_json_error([
                'success' => false,
                'feed'    => __('Feed Id are required!', 'wp-base-plugin'),
            ]);
        }

        wp_send_json_success([
            'success' => true,
            'feed'    => [
                "Hello",
                "There"
            ],
        ]);

    }
}

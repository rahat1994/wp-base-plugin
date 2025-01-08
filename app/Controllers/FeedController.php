<?php

namespace App\Controllers;

if (!defined('ABSPATH')) {
	exit;
}

use App\Controllers\BaseController;
use App\Traits\CanInteractWithFeedCPT;

class FeedController extends BaseController {

    use CanInteractWithFeedCPT;

    public function index(){

        try {
            $feeds = $this->get();

            

            wp_send_json_success([
                'success' => true,
                'feeds'    => json_encode($feeds),
            ]);
        } catch (\Exception $e) {
            wp_send_json_success([
                'success' => false,
                'feeds'    => $e->getMessage(),
            ]);
        }
    }
    
}

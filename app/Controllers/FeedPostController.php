<?php

namespace App\Controllers;

use App\Traits\CanValidateInputs;

if (!defined('ABSPATH')) {
    exit;
}

class FeedPostController extends BaseController
{
    use CanValidateInputs;
    public function index()
    {
        $inputs = $this->validateAndSanitize([
            'feed' => 'string',
        ]);

        $feed = $inputs['feed'];



        wp_send_json_success([
            'success' => true,
            'feed' => [
                "Hello",
                "There"
            ],
        ]);

    }
}

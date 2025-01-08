<?php

namespace App\Controllers;

use App\Traits\CanInteractWithUsers;

if (!defined('ABSPATH')) {
    exit;
}

use App\Controllers\BaseController;

class UsersController extends BaseController {

    use CanInteractWithUsers;
    public function index(){

        try {
            $authors = $this->getAll();
            wp_send_json_success([
                'success' => true,
                'users'    => json_encode($authors),
            ]);
        } catch (\Exception $e) {
            wp_send_json_success([
                'success' => false,
                'users'    => $e->getMessage(),
            ]);
        }
    }
    
}
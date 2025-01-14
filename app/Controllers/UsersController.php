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
            $authors = $this->getAllUsers();
            wp_send_json_success([
                'success' => true,
                'users'    => wp_json_encode($authors),
            ]);
        } catch (\Exception $e) {
            wp_send_json_error([
                'success' => false,
                'users'    => $e->getMessage(),
            ]);
        }
    }
    
}
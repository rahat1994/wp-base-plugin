<?php

//declare(strict_types=1);

namespace App\Validators;

if (!defined('ABSPATH')) {
    exit;
}

class UrlValidator
{
    public function __construct()
    {
        add_action('admin_notices', array($this, 'addAdminNotice'));
    }

    public function validate($url)
    {
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            return true;
        } else {
            if(is_admin()){
                set_transient('my_meta_box_error', 'The custom field cannot be empty.', 30);
                return false;
            }
        }
        return false;
    }
    

    public function addAdminNotice()
    {
        error_log('this is an eror');
        $message = get_transient('my_meta_box_error');
        if ($message) {
            echo '<div class="notice notice-error is-dismissible"><p>' . esc_html($message) . '</p></div>';
            // Delete the transient to ensure the notice is shown only once
            delete_transient('my_meta_box_error');
        }
    }
}

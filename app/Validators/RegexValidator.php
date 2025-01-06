<?php

//declare(strict_types=1);

namespace App\Validators;

if (!defined('ABSPATH')) {
    exit;
}


class RegexValidator
{

    public function validate(string $value, string $pattern = "/^https:\/\/www\.reddit\.com\/r\/[a-zA-Z0-9_-]+$/", string $message = ''): bool
    {
        if (preg_match($pattern, $value)) {
            return true;
        }

        if (is_admin()) {
            set_transient('my_meta_box_error', 'The custom field cannot be empty.', 30);
            add_action('admin_notices', array($this, 'addAdminNotice'), 10);
        }

        return false;
    }


    public function addAdminNotice()
    {
        $message = get_transient('my_meta_box_error');
        if ($message) {
            echo '<div class="notice notice-error is-dismissible"><p>' . esc_html($message) . '</p></div>';
            // Delete the transient to ensure the notice is shown only once
            delete_transient('my_meta_box_error');
        }
    }
}

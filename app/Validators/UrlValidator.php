<?php

//declare(strict_types=1);

namespace App\Validators;

if (!defined('ABSPATH')) {
    exit;
}

class UrlValidator
{
    public function validate($url)
    {
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            return true;
        }
        
        return false;
    }
}

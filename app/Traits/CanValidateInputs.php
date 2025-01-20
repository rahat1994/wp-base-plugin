<?php

namespace App\Traits;

trait CanValidateInputs
{
    public function validateAndSanitize(array $keys)
    {
        $sanitizedData = [];

        foreach ($keys as $key => $type) {
            if (isset($_POST[$key])) {
                $value = wp_unslash($_POST[$key]);
            } elseif (isset($_GET[$key])) {
                $value = wp_unslash($_GET[$key]);
            } else {
                wp_send_json_error([
                    'success' => false,
                    'message' => sprintf(__('The key %s is required!', 'wp-base-plugin'), $key),
                ], 400);
            }

            switch ($type) {
                case 'url':
                    $sanitizedData[$key] = sanitize_url($value);
                    break;
                case 'email':
                    $sanitizedData[$key] = sanitize_email($value);
                    break;
                case 'boolean':
                    $sanitizedData[$key] = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
                    if (is_null($sanitizedData[$key])) {
                        wp_send_json_error([
                            'success' => false,
                            'message' => sprintf(__('The key %s must be a boolean!', 'wp-base-plugin'), $key),
                        ], 400);
                    }
                    break;
                default:
                    $sanitizedData[$key] = sanitize_text_field($value);
                    break;
            }
        }

        return $sanitizedData;
    }
}
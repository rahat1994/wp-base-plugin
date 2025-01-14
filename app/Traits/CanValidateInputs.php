<?php

namespace App\Traits;

trait CanValidateInputs
{
    /**
     * Validate if the given value is a valid email.
     *
     * @param string $email
     * @return bool
     */
    public function isValidEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Validate if the given value is a valid URL.
     *
     * @param string $url
     * @return bool
     */
    public function isValidUrl(string $url): bool
    {
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }

    /**
     * Validate if the given value is a valid text.
     * Here we assume valid text is a non-empty string.
     *
     * @param string $text
     * @return bool
     */
    public function isValidText(string $text): bool
    {
        return !empty($text) && is_string($text);
    }
}
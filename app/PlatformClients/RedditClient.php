<?php

//declare(strict_types=1);

namespace App\PlatformClients;

use App\Traits\CanInteractWithSettings;

if (!defined('ABSPATH')) {
    exit;
}

class RedditClient
{
    use CanInteractWithSettings;

    // Your Reddit API credentials
    protected $clientId;
    protected $clientSecret;
    protected $userAgent = 'rahatsplugin/1.0 by Rahat Baksh';

    public function getAccessToken(){

        $settings = $this->getSettings(['client_id', 'client_secret']);
        $this->clientId = $settings['client_id'];
        $this->clientSecret = $settings['client_secret'];
        
        // Step 1: Get access token
        $authUrl = 'https://www.reddit.com/api/v1/access_token';
        $authHeaders = [
            'Authorization: Basic ' . base64_encode("$this->clientId:$this->clientSecret"),
            'User-Agent: ' . $this->userAgent
        ];
        $authPostFields = http_build_query([
            'grant_type' => 'client_credentials'
        ]);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $authUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $authPostFields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $authHeaders);
        $response = curl_exec($ch);
        curl_close($ch);

        if ($response === false) {
            die('Error fetching access token.');
        }

        $tokenData = json_decode($response, true);
        if (!isset($tokenData['access_token'])) {
            return false;
        }

        $accessToken = $tokenData['access_token'];

        return $accessToken;
    }

    public function getFeedData($subRedditName = 'ecommerce', $feedType = 'new'){
        $accessToken = $this->getAccessToken();

        if(!$accessToken){
            return false;
        }

        $feedUrl = "https://oauth.reddit.com/r/$subRedditName/$feedType";
        $feedHeaders = [
            'Authorization: Bearer ' . $accessToken,
            'User-Agent: ' . $this->userAgent
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $feedUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $feedHeaders);
        $feedResponse = curl_exec($ch);
        curl_close($ch);

        if ($feedResponse === false) {
            die('Error fetching subreddit feed.');
        }

        $feedData = json_decode($feedResponse, true);

        return $feedData;
    }

}

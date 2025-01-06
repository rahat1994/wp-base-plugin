<?php

//declare(strict_types=1);

namespace App\PlatformClients;

if (!defined('ABSPATH')) {
    exit;
}

class RedditClient
{

    // Your Reddit API credentials
    protected $clientId = 'DiDzaaj1Nr9npMtqVbec2g';
    protected $clientSecret = 'on3b9vhp8_xJ3OCOUPb3WCoY2AKTNw';
    protected $userAgent = 'rahatsplugin/1.0 by Rahat Baksh';

    public function getAccessToken(){
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
            die('Error: Invalid response for access token.');
        }

        $accessToken = $tokenData['access_token'];

        return $accessToken;
    }

    public function getFeedData(){
        $accessToken = $this->getAccessToken();

        $subreddit = 'ecommerce';
        $feedUrl = "https://oauth.reddit.com/r/$subreddit/new";
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

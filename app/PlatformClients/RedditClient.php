<?php

//declare(strict_types=1);

namespace App\PlatformClients;

use App\Repositories\PlatformCallCacheRepository;
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

    public function getSubredditHTML($feedId, $subRedditName = 'ecommerce', $feedType = 'new', $shouldBeCached = true)
    {

        if ($shouldBeCached || $shouldBeCached === 'true') {
            error_log('Cached');
            $cachedData = PlatformCallCacheRepository::getCache($feedId);
            error_log("get cached");
            error_log(wp_json_encode($cachedData));
        } else {
            error_log('Not cached');
            $accessToken = $this->getAccessToken();
            return $this->makeDataCall($subRedditName, $feedType, $accessToken);
        }


        if (!$cachedData || empty($cachedData) || count($cachedData) === 0) {

            $accessToken = $this->getAccessToken();
            error_log("Access token");
            error_log($accessToken);
            if (!$accessToken) {
                return false;
            }


            $data = $this->makeDataCall($subRedditName, $feedType, $accessToken);
            error_log(
                "Data"
            );
            error_log(wp_json_encode($data));

            if (!$data['feedData']['error'] === 400) {
                error_log("Error in feed data");
                do_action('wp_base_plugin/feed_error', $feedId, $data);
                return false;
            }

            if ($shouldBeCached || $shouldBeCached === 'true') {
                PlatformCallCacheRepository::createCache($feedId, 'feedData', wp_json_encode($data));
            }
            return $data;
            // PlatformCallCacheRepository::createCache($feedId, 'feedData', wp_json_encode($data));
        } else {
            $data = json_decode($cachedData['value'], true);
            $feedData = $data['feedData'];
            $subRedditInfo = $data['subRedditInfo'];

            return [
                'feedData' => $feedData,
                'subRedditInfo' => $subRedditInfo
            ];
        }

    }

    public function makeDataCall($subRedditName = 'ecommerce', $feedType = 'new', $accessToken)
    {
        $feedData = $this->getFeedData($subRedditName, $feedType, $accessToken);
        $subRedditInfo = $this->getSubredditInfo($subRedditName, $accessToken);

        return [
            'feedData' => $feedData,
            'subRedditInfo' => $subRedditInfo
        ];
    }

    public function getAccessToken()
    {

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
            return false;
        }

        $tokenData = json_decode($response, true);
        if (!isset($tokenData['access_token'])) {
            return false;
        }

        $accessToken = $tokenData['access_token'];

        return $accessToken;
    }

    public function getFeedData($subRedditName = 'ecommerce', $feedType = 'new', $accessToken)
    {
        $accessToken = $this->getAccessToken();

        if (!$accessToken) {
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

    public function getSubredditInfo($subRedditName = 'ecommerce', $accessToken)
    {

        $subRedditInfoUrl = "https://oauth.reddit.com/r/$subRedditName/about";
        $subRedditInfoHeaders = [
            'Authorization: Bearer ' . $accessToken,
            'User-Agent: ' . $this->userAgent
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $subRedditInfoUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $subRedditInfoHeaders);
        $subRedditInfoResponse = curl_exec($ch);
        curl_close($ch);

        if ($subRedditInfoResponse === false) {
            // die('Error fetching subreddit info.');
            return false;
        }

        $subRedditInfo = json_decode($subRedditInfoResponse, true);

        return $subRedditInfo;
    }



}

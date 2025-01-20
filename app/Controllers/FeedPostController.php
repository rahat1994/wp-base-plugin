<?php

namespace App\Controllers;

use App\Repositories\PlatformCallCacheRepository;
use App\Traits\CanInteractWithFeedCPT;
use App\Traits\CanValidateInputs;

if (!defined('ABSPATH')) {
    exit;
}

class FeedPostController extends BaseController
{
    use CanValidateInputs;
    use CanInteractWithFeedCPT;

    public function index()
    {
        $inputs = $this->validateAndSanitize([
            'feed_id' => 'string',
            'page' => 'int',
        ], ['page']);

        $feed = $inputs['feed_id'];
        $page = $inputs['page'];
        $cachedData = PlatformCallCacheRepository::getLatestcacheForFeedId($feed);
        $feedConfig = $this->getFeedConfig($feed);

        if (!$cachedData) {
            return wp_send_json_error([
                'success' => false,
                'message' => 'No cached data found',
            ]);
        }

        // $this->processFeedData($cachedData);
        $page = (($page - 1) < 0) ? 0 : $page - 1;
        wp_send_json_success([
            'success' => true,
            'data' => $this->processFeedData($cachedData, $page),
            'config' => $feedConfig
        ]);

    }

    private function processFeedData($cachedData, $offset = 0)
    {
        $data = json_decode($cachedData['value'], true);

        $posts = $data['feedData']['data']['children'];
        $batches = array_chunk($posts, 10);
        $currentBatch = $batches[$offset] ?? [];

        if (empty($currentBatch)) {
            return [
                'posts' => [],
                'subRedditInfo' => $this->subredditMetaAsArray($data['subRedditInfo']),
            ];
        }

        $formatedPosts = $this->formatPosts($currentBatch);
        $subRedditInfo = $this->subredditMetaAsArray($data['subRedditInfo']);

        return [
            'posts' => $formatedPosts,
            'subRedditInfo' => $subRedditInfo,
        ];
    }

    private function subredditMetaAsArray($feedData)
    {
        return [
            'title' => $feedData['data']['title'],
            'description' => $feedData['data']['description'],
            'banner_background_color' => $feedData['data']['subscribers'],
            'key_color' => $feedData['data']['key_color']
        ];
    }

    private function formatPosts($posts)
    {

        $feed = [];

        foreach ($posts as $key => $value) {
            $feed[] = [
                'created_utc' => $value['data']['created_utc'],
                'num_comments' => $value['data']['num_comments'],
                'score' => $value['data']['score'],
                'title' => $value['data']['title'],
                'url' => $value['data']['url'],
                'selftext' => $value['data']['selftext'],
                'selftext_html' => $value['data']['selftext_html'],
            ];
        }

        return $feed;
    }
}

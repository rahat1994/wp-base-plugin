<?php

namespace App\Handlers;

use App\PlatformClients\RedditClient;
use App\Traits\CanInteractWithFeedCacheOption;
use App\Traits\CanInteractWithFeedCPT;

if (!defined('ABSPATH')) {
    exit;
}


class FeedCreationEventHandler
{
    use CanInteractWithFeedCacheOption;
    use CanInteractWithFeedCPT;

    public RedditClient $redditClient;

    public function __construct(RedditClient $redditClient)
    {
        $this->redditClient = $redditClient;
    }

    public function boot()
    {
        add_action('wp_base_plugin/feed_created', [$this, 'feedCreationHook'], 9, 1);
    }

    public function feedCreationHook($feedId)
    {
        try {

            $rssUrl = $this->getFeedMeta((int) $feedId, '_wprb_subreddit_url');
            error_log("Inside event handler");
            error_log($rssUrl);

            if (!$rssUrl) {
                return;
            }

            $feedType = $this->getFeedMeta($feedId, '_wprb_feed_type');
            $shouldBeCached = true;

            error_log($feedType);
            $feedType = $feedType ? $feedType : 'new';
            $shouldBeCached = $shouldBeCached ? $shouldBeCached : 'true';
            $subRedditName = $this->getSubredditName($rssUrl);

            $subredditData = $this->redditClient->getSubredditHTML($feedId, $subRedditName, $feedType, $shouldBeCached);

        } catch (\Throwable $e) {

            $this->setCacheRegenerationCronErrorOption($e->getMessage());
        }

    }


}
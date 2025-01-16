<?php

namespace App\Cron;

use App\PlatformClients\RedditClient;
use App\Repositories\PlatformCallCacheRepository;
use App\Traits\CanInteractWithFeedCacheOption;
use App\Traits\CanInteractWithFeedCPT;

class SingleFeedCacheRegenerationCron
{
    use CanInteractWithFeedCPT;
    use CanInteractWithFeedCacheOption;
    public RedditClient $redditClient;
    public function __construct(RedditClient $redditClient){
        $this->redditClient = $redditClient;
    }
    public function boot()
    {
        add_action('wp_base_plugin_feed_regeneration_hook', [$this, 'feedRegenerationCallback']);
        add_filter( 'cron_schedules', [$this, 'addCronFiveMinuteInterval'] );

        if ( ! wp_next_scheduled( 'wp_base_plugin_feed_regeneration_hook' ) ) {
            wp_schedule_event( time(), 'five_minutes', 'wp_base_plugin_feed_regeneration_hook' );
        }
    }

    public function addCronFiveMinuteInterval( $schedules ) { 
        $schedules['five_minutes'] = array(
            'interval' => 300,
            'display'  => esc_html__( 'Every Five minutes' ), );
        return $schedules;
    }

    public function feedRegenerationCallback()
    {
        $feedIds = $this->getCacheRegenerationFeedIds();
        if(empty($feedIds)){
            return;
        }

        $feedId = array_shift($feedIds);

        $rssUrl = $this->getFeedMeta($feedId );

        if(!$rssUrl){
            return '<div class="wp-base-notice">'.__('Invalid URL', 'wp-base-plugin').'</div>';
        }
        
        $feedType = $this->getFeedMeta($feedId, '_wprb_feed_type');
        $shouldBeCached = true;

        $feedType = $feedType ? $feedType : 'new';
        $shouldBeCached = $shouldBeCached ? $shouldBeCached : 'true';
        $subRedditName = $this->getSubredditName($rssUrl);

        $subredditData = $this->redditClient->getSubredditHTML($feedId,$subRedditName, $feedType , $shouldBeCached);
        error_log("From Single Cron: ");
        error_log(wp_json_encode($subredditData));
        error_log("From Single Cron end: ");
        if($subredditData){
            $this->setCacheRegenerationFeedIds($feedIds);
        }

    }
}
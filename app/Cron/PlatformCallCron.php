<?php

namespace App\Cron;

use App\Repositories\PlatformCallCacheRepository;
use App\Traits\CanInteractWithFeedCacheOption;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class PlatformCallCron
 *
 * This class is responsible for deteecting the expired or about to expire caches
 * and sets an option to regenerate them. another cron will be picking the option up 
 * one by one and regenerate the cache.
 * 
 *
 * @package WPBasePlugin\App\Cron
 */

class PlatformCallCron
{
    use CanInteractWithFeedCacheOption;
    public function boot()
    {
        add_action('wp_base_plugin_platform_call_cron', [$this, 'platformCallCron']);
        if (!wp_next_scheduled('wp_base_plugin_platform_call_cron')) {
            wp_schedule_event(time(), 'hourly', 'wp_base_plugin_platform_call_cron');
        }
    }

    public function platformCallCron()
    {
        try {
            $feedIds = PlatformCallCacheRepository::getCachesThatAreExpired() ?: [];
            $feedIdsAlreadyInQueue = $this->getCacheRegenerationFeedIds() ?: [];

            $onlyFeedIds = array_map(function ($feed) {
                return $feed['post_id'];
            }, array_values($feedIds));
            error_log(wp_json_encode($onlyFeedIds));

            $feedIds = array_unique(array_merge((array) $onlyFeedIds, (array) $feedIdsAlreadyInQueue));
            $this->setCacheRegenerationFeedIds($feedIds);
        } catch (\Throwable $e) {
            //throw $th;
            error_log($e);
            $this->setCacheRegenerationCronErrorOption($e->getMessage());
        }

    }


}
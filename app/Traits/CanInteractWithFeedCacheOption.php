<?php

namespace App\Traits;

if (!defined('ABSPATH')) {
    exit;
}

trait CanInteractWithFeedCacheOption
{
    public function getCacheRegenerationFeedIds()
    {
        return get_option('wp_base_plugin_cache_regeneration_feed_ids');
    }

    public function setCacheRegenerationFeedIds($feedIds)
    {
        return update_option('wp_base_plugin_cache_regeneration_feed_ids', $feedIds);
    }

    public function setCacheRegenerationCronErrorOption($errorMessage)
    {
        return update_option('wp_base_plugin_cron_error', $errorMessage);
    }
}
<?php

namespace App\Repositories;

class PlatformCallCacheRepository extends BaseRepository
{

    public static function getCache($feedId)
    {
        global $wpdb;

        $tableName = $wpdb->prefix . 'wp_base_feed_cache';

        $currentTime = current_time('mysql');
        $sql = $wpdb->prepare("SELECT * FROM $tableName WHERE post_id = %d AND expiration > %s ORDER BY id DESC LIMIT 1", $feedId, $currentTime);

        $result = $wpdb->get_row($sql, ARRAY_A);

        if ($result === null) {
            return false;
        }

        return $result;

    }

    public static function getLatestcacheForFeedId($feedId)
    {
        global $wpdb;

        $tableName = $wpdb->prefix . 'wp_base_feed_cache';

        $sql = $wpdb->prepare("SELECT * FROM $tableName WHERE post_id = %d ORDER BY id DESC LIMIT 1", $feedId);

        $result = $wpdb->get_row($sql, ARRAY_A);

        return $result;

    }

    public static function createCache($feedId, $name, $value, $platform = 'reddit')
    {
        global $wpdb;

        $tableName = $wpdb->prefix . 'wp_base_feed_cache';

        $currentTime = current_time('mysql');
        $expiration = date('Y-m-d H:i:s', strtotime($currentTime . ' + 1 hour'));
        $data = [
            'platform' => $platform,
            'post_id' => $feedId,
            'name' => $name,
            'value' => $value,
            'expiration' => $expiration,
            'created_at' => $currentTime,
            'updated_at' => $currentTime,
        ];

        $wpdb->insert($tableName, $data);
    }

    public static function deleteCache($feedId)
    {
        global $wpdb;

        $tableName = $wpdb->prefix . 'wp_base_feed_cache';

        $wpdb->delete($tableName, ['post_id' => $feedId]);
    }

    public static function getCachesThatAreExpiredOrGoingToExpire($in = 30)
    {
        global $wpdb;

        $tableName = $wpdb->prefix . 'wp_base_feed_cache';

        $currentTime = current_time('mysql');
        $futureTime = date('Y-m-d H:i:s', strtotime($currentTime . " + $in minutes"));
        $sql = $wpdb->prepare("SELECT post_id FROM $tableName WHERE expiration < %s OR expiration BETWEEN %s AND %s", $currentTime, $currentTime, $futureTime);

        $results = $wpdb->get_results($sql, ARRAY_A);

        return $results;
    }

    public static function getCachesThatAreExpired()
    {
        global $wpdb;

        $tableName = $wpdb->prefix . 'wp_base_feed_cache';

        $currentTime = current_time('mysql');
        $sql = $wpdb->prepare("SELECT post_id FROM $tableName WHERE expiration < %s", $currentTime);

        $results = $wpdb->get_results($sql, ARRAY_A);

        return $results;
    }
}
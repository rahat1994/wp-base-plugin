<?php

namespace App\Common;

if (!defined('ABSPATH')) {
    exit;
}

use App\Interfaces\Commons\PluginActivatorInterface;

class PluginActivator implements PluginActivatorInterface
{
    public function migrateDatabases($networkWide) {
        global $wpdb;
        if ($networkWide) {
            // Retrieve all site IDs from this network (WordPress >= 4.6 provides easy to use functions for that).
            $site_ids = function_exists('get_sites') && function_exists('get_current_network_id') 
            ? get_sites(array('fields' => 'ids', 'network_id' => get_current_network_id())) 
            : $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs WHERE site_id = $wpdb->siteid;");
            
            // Install the plugin for all these sites.
            foreach ($site_ids as $site_id) {
            switch_to_blog($site_id);
            $this->migrate();
            restore_current_blog();
            }
        } else {
            $this->migrate();
        }
    }

    public function migrate() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $table_name = $wpdb->prefix . 'wp_base_feed_cache';
        $sql = "CREATE TABLE $table_name (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            platform varchar(255) DEFAULT NULL,
            post_id bigint(20) UNSIGNED NOT NULL,
            name varchar(255) DEFAULT NULL,
            value longtext DEFAULT NULL,
            expiration timestamp NULL DEFAULT NULL,
            failed_count int DEFAULT 0,
            created_at timestamp NULL DEFAULT NULL,
            updated_at timestamp NULL DEFAULT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}

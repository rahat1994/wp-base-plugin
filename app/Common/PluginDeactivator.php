<?php

namespace App\Common;

if (!defined('ABSPATH')) {
    exit;
}

class PluginDeactivator {

    public function deactivate($networkWide){
        global $wpdb;
        if ($networkWide) {
            // Retrieve all site IDs from this network (WordPress >= 4.6 provides easy to use functions for that).
            $site_ids = function_exists('get_sites') && function_exists('get_current_network_id') 
            ? get_sites(array('fields' => 'ids', 'network_id' => get_current_network_id())) 
            : $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs WHERE site_id = $wpdb->siteid;");
            
            // Install the plugin for all these sites.
            foreach ($site_ids as $site_id) {
            switch_to_blog($site_id);
            $this->unscheduleCRON();
            restore_current_blog();
            }
        } else {
            $this->unscheduleCRON();
        }
        
    }

    public function unscheduleCRON(){

    }
}

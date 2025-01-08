<?php

namespace App\Traits;

if (!defined('ABSPATH')) {
    exit;
}

trait CanInteractWithFeedCPT
{
    
    public function get(int $limit = 10, int $offset = 0, array $filters = []){
        $args = [
            'post_type' => 'subreddit_feed',
            'post_status' => 'publish',
            'numberposts' => $limit
        ];
        $posts = get_posts($args);
        return $posts;
    }

    public function getByID($id){

    }

    public function create($data){

    }

    public function update($id, $data){

    }

    public function delete($id){

    }

    public function getAll(){

    }



}

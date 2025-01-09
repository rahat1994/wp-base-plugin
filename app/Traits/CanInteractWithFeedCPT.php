<?php

namespace App\Traits;

use App\Repositories\FeedRepository;

if (!defined('ABSPATH')) {
    exit;
}

trait CanInteractWithFeedCPT
{
    
    public function get(int $limit = 10, int $page = 0, array $filters = []){
        $args = [
            'posts_per_page' => $limit,
            'offset' => ($page -1) * $limit,
            'orderby' => 'id',
            'order' => 'DESC',
        ];

        $posts = FeedRepository::getPosts($args);
        return $posts;
    }

    public function getTotalNumberOfPosts(){
        $total = FeedRepository::getTotalNumberOfPosts();
        return $total;
    }

    public function getByID($id){

    }

    public function create($data){
        $post_id = FeedRepository::createPost($data);
        return $post_id;
    }

    public function updateFeed($id, $data){
       return FeedRepository::updatePost($id, $data);
    }

    public function delete($id){

    }

    public function getAll(){

    }



}

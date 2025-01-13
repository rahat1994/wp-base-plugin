<?php

namespace App\Traits;

use App\Repositories\FeedRepository;

if (!defined('ABSPATH')) {
    exit;
}

trait CanInteractWithFeedCPT
{
    
    public function get(int $limit = 10, int $page = 0, string $titleFilter = '', array $filters = []){
        $args = [
            'posts_per_page' => $limit,
            'offset' => ($page -1) * $limit,
            'orderby' => 'id',
            'order' => 'DESC',
            's' => $titleFilter,
        ];

        $posts = FeedRepository::getPosts($args);
        return $posts;
    }

    public function getTotalNumberOfPosts(string $titleFilter = ''){
        $total = FeedRepository::getTotalNumberOfPosts($titleFilter);
        return $total;
    }

    public function getByID($id){
        $post = FeedRepository::getPostByID($id);
        return $post;
    }

    public function create($data){
        return FeedRepository::createPost($data);
        
    }

    public function updateFeed($id, $data){
       return FeedRepository::updatePost($id, $data);
    }

    public function deleteFeedPost($id){
        return FeedRepository::deletePost($id);
    }

    public function getAll(){

    }



}

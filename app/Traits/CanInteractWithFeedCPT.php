<?php

namespace App\Traits;

use App\Repositories\FeedRepository;

if (!defined('ABSPATH')) {
    exit;
}

trait CanInteractWithFeedCPT
{
    
    public function get(int $limit = 10, int $offset = 0, array $filters = []){
        $posts = FeedRepository::get_posts();
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

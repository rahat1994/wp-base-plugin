<?php

namespace App\Traits;

use App\Repositories\UserRepository;

if (!defined('ABSPATH')) {
    exit;
}

trait CanInteractWithUsers
{
    
    public function get(int $limit = 10, int $offset = 0, array $filters = []){
        $users = UserRepository::get_users();
        return $users;
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

        $args = [
            'number' => -1,
            'orderby' => 'ID',
            'order' => 'DESC',
        ];
        $users = UserRepository::get_users($args);
        return $users;
    }

}
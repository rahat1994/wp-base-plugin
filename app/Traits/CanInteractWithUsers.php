<?php

namespace App\Traits;

use App\Repositories\UserRepository;

if (!defined('ABSPATH')) {
    exit;
}

trait CanInteractWithUsers
{
    public function getUsers(int $limit = 10, int $offset = 0, array $filters = [])
    {
        $users = UserRepository::getUsers();
        return $users;
    }

    public function getUserByID($id)
    {
    }

    public function getUserByEmail($email)
    {
        return UserRepository::getUserByEmail($email);
    }

    public function createUser($data)
    {
    }

    public function updateUser($id, $data)
    {
    }

    public function deleteUser($id)
    {
    }

    public function getAllUsers()
    {

        $args = [
            'number' => -1,
            'orderby' => 'ID',
            'order' => 'DESC',
        ];
        $users = UserRepository::getUsers($args);
        return $users;
    }
}

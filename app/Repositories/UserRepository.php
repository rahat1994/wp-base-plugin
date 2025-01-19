<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;

class UserRepository extends BaseRepository
{
    public static function getUsers($args = [])
    {
        $default_args = [
            'number' => 10,
            'orderby' => 'ID',
            'order' => 'DESC',
        ];

        $query_args = wp_parse_args($args, $default_args);

        $user_query = new \WP_User_Query($query_args);

        $users = [];
        $results = $user_query->get_results();
        if (!empty($results)) {
            foreach ($results as $user) {
                $users[] = [
                    'username' => $user->user_login,
                    'email'    => $user->user_email,
                    'role'     => implode(', ', $user->roles),
                ];
            }
        }

        return $users;
    }

    public function getUserById($user_id)
    {
        $user = get_userdata($user_id);

        if ($user) {
            return [
                'id'       => $user->ID,
                'username' => $user->user_login,
                'email'    => $user->user_email,
                'role'     => implode(', ', $user->roles),
            ];
        }

        return null;
    }

    public static function getUserByEmail($email)
    {
        $args = [
            'search' => '*' . $email . '*',
            'search_columns' => ['user_email'],
            'number' => 1,
        ];

        $user_query = new \WP_User_Query($args);

        $results = $user_query->get_results();
        if (!empty($results)) {
            $user = $results[0];
            return [
                'id'       => $user->ID,
                'username' => $user->user_login,
                'email'    => $user->user_email,
                'role'     => implode(', ', $user->roles),
            ];
        }

        return null;
    }

    public function getUsersByMeta($meta_key, $meta_value)
    {
        $args = [
            'meta_key'   => $meta_key,
            'meta_value' => $meta_value,
        ];

        $user_query = new \WP_User_Query($args);

        return $user_query->get_results();
    }
}

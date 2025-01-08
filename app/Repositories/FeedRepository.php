<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;

class FeedRepository extends BaseRepository
{
    public static $postType = 'subreddit_feed';

    public static function get_posts($args = [])
    {
        $default_args = [
            'post_type'      => self::$postType,   // Adjust post type as needed
            'posts_per_page' => 10,
            'orderby'        => 'id',
            'order'          => 'DESC',
        ];

        $query_args = wp_parse_args($args, $default_args);

        $query = new \WP_Query($query_args);

        $posts = [];

        if ($query->have_posts()) {
            while ($query->have_posts()) {
            $query->the_post();
            $posts[] = [
                'id'     => get_the_ID(),
                'title'  => get_the_title(),
                'status' => get_post_status(),
                'author' => get_the_author(),
            ];
            }
            wp_reset_postdata();
        }

        return $posts;
    }

    public function get_post_by_id($post_id)
    {
        $args = [
            'p'         => $post_id,
            'post_type' => 'post',
        ];

        $query = new \WP_Query($args);

        return $query->have_posts() ? $query->posts[0] : null;
    }

    public function get_posts_by_meta($meta_key, $meta_value)
    {
        $args = [
            'meta_query' => [
                [
                    'key'   => $meta_key,
                    'value' => $meta_value,
                ],
            ],
            'post_type' => 'post',
        ];

        return new \WP_Query($args);
    }
}


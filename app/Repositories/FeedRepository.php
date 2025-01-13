<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;

class FeedRepository extends BaseRepository
{
    public static $postType = 'subreddit_feed';
    public static $publicMetas = [
        '_wprb_subreddit_url',
        '_wprb_feed_type',
        '_wprb_should_be_cached',
    ];

    public static function getPosts($args = [])
    {
        
        $default_args = [
            'post_type'      => self::$postType,   // Adjust post type as needed
            'posts_per_page' => 10,
            'orderby'        => 'id',
            'order'          => 'DESC',
            'offset'         => 0,
            's' => '',
        ];

        $query_args = wp_parse_args($args, $default_args);

        $query = new \WP_Query($query_args);

        $posts = [];
        
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $publicMetas = self::getAllPublicMetaValuesOfThePost(get_the_ID());
                $posts[] = array_merge([
                    'id'     => get_the_ID(),
                    'title'  => get_the_title(),
                    'status' => get_post_status(),
                    'author' => get_the_author(),
                    
                ], $publicMetas);
            }
            wp_reset_postdata();
        }

        return $posts;
    }

    public static function getAllPublicMetaValuesOfThePost($id){
        // 'subreddit_url'  => get_post_meta(get_the_ID(), '_wprb_subreddit_url', true),
        $metas = get_post_meta($id);
        $publicMetas = [];

        foreach (self::$publicMetas as $value) {
            if($value === '_wprb_should_be_cached'){
                $publicMetas['should_be_cached'] = $metas[$value][0] === 'true' ? true : false;
                continue;
            }
            else if($value === '_wprb_feed_type'){
                $publicMetas['feed_type'] = $metas[$value][0];
                continue;
            }
            else if($value === '_wprb_subreddit_url'){
                $publicMetas['subreddit_url'] = $metas[$value][0];
                continue;
            }
        }

        return $publicMetas;
    }

    public static function getPostByID($post_id)
    {
        $args = [
            'p'         => $post_id,
            'post_type' => self::$postType,
        ];

        $query = new \WP_Query($args);

        $post = $query->have_posts() ? $query->posts[0] : null;

        return $post;
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

    public static function createPost($data)
    {
        $post_id = wp_insert_post([
            'post_title'   => $data['post_title'],
            'post_status'  => 'publish',
            'post_type'    => self::$postType,
        ]);

        if ($post_id) {
            if (isset($data['meta']) && is_array($data['meta'])) {
                foreach ($data['meta'] as $key => $value) {
                    update_post_meta($post_id, $key, $value);
                }
            }
        }

        return $post_id;
    }

    public static function updatePost($post_id, $data)
    {
        $post_id = wp_update_post([
            'ID'           => $post_id,
            'post_title'   => $data['post_title'],
            'post_status'  => 'publish',
            
        ]);

        if ($post_id) {
            if (isset($data['meta']) && is_array($data['meta'])) {
                foreach ($data['meta'] as $key => $value) {
                    update_post_meta($post_id, $key, $value);
                }
            }
        }

        return $post_id;
    }

    public static function getTotalNumberOfPosts(string $titleFilter = '')
    {
        $query = new \WP_Query([
            'post_type'      => self::$postType,
            'posts_per_page' => -1,
            's' =>$titleFilter
        ]);

        return $query->found_posts;
    }

    public static function deletePost($post_id)
    {
        return wp_delete_post($post_id, true);
    }

    public static function changePostStatus($post_id, $status)  {
        return wp_update_post([
            'ID' => $post_id,
            'post_status' => $status,
        ]);
    }
}


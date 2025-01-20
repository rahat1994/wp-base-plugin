<?php

namespace App\Controllers;

use App\Traits\CanInteractWithUsers;
use App\Traits\CanValidateInputs;

if (!defined('ABSPATH')) {
    exit;
}

use App\Controllers\BaseController;
use App\Traits\CanInteractWithFeedCPT;

class FeedController extends BaseController
{

    use CanInteractWithFeedCPT;
    use CanInteractWithUsers;
    use CanValidateInputs;

    public array $acceptedFeedTypes = [
        'new',
        'hot',
        'top',
        'rising',
        'controversial',
    ];

    public array $acceptedStatuses = [
        'publish',
        'draft',
        'trash',
    ];

    public function index()
    {
        $inputs = $this->validateAndSanitize([
            'page' => 'integer',
            'filterTitle' => 'string',
        ]);

        $page = $inputs['page'];
        $filterTitle = $inputs['filterTitle'];
        try {

            $page = isset($page) &&
                (0 !== $page) ?
                (int) $page : 1;

            $feeds = $this->get(10, $page, $filterTitle);
            $total = $this->getTotalNumberOfPosts($filterTitle);

            wp_send_json_success([
                'success' => true,
                'feeds' => wp_json_encode($feeds),
                'total' => $total,
                'page' => $page,
                'getpage' => $_GET['page'],
            ]);
        } catch (\Exception $e) {
            wp_send_json_error([
                'success' => false,
                'feeds' => $e->getMessage(),
            ], 400);
        }
    }

    public function show()
    {
        $feedId = $this->validateAndSanitize([
            'id' => 'string',
        ])['id'];

        try {
            $feed = $this->getByID($feedId);

            wp_send_json_success([
                'success' => true,
                'feed' => wp_json_encode($feed),
            ]);
        } catch (\Exception $e) {
            wp_send_json_error([
                'success' => false,
                'feed' => $e->getMessage(),
            ], 400);
        }
    }

    public function store()
    {
        $inputs = $this->validateAndSanitize([
            'title' => 'string',
            'feed_type' => 'string',
            'subreddit_url' => 'url',
            'should_be_cached' => 'boolean',
        ]);

        $title = $inputs['title'];
        $feedType = in_array($inputs['feed_type'], $this->acceptedFeedTypes, true) ? $inputs['feed_type'] : 'new';
        $subredditURL = $inputs['subreddit_url'];
        $shouldBeCached = true;

        // TODO: Further validation.

        try {
            $data = [
                'post_title' => $title,
                'post_status' => 'publish',
                'post_type' => 'subreddit_feed',
                'meta' => [
                    '_wprb_subreddit_url' => $subredditURL,
                    '_wprb_feed_type' => $feedType,
                    '_wprb_should_be_cached' => $shouldBeCached,
                ]
            ];

            $feed = $this->createFeed($data);

            if (!$feed) {
                wp_send_json_error([
                    'success' => false,
                    'feed' => __('Feed could not be created!', 'wp-base-plugin'),
                ], 400);
            }

            wp_send_json_success([
                'success' => true,
                'feed' => __('Feed edited successfully!', 'wp-base-plugin'),
            ]);
        } catch (\Exception $e) {
            wp_send_json_error(
                [
                    'success' => false,
                    'feed' => $e->getMessage(),
                ],
                400
            );
        }
    }

    public function update()
    {
        $inputs = $this->validateAndSanitize([
            'id' => 'string',
            'title' => 'string',
            'feed_type' => 'string',
            'subreddit_url' => 'url',
            'should_be_cached' => 'boolean',
        ]);

        $id = $inputs['id'];
        $title = $inputs['title'];
        $feedType = in_array($inputs['feed_type'], $this->acceptedFeedTypes, true) ? $inputs['feed_type'] : 'new';
        $subredditURL = $inputs['subreddit_url'];
        $shouldBeCached = true;

        try {

            $data = [
                'post_title' => $title,
                'meta' => [
                    '_wprb_subreddit_url' => $subredditURL,
                    '_wprb_feed_type' => $feedType,
                    '_wprb_should_be_cached' => $shouldBeCached,
                ]
            ];

            $feed = $this->updateFeed($id, $data);

            wp_send_json_error([
                'success' => true,
                'feed' => __('Feed updated successfully!', 'wp-base-plugin'),
            ]);
        } catch (\Exception $e) {
            wp_send_json_error([
                'success' => false,
                'feed' => $e->getMessage(),
            ], 400);
        }

    }
    public function delete()
    {
        $id = $this->validateAndSanitize([
            'id' => 'string',
        ])['id'];

        try {
            $this->deleteFeedPost($id);

            wp_send_json_success([
                'success' => true,
                'feed' => __('Feed deleted successfully!', 'wp-base-plugin'),
            ]);
        } catch (\Exception $e) {
            wp_send_json_error(
                [
                    'success' => false,
                    'feed' => $e->getMessage(),
                ],
                400
            );
        }

    }

    public function changeStatus()
    {
        $inputs = $this->validateAndSanitize([
            'id' => 'string',
            'status' => 'string',
        ]);

        $id = $inputs['id'];
        $status = $inputs['status'];

        if (in_array($status, $this->acceptedStatuses, true) === false) {
            wp_send_json_error(
                [
                    'success' => false,
                    'feed' => __('Status is not accepted!', 'wp-base-plugin'),
                ],
                400
            );
        }

        try {
            $this->changeFeedStatus($id, $status);

            wp_send_json_success([
                'success' => true,
                'feed' => __('Feed status changed successfully!', 'wp-base-plugin'),
            ]);
        } catch (\Exception $e) {
            wp_send_json_error([
                'success' => false,
                'feed' => $e->getMessage(),
            ], 400);
        }
    }

    public function regenerateCache()
    {
        $inputs = $this->validateAndSanitize([
            'id' => 'string',
        ]);

        $id = $inputs['id'];

        try {
            // $this->regenerateFeedCache($id);
            sleep(3);
            wp_send_json_success([
                'success' => true,
                'feed' => __('Cache regenerated successfully!', 'wp-base-plugin'),
            ]);
        } catch (\Exception $e) {
            wp_send_json_error([
                'success' => false,
                'feed' => $e->getMessage(),
            ], 400);
        }
    }

}

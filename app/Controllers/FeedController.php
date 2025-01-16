<?php

namespace App\Controllers;

use App\Traits\CanInteractWithUsers;

if (!defined('ABSPATH')) {
	exit;
}

use App\Controllers\BaseController;
use App\Traits\CanInteractWithFeedCPT;

class FeedController extends BaseController {

    use CanInteractWithFeedCPT;
    use CanInteractWithUsers;

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

    public function index(){

        try {

            $page = isset($_GET['page']) && 
            (0 !== sanitize_text_field(wp_unslash($_GET['page']))) ? 
            (int) sanitize_text_field(wp_unslash($_GET['page'])) : 1;
                    
            if(isset($_GET['filterTitle'])){
                $filterTitle = sanitize_text_field(wp_unslash($_GET['filterTitle']));
            }else{
                $filterTitle = '';
            }

            $feeds = $this->get(10,$page,$filterTitle);
            $total = $this->getTotalNumberOfPosts($filterTitle);

            wp_send_json_success([
                'success' => true,
                'feeds'    => wp_json_encode($feeds),
                'total'    => $total,
                'page'     => $page,
                'getpage'  => $_GET['page'],
            ]);
        } catch (\Exception $e) {
            wp_send_json_error([
                'success' => false,
                'feeds'    => $e->getMessage(),
            ]);
        }
    }

    public function show(){

        if (!isset($_GET['id'])) {
            wp_send_json_error([
                'success' => false,
                'feed'    => __('Feed ID is required!', 'wp-base-plugin'),
            ]);
        }

        $feedId = sanitize_text_field($_GET['id']);

        try {
            $feed = $this->getByID($feedId);

            wp_send_json_success([
                'success' => true,
                'feed'    => wp_json_encode($feed),
            ]);
        } catch (\Exception $e) {
            wp_send_json_error([
                'success' => false,
                'feed'    => $e->getMessage(),
            ]);
        }
    }

    public function validateCreateFormInput(){
        if (empty($_POST['title']) || empty($_POST['subreddit_url']) || empty($_POST['feed_type']) || empty($_POST['should_be_cached'])) {
            wp_send_json_error([
                'success' => false,
                'feed'    => __('All fields are required!', 'wp-base-plugin'),
            ]);
        }

        if(in_array($_POST['feed_type'], $this->acceptedFeedTypes, true) === false){
            wp_send_json_error([
                'success' => false,
                'feed'    => __('Feed type is not accepted!', 'wp-base-plugin'),
            ]);
        }

        if($_POST['should_be_cached'] !== 'true' && $_POST['should_be_cached'] !== 'false'){
            wp_send_json_error([
                'success' => false,
                'feed'    => __('Should be cached field is not accepted!', 'wp-base-plugin'),
            ]);

        }
        return true;
    }

    public function validateEditFormInput(){
        if (empty($_POST['id']) || empty($_POST['title']) || empty($_POST['subreddit_url']) || empty($_POST['feed_type']) || empty($_POST['should_be_cached'])) {
            wp_send_json_error([
                'success' => false,
                'feed'    => __('All fields are required!', 'wp-base-plugin'),
            ]);
        }

        if(in_array($_POST['feed_type'], $this->acceptedFeedTypes, true) === false){
            wp_send_json_error([
                'success' => false,
                'feed'    => __('Feed type is not accepted!', 'wp-base-plugin'),
            ]);
        }

        if($_POST['should_be_cached'] !== 'true' && $_POST['should_be_cached'] !== 'false'){
            wp_send_json_error([
                'success' => false,
                'feed'    => __('Should be cached field is not accepted!', 'wp-base-plugin'),
            ]);

        }
        return true;
    }

    public function store(){

        $this->validateCreateFormInput();

        $title = sanitize_text_field(wp_unslash($_POST['title']));
        $feedType = sanitize_text_field(wp_unslash($_POST['feed_type']));
        $subredditURL = sanitize_url(wp_unslash($_POST['subreddit_url']));
        $shouldBeCached = true;

        // TODO: Further validation.

        try {

            $data = [
                'post_title' => $title,
                'post_status' => 'publish',
                'post_type' => 'subreddit_feed',
                'meta'=> [
                    '_wprb_subreddit_url' => $subredditURL,
                    '_wprb_feed_type' => $feedType,
                    '_wprb_should_be_cached' => $shouldBeCached,
                ]
            ];

            $feed = $this->createFeed($data);

            if(!$feed){
                wp_send_json_error([
                    'success' => false,
                    'feed'    => __('Feed could not be created!', 'wp-base-plugin'),
                ]);
            }

            wp_send_json_success([
                'success' => true,
                'feed'    => __('Feed edited successfully!', 'wp-base-plugin'),
            ]);
        } catch (\Exception $e) {
            wp_send_json_error([
                'success' => false,
                'feed'    => $e->getMessage(),
            ]);
        }
    }

    public function update(){
        $this->validateEditFormInput();
        $id = sanitize_text_field($_POST['id']);
        $title = sanitize_text_field($_POST['title']);
        $feedType = sanitize_text_field($_POST['feed_type']);
        $subredditURL = sanitize_url($_POST['subreddit_url']);
        $shouldBeCached = true;

        try {

            $data = [
                'post_title' => $title,
                'meta'=> [
                    '_wprb_subreddit_url' => $subredditURL,
                    '_wprb_feed_type' => $feedType,
                    '_wprb_should_be_cached' => $shouldBeCached,
                ]
            ];

            $feed = $this->updateFeed($id, $data);

            wp_send_json_error([
                'success' => true,
                'feed'    => __('Feed updated successfully!', 'wp-base-plugin'),
            ]);
        } catch (\Exception $e) {
            wp_send_json_error([
                'success' => false,
                'feed'    => $e->getMessage(),
            ]);
        }

    }
    public function delete(){
        if (empty($_POST['id'])) {
            wp_send_json_success([
                'success' => false,
                'feed'    => __('POST ID is required!', 'wp-base-plugin'),
            ]);
        }

        $id = sanitize_text_field($_POST['id']);

        try {
            $this->deleteFeedPost($id);

            wp_send_json_success([
                'success' => true,
                'feed'    => __('Feed deleted successfully!', 'wp-base-plugin'),
            ]);
        } catch (\Exception $e) {
            wp_send_json_error([
                'success' => false,
                'feed'    => $e->getMessage(),
            ]);
        }

    }

    public function changeStatus(){
        if (empty($_POST['id']) || empty($_POST['status'])) {
            wp_send_json_error([
                'success' => false,
                'feed'    => __('post id and status are required!', 'wp-base-plugin'),
            ]);
        }

        $id = sanitize_text_field($_POST['id']);
        $status = sanitize_text_field($_POST['status']);

        if(in_array($status, $this->acceptedStatuses, true) === false){
            wp_send_json_error([
                'success' => false,
                'feed'    => __('Status is not accepted!', 'wp-base-plugin'),
            ]);
        }

        try {
            $this->changeFeedStatus($id, $status);

            wp_send_json_success([
                'success' => true,
                'feed'    => __('Feed status changed successfully!', 'wp-base-plugin'),
            ]);
        } catch (\Exception $e) {
            wp_send_json_error([
                'success' => false,
                'feed'    => $e->getMessage(),
            ]);
        }
    }

    public function regenerateCache(){
        if (empty($_POST['id'])) {
            wp_send_json_success([
                'success' => false,
                'feed'    => __('post id is required!', 'wp-base-plugin'),
            ]);
        }

        $id = sanitize_text_field($_POST['id']);

        try {
            // $this->regenerateFeedCache($id);
            sleep(3);
            wp_send_json_success([
                'success' => true,
                'feed'    => __('Cache regenerated successfully!', 'wp-base-plugin'),
            ]);
        } catch (\Exception $e) {
            wp_send_json_error([
                'success' => false,
                'feed'    => $e->getMessage(),
            ]);
        }
    }
    
}

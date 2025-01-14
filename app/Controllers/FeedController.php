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
        'popular',
        'gold',
        'default'
    ];

    public array $acceptedStatuses = [
        'publish',
        'draft',
        'trash',
    ];

    public function index(){

        try {
            $_GET['page'] = sanitize_text_field($_GET['page']);
            $page = isset($_GET['page']) && 
                    (0 !== $_GET['page']) ? 
                    (int) $_GET['page'] : 1;
                    
            if(isset($_GET['filterTitle'])){
                $filterTitle = sanitize_text_field($_GET['filterTitle']);
            }else{
                $filterTitle = '';
            }

            $feeds = $this->get(10,$page,$filterTitle);
            $total = $this->getTotalNumberOfPosts($filterTitle);

            wp_send_json_success([
                'success' => true,
                'feeds'    => json_encode($feeds),
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
                'feed'    => 'Feed ID is required!',
            ]);
        }

        $_GET['id'] = sanitize_text_field($_GET['id']);

        try {
            $feed = $this->getByID($_GET['id']);

            wp_send_json_success([
                'success' => true,
                'feed'    => json_encode($feed),
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
                'feed'    => 'All fields are required!',
            ]);
        }

        if(in_array($_POST['feed_type'], $this->acceptedFeedTypes, true) === false){
            wp_send_json_error([
                'success' => false,
                'feed'    => 'Feed type is not accepted!',
            ]);
        }

        if($_POST['should_be_cached'] !== 'true' && $_POST['should_be_cached'] !== 'false'){
            wp_send_json_error([
                'success' => false,
                'feed'    => 'Should be cached field is not accepted!',
            ]);

        }
        return true;
    }

    public function validateEditFormInput(){
        if (empty($_POST['id']) || empty($_POST['title']) || empty($_POST['subreddit_url']) || empty($_POST['feed_type']) || empty($_POST['should_be_cached'])) {
            wp_send_json_error([
                'success' => false,
                'feed'    => 'All fields are required!',
            ]);
        }

        if(in_array($_POST['feed_type'], $this->acceptedFeedTypes, true) === false){
            wp_send_json_error([
                'success' => false,
                'feed'    => 'Feed type is not accepted!',
            ]);
        }

        if($_POST['should_be_cached'] !== 'true' && $_POST['should_be_cached'] !== 'false'){
            wp_send_json_error([
                'success' => false,
                'feed'    => 'Should be cached field is not accepted!',
            ]);

        }
        return true;
    }

    public function store(){

        $_POST['title'] = sanitize_text_field($_POST['title']);
        $_POST['feed_type'] = sanitize_text_field($_POST['feed_type']);
        $_POST['subreddit_url'] = sanitize_url($_POST['subreddit_url']);
        $_POST['should_be_cached'] = sanitize_text_field($_POST['should_be_cached']);

        $this->validateCreateFormInput();

        // TODO: Further validation.

        try {

            $data = [
                'post_title' => $_POST['title'],
                'post_status' => 'publish',
                'post_type' => 'subreddit_feed',
                'meta'=> [
                    '_wprb_subreddit_url' => $_POST['subreddit_url'],
                    '_wprb_feed_type' => $_POST['feed_type'],
                    '_wprb_should_be_cached' => $_POST['should_be_cached'],
                ]
            ];

            $feed = $this->create($data);

            if(!$feed){
                wp_send_json_error([
                    'success' => false,
                    'feed'    => 'Feed could not be created!',
                ]);
            }

            wp_send_json_success([
                'success' => true,
                'feed'    => 'Feed created successfully!',
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

        try {

            $data = [
                'post_title' => $_POST['title'],
                'meta'=> [
                    '_wprb_subreddit_url' => $_POST['subreddit_url'],
                    '_wprb_feed_type' => $_POST['feed_type'],
                    '_wprb_should_be_cached' => $_POST['should_be_cached'],
                ]
            ];

            $feed = $this->updateFeed($_POST['id'], $data);

            wp_send_json_error([
                'success' => true,
                'feed'    => 'Feed updated successfully!',
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
                'feed'    => 'POST ID is required!',
            ]);
        }

        $_POST['id'] = sanitize_text_field($_POST['id']);

        try {
            $this->deleteFeedPost($_POST['id']);

            wp_send_json_success([
                'success' => true,
                'feed'    => 'Feed deleted successfully!',
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
                'feed'    => 'post id and status are required!',
            ]);
        }

        $_POST['id'] = sanitize_text_field($_POST['id']);
        $_POST['status'] = sanitize_text_field($_POST['status']);

        if(in_array($_POST['status'], $this->acceptedStatuses, true) === false){
            wp_send_json_error([
                'success' => false,
                'feed'    => 'Status is not accepted!',
            ]);
        }

        try {
            $this->changeFeedStatus($_POST['id'], $_POST['status']);

            wp_send_json_success([
                'success' => true,
                'feed'    => 'Feed status changed successfully!',
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
                'feed'    => 'post id is required!',
            ]);
        }

        $_POST['id'] = sanitize_text_field($_POST['id']);

        try {
            $this->regenerateFeedCache($_POST['id']);

            wp_send_json_success([
                'success' => true,
                'feed'    => 'Cache regenerated successfully!',
            ]);
        } catch (\Exception $e) {
            wp_send_json_error([
                'success' => false,
                'feed'    => $e->getMessage(),
            ]);
        }
    }
    
}

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
            wp_send_json_success([
                'success' => false,
                'feeds'    => $e->getMessage(),
            ]);
        }
    }

    public function show(){

        if (!isset($_GET['id'])) {
            wp_send_json_success([
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
            wp_send_json_success([
                'success' => false,
                'feed'    => $e->getMessage(),
            ]);
        }
    }

    public function store(){

        $_POST['title'] = sanitize_text_field($_POST['title']);
        $_POST['subreddit_url'] = sanitize_url($_POST['subreddit_url']);
        $_POST['author_email'] = sanitize_email($_POST['author_email']);

        if (empty($_POST['title']) || empty($_POST['subreddit_url']) || empty($_POST['author_email'])) {
            wp_send_json_success([
                'success' => false,
                'feed'    => 'All fields are required!',
            ]);
        }

        // TODO: Further validation.

        try {
            $user = $this->getUserByEmail($_POST['email']);

            $data = [
                'post_title' => $_POST['title'],
                'post_status' => 'publish',
                'post_type' => 'subreddit_feed',
                'post_author' => $user['id'],
                'meta'=> [
                    '_wprb_subreddit_url' => $_POST['subreddit_url'],
                ]
            ];

            $feed = $this->create($data);

            wp_send_json_success([
                'success' => true,
                'feed'    => 'Feed created successfully!',
            ]);
        } catch (\Exception $e) {
            wp_send_json_success([
                'success' => false,
                'feed'    => $e->getMessage(),
            ]);
        }
    }

    public function update(){
        
        if (!isset($_POST['id'], $_POST['title'], $_POST['subreddit_url'])) {
            wp_send_json_success([
            'success' => false,
            'feed'    => 'All fields are required!',
            ]);
        }

        $_POST['id'] = sanitize_text_field($_POST['id']);
        $_POST['title'] = sanitize_text_field($_POST['title']);
        $_POST['subreddit_url'] = sanitize_url($_POST['subreddit_url']);

        if (empty($_POST['title']) || empty($_POST['subreddit_url'])) {
            wp_send_json_success([
                'success' => false,
                'feed'    => 'All fields are required!',
            ]);
        }

        try {

            $data = [
                'post_title' => $_POST['title'],
                'meta'=> [
                    '_wprb_subreddit_url' => $_POST['subreddit_url'],
                ]
            ];

            $feed = $this->updateFeed($_POST['id'], $data);

            wp_send_json_success([
                'success' => true,
                'feed'    => 'Feed updated successfully!',
            ]);
        } catch (\Exception $e) {
            wp_send_json_success([
                'success' => false,
                'feed'    => $e->getMessage(),
            ]);
        } catch (\Throwable $th) {
            //throw $th;
        }

    }
    
}

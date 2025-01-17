<?php

$router->get('feeds', "FeedController@index");
$router->get('feed', "FeedController@show");
$router->post('delete-feed', "FeedController@delete");
$router->post('feeds', "FeedController@store");
$router->post('edit-feed', "FeedController@update");
$router->post('change-feed-status', "FeedController@changeStatus");
$router->post('regenerate-cache', "FeedController@regenerateCache");

$router->get('settings', "SettingController@index");
$router->post('settings', "SettingController@store");

$router->get('users', "UsersController@index");
$router->get('editor-get-feed', "EditorController@getFeed");
$router->post('editor-save-feed-config', "EditorController@saveFeedConfig");
$router->get('editor-get-feed-config', "EditorController@getFeedConfig");


// frontend
$router->get('get-feed-posts', "FeedPostController@index");



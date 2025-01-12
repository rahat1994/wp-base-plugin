<?php

$router->get('feeds', "FeedController@index");
$router->get('feed', "FeedController@show");
$router->post('deleteFeed', "FeedController@delete");
$router->post('feeds', "FeedController@store");
$router->post('edit-feed', "FeedController@update");

$router->get('settings', "SettingController@index");
$router->post('settings', "SettingController@store");


$router->post('delete-feed', "FeedController@destroy");
$router->get('users', "UsersController@index");



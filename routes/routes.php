<?php

$router->get('feeds', "FeedController@index");
$router->post('feeds', "FeedController@store");
$router->post('edit-feed', "FeedController@update");
$router->get('users', "UsersController@index");

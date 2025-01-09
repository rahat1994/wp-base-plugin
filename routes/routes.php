<?php

$router->get('feeds', "FeedController@index");
$router->post('feeds', "FeedController@store");
$router->post('edit-feed', "FeedController@update");
$router->post('delete-feed', "FeedController@destroy");
$router->get('users', "UsersController@index");



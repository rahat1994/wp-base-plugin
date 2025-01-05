<?php

use App\Common\PluginActivator;
use App\Common\Router;

$container = ServiceContainer::getInstance()->getContainer();
$router = $container->get(Router::class);

$router->get('/test', function () {
    echo 'Hello World';
});
// Router::get('/test', function () {
//     echo 'Hello World';
// });

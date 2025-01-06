<?php


error_log("routes.php loaded");
$router->get('test', function () {
        echo 'Hello World';
});

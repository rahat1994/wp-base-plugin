<?php

$router->get('test', function () {
        echo 'Hello World';
        wp_die();
});

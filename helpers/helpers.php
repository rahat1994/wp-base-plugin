<?php

function wprb_feed_default_config()
{
    $config = array(
        "title" => array(
            "show" => true,
            "tag" => "h2",
            "classes" => ""
        ),
        "description" => array(
            "show" => true,
            "tag" => "p",
            "classes" => ""
        ),
        "list" => array(
            "show" => true,
            "tag" => "ul",
            "classes" => ""
        ),
        "links" => array(
            "tag" => "a",
            "classes" => ""
        )
    );

    return wp_json_encode($config);
}
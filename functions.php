<?php

// Allow Featured Images
add_theme_support('post-thumbnails');

// No index for this site
function stop_robots_indexing()
{
    if (is_robots()) {
        header("Content-Type: text/plain; charset=utf-8");
        echo "User-agent: *\n";
        echo "Disallow: /\n";
        exit();
    }
}
add_action('init', 'stop_robots_indexing');

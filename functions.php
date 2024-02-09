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

// create custom post type
// function create_custom_api_post_type() {
//     register_post_type('api',
//         array(
//             'labels' => array(
//                 'name' => __('APIs'),
//                 'singular_name' => __('API'),
//             ),
//             'public' => true,
//             'has_archive' => true,
//             'rewrite' => array('slug' => 'api'),
//             'show_in_rest' => true,
//             'supports' => array(
//                 'title',
//                 'editor',
//                 'thumbnail',
//                 'custom-fields',
//             ),
//         )
//     );
// }

// add_action('init', 'create_custom_api_post_type');

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

// Enhanced search engine blocking
function enhanced_search_engine_blocking() {
    // Send noindex header
    header('X-Robots-Tag: noindex, nofollow', true);

    // Disable XML-RPC
    add_filter('xmlrpc_enabled', '__return_false');

    // Remove REST API links from head
    remove_action('wp_head', 'rest_output_link_wp_head', 10);
    remove_action('wp_head', 'wp_oembed_add_discovery_links', 10);

    // Remove all RSS feed links
    remove_action('wp_head', 'feed_links', 2);
    remove_action('wp_head', 'feed_links_extra', 3);

    // Remove WP version
    remove_action('wp_head', 'wp_generator');

    // Disable author pages
    if (is_author()) {
        wp_redirect(home_url(), 301);
        exit;
    }
}
add_action('init', 'enhanced_search_engine_blocking');

// Modify REST API headers to prevent indexing
function add_noindex_header_to_rest_api($response) {
    $response->header('X-Robots-Tag', 'noindex, nofollow');
    return $response;
}
add_filter('rest_pre_serve_request', 'add_noindex_header_to_rest_api');

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

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
// Change everything wrapped in **
function create_custom_*POST*_post_type() { // change function name
    register_post_type('*POST*', // change name of post type
        array(
            'labels' => array(
                'name' => __('*POST*s'), // change name of plural post type
                'singular_name' => __('*POST*'), // change this one too
            ),
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => '*POST*'), // make sure this is lowercase
            'show_in_rest' => true,
            'supports' => array(
                'title',
                'editor',
                'thumbnail',
                'custom-fields', // Enable custom fields for this post type
            ),
        )
    );
}

add_action('init', 'create_custom_*POST*_post_type'); // change function name here as well

// Define custom fields as a global array
// define all custom fields below
global $custom_fields;
$custom_fields = array(
    'name' => 'Name',
    'url' => 'URL',
    'description' => 'Description',
    'auth' => 'Auth',
    'https' => 'HTTPS',
    'cors' => 'CORS',
    'pricing' => 'Pricing',
    'category' => 'Category'
);

function add_custom_fields() {
    add_meta_box('*POST*_custom_fields', '*POST* Custom Fields', 'render_custom_fields', '*POST*', 'normal', 'high');
}
add_action('add_meta_boxes', 'add_custom_fields');

function render_custom_fields() {
    global $post, $custom_fields;
    foreach ($custom_fields as $field => $label) {
        $value = get_post_meta($post->ID, $field, true);
        echo '<label for="' . $field . '">' . $label . ':</label>';
        echo '<input type="text" id="' . $field . '" name="' . $field . '" value="' . esc_attr($value) . '" size="40" /><br>';
        echo '<input type="hidden" name="*POST*_meta_box_nonce" value="' . wp_create_nonce('*POST*_meta_box') . '">';
    }
}

function save_custom_fields($post_id) {
    global $custom_fields;

    // Verify nonce
    if (!isset($_POST['*POST*_meta_box_nonce']) || !wp_verify_nonce($_POST['*POST*_meta_box_nonce'], '*POST*_meta_box')) {
        return;
    }

    // Check if it's an autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check permissions
    if ('*POST*' != $_POST['post_type'] || !current_user_can('edit_post', $post_id)) {
        return;
    }

    foreach ($custom_fields as $field => $label) {
        if (isset($_POST[$field])) {
            $value = sanitize_text_field($_POST[$field]);
            update_post_meta($post_id, $field, $value);
        }
    }
}

function *POST*_custom_fields_to_rest() {
    global $custom_fields;

    foreach ($custom_fields as $field_key => $field_label) {
        register_rest_field('*POST*', $field_key, array(
            'get_callback' => function ($post_arr) use ($field_key) {
                return get_post_meta($post_arr['id'], $field_key, true);
            },
            'schema' => array(
                'description' => $field_label,
                'type' => 'string',
            ),
        ));
    }
}

add_action('save_post', 'save_custom_fields');
add_action('rest_api_init', '*POST*_custom_fields_to_rest');
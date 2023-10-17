<?php
// Silence is golden.

echo "Hello World!";
$query = new WP_Query(array('post_type' => 'post'));

if ($query->have_posts()) {
    while ($query->have_posts()) {
        $query->the_post();
        echo '<h2>' . get_the_title() . '</h2>';
    }
} else {
    echo 'No posts found';
}

wp_reset_postdata();

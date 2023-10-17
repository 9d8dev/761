<?php

echo "Hello World!";
$query = new WP_Query(array('post_type' => 'post'));

if ($query->have_posts()) {
    while ($query->have_posts()) {
        $query->the_post();
        echo get_the_title();
    }
} else {
    echo 'No posts found';
}

wp_reset_postdata();

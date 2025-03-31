<?php
function tracker_flow_register_post_types() {
    register_post_type('investment', array(
        'labels' => array('name' => 'Investments', 'singular_name' => 'Investment'),
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor'),
    ));
}
add_action('init', 'tracker_flow_register_post_types');

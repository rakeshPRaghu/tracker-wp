<?php
if (!defined('ABSPATH')) {
    exit; // Prevent direct access
}

// Function to Register "Investor" Role
function tracker_flow_register_roles() {
    add_role('investor', 'Investor', array(
        'read'         => true,
        'edit_posts'   => false,
        'delete_posts' => false,
    ));
}

// Function to Remove Role on Plugin Deactivation
function tracker_flow_remove_roles() {
    remove_role('investor');
}

// Hook Role Registration on Plugin Activation
register_activation_hook(__FILE__, 'tracker_flow_register_roles');
register_deactivation_hook(__FILE__, 'tracker_flow_remove_roles');
?>

<?php

function it_add_investor_role() {
    $role = get_role('investor'); // Get the custom role
    
    if ($role) {
        // Add necessary capabilities
        $role->add_cap('read'); // Can view the admin panel
        $role->add_cap('edit_posts'); // Required for submitting forms
        $role->add_cap('manage_options'); // Allows saving data
    }
}
add_action('init', 'it_add_investor_role');
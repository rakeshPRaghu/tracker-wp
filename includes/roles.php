<?php
function tracker_flow_create_roles() {
    add_role('investor', 'Investor', array(
        'read' => true,
        'edit_posts' => false,
        'delete_posts' => false,
    ));
}

function tracker_flow_remove_roles() {
    remove_role('investor');
}

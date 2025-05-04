<?php
// Add Admin Menu
function tracker_flow_add_admin_menu() {

    function tracker_flow_dashboard() {
        ?>
        <div class="wrap">
            <h1>Investment Tracker Dashboard</h1>
            <p>Welcome to the Investment Tracker plugin. Use the menu to manage deposits, expenses, and subcategories.</p>
        </div>
        <?php
    }
    
    add_menu_page(
        'Investment Tracker', // Page title
        'Investment Tracker', // Menu title
        'manage_options', // Capability (Only admins can access)
        'tracker-flow', // Menu slug
        'tracker_flow_dashboard', // Callback function
        'dashicons-chart-pie', // Icon
        6 // Position in menu
    );

    // Add Subcategories Management Page
    add_submenu_page(
        'tracker-flow',
        'Manage Subcategories',
        'Subcategories',
        'manage_options',
        'tracker-flow-subcategories',
        'tracker_flow_subcategories_page'
    );
}

add_action('admin_menu', 'tracker_flow_add_admin_menu');

?>

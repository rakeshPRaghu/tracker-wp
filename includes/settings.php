<?php
// Add a settings page under the "Tracker Flow" menu
function tracker_flow_settings_menu() {
    add_submenu_page(
        'tracker-flow',           // Parent slug
        'Tracker Flow Settings',  // Page title
        'Settings',               // Menu title
        'manage_options',         // Capability
        'tracker-flow-settings',  // Menu slug
        'tracker_flow_settings_page' // Function to display the page
    );
}
add_action('admin_menu', 'tracker_flow_settings_menu');

// Display the settings page
function tracker_flow_settings_page() {
    ?>
    <div class="wrap">
        <h2>Tracker Flow Settings</h2>
        <form method="post" action="options.php">
            <?php
            settings_fields('tracker_flow_settings_group');
            do_settings_sections('tracker-flow-settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Register settings
function tracker_flow_register_settings() {
    register_setting('tracker_flow_settings_group', 'tracker_flow_email_notifications');
    register_setting('tracker_flow_settings_group', 'tracker_flow_currency');

    add_settings_section('tracker_flow_main_section', 'General Settings', null, 'tracker-flow-settings');

    add_settings_field(
        'tracker_flow_email_notifications',
        'Enable Email Notifications',
        'tracker_flow_email_notifications_callback',
        'tracker-flow-settings',
        'tracker_flow_main_section'
    );

    add_settings_field(
        'tracker_flow_currency',
        'Select Currency',
        'tracker_flow_currency_callback',
        'tracker-flow-settings',
        'tracker_flow_main_section'
    );
}
add_action('admin_init', 'tracker_flow_register_settings');

// Callback for email notifications setting
function tracker_flow_email_notifications_callback() {
    $option = get_option('tracker_flow_email_notifications', 'yes');
    ?>
    <input type="checkbox" name="tracker_flow_email_notifications" value="yes" <?php checked('yes', $option); ?> />
    Enable email notifications
    <?php
}

// Callback for currency selection
function tracker_flow_currency_callback() {
    $option = get_option('tracker_flow_currency', 'USD');
    ?>
    <select name="tracker_flow_currency">
        <option value="USD" <?php selected($option, 'USD'); ?>>USD ($)</option>
        <option value="EUR" <?php selected($option, 'EUR'); ?>>EUR (€)</option>
        <option value="GBP" <?php selected($option, 'GBP'); ?>>GBP (£)</option>
        <option value="INR" <?php selected($option, 'INR'); ?>>INR (₹)</option>
    </select>
    <?php
}
?>

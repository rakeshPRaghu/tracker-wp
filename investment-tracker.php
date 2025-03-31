<?php
/**
 * Plugin Name: Tracker Flow
 * Description: A simple WordPress plugin to track deposits and expenses for investors.
 * Version: 1.0
 * Author: Rakesh PR
 */

if (!defined('ABSPATH')) {
    exit; // Prevent direct access
}

// Define constants
define('TRACKER_FLOW_PATH', plugin_dir_path(__FILE__));
define('TRACKER_FLOW_URL', plugin_dir_url(__FILE__));

// Include required files
require_once TRACKER_FLOW_PATH . 'includes/access-control.php';
require_once TRACKER_FLOW_PATH . 'includes/database.php';
require_once TRACKER_FLOW_PATH . 'includes/admin-menu.php';
require_once TRACKER_FLOW_PATH . 'includes/handlers.php';
require_once TRACKER_FLOW_PATH . 'includes/functions.php';
require_once TRACKER_FLOW_PATH . 'includes/shortcodes.php';
require_once TRACKER_FLOW_PATH . 'includes/email.php';
require_once TRACKER_FLOW_PATH . 'includes/post-types.php';
require_once TRACKER_FLOW_PATH . 'includes/roles.php';

// Register styles and scripts
function it_enqueue_assets() {
    wp_enqueue_style('investment-tracker-css', plugin_dir_url(__FILE__) . 'assets/styles.css');
}
add_action('wp_enqueue_scripts', 'it_enqueue_assets');

// Activate the plugin (create DB tables & roles)
register_activation_hook(__FILE__, 'tracker_flow_create_db');
register_activation_hook(__FILE__, 'tracker_flow_create_roles');
register_deactivation_hook(__FILE__, 'tracker_flow_remove_roles');

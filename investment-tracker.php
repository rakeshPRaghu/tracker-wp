<?php
/**
 * Plugin Name: Tracker Flow
 * Plugin URI:  https://yourwebsite.com
 * Description: A financial tracker for deposits, investments, expenses, and sales.
 * Version:     1.0
 * Author:      Rakesh PR
 * Author URI:  https://yourwebsite.com
 * License:     GPL-2.0+
 */

if (!defined('ABSPATH')) {
    exit; // Prevent direct access
}

// Include Required Files
require_once plugin_dir_path(__FILE__) . 'includes/admin-menu.php';
require_once plugin_dir_path(__FILE__) . 'includes/admin-subcategories.php';
require_once plugin_dir_path(__FILE__) . 'includes/database.php';
require_once plugin_dir_path(__FILE__) . 'includes/shortcodes.php';  // Include shortcodes file
require_once plugin_dir_path(__FILE__) . 'includes/access-control.php';
require_once plugin_dir_path(__FILE__) . 'includes/email.php';
// require_once plugin_dir_path(__FILE__) . 'includes/post-types.php';
require_once plugin_dir_path(__FILE__) . 'includes/roles.php';
require_once plugin_dir_path(__FILE__) . 'includes/reports.php';

// Plugin Activation Hook
function tracker_flow_activate() {
    tracker_flow_register_roles();  
    tracker_flow_create_deposit_table();  
    tracker_flow_create_expense_table();  
    tracker_flow_create_sales_table();   
    tracker_flow_create_investment_table();  
    tracker_flow_create_subcategory_table();  
}
register_activation_hook(__FILE__, 'tracker_flow_activate');

// Plugin Deactivation Hook
function tracker_flow_deactivate() {
    tracker_flow_remove_roles(); 
}
register_deactivation_hook(__FILE__, 'tracker_flow_deactivate');
?>

<?php
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

global $wpdb;
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}tracker_deposits");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}tracker_expenses");
?>

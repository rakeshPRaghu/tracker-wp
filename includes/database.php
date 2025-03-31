<?php
function tracker_flow_create_db() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'investment_tracker';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_id BIGINT(20) UNSIGNED NOT NULL,
        type ENUM('deposit', 'expense') NOT NULL,
        amount DECIMAL(10,2) NOT NULL,
        category VARCHAR(255) DEFAULT NULL,
        subcategory VARCHAR(255) DEFAULT NULL,
        deposit_month VARCHAR(20) DEFAULT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    ) $charset_collate;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);
}

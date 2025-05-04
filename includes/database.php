<?php
if (!defined('ABSPATH')) {
    exit; // Prevent direct access
}

// Create Deposits Table
function tracker_flow_create_deposit_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'tracker_deposits';
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id INT(11) NOT NULL AUTO_INCREMENT,
            user_id VARCHAR(255) NOT NULL,
            deposited_by VARCHAR(255) NOT NULL,
            deposit_amount DECIMAL(10,2) NOT NULL,
            deposit_date DATE NOT NULL,
            transaction_id VARCHAR(255) DEFAULT NULL,
            proof_of_payment VARCHAR(255) DEFAULT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}

// Create Expenses Table
function tracker_flow_create_expense_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'tracker_expenses';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id BIGINT(20) AUTO_INCREMENT PRIMARY KEY,
        user_id VARCHAR(255) NOT NULL, 
        expense_type VARCHAR(255) NOT NULL, 
        expense_amount DECIMAL(10,2) NOT NULL,
        expense_date DATE NOT NULL,
        proof_of_payment VARCHAR(255) DEFAULT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    ) $charset_collate;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);
}

// Create Sales Table
function tracker_flow_create_sales_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'tracker_sales';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id BIGINT(20) AUTO_INCREMENT PRIMARY KEY,
        user_id VARCHAR(255) NOT NULL,
        product_name VARCHAR(255) NOT NULL,
        quantity_sold INT NOT NULL,
        sale_amount DECIMAL(10,2) NOT NULL,
        payment_method VARCHAR(100) NOT NULL,
        order_status VARCHAR(100) NOT NULL,
        sale_date DATE NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    ) $charset_collate;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);
}

// Create Investment Table
function tracker_flow_create_investment_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'tracker_investments';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id BIGINT(20) AUTO_INCREMENT PRIMARY KEY,
        user_id BIGINT(20) NOT NULL,
        subcategory_id BIGINT(20) NOT NULL,
        invested_by VARCHAR(255) NOT NULL,
        investment_amount DECIMAL(10,2) NOT NULL,
        investment_date DATE NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (subcategory_id) REFERENCES {$wpdb->prefix}tracker_subcategories(id) ON DELETE CASCADE
    ) $charset_collate;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);
}

// Create Subcategory Table
function tracker_flow_create_subcategory_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'tracker_subcategories';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        description TEXT NULL,
        total_amount DECIMAL(10,2) NOT NULL,
        num_investors INT NOT NULL DEFAULT 4,
        amount_per_investor DECIMAL(10,2) GENERATED ALWAYS AS (total_amount / num_investors) STORED,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) $charset_collate;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);
}
?>

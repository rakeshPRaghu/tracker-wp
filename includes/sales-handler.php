<?php
if (!defined('ABSPATH')) {
    exit; // Prevent direct access
}

// Create Sales Table
function tracker_flow_create_sales_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'tracker_sales';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id BIGINT(20) AUTO_INCREMENT PRIMARY KEY,
        product_name VARCHAR(255) NOT NULL,
        quantity_sold INT NOT NULL,
        sale_amount DECIMAL(10,2) NOT NULL,
        payment_method ENUM('Cash', 'Card', 'Online') NOT NULL,
        order_status ENUM('Pending', 'Completed', 'Refunded') NOT NULL DEFAULT 'Pending',
        sale_date DATE NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    ) $charset_collate;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);
}
register_activation_hook(__FILE__, 'tracker_flow_create_sales_table');

// Handle Sales Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_sale'])) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'tracker_sales';

    $product_name = sanitize_text_field($_POST['product_name']);
    $quantity_sold = intval($_POST['quantity_sold']);
    $sale_amount = floatval($_POST['sale_amount']);
    $payment_method = sanitize_text_field($_POST['payment_method']);
    $order_status = sanitize_text_field($_POST['order_status']);
    $sale_date = sanitize_text_field($_POST['sale_date']);

    $wpdb->insert($table_name, array(
        'product_name' => $product_name,
        'quantity_sold' => $quantity_sold,
        'sale_amount' => $sale_amount,
        'payment_method' => $payment_method,
        'order_status' => $order_status,
        'sale_date' => $sale_date
    ));

    echo "Sale recorded successfully!";
}

// Get Sales Data
function tracker_flow_get_sales() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'tracker_sales';
    return $wpdb->get_results("SELECT * FROM $table_name ORDER BY sale_date DESC");
}

// Get Sales Summary
function tracker_flow_get_sales_summary() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'tracker_sales';

    return $wpdb->get_row("SELECT COUNT(id) as total_sales, SUM(sale_amount) as total_revenue FROM $table_name");
}
?>

<?php
if (!defined('ABSPATH')) {
    exit; // Prevent direct access
}

// Create Investment Table
function tracker_flow_create_investment_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'tracker_investments';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id BIGINT(20) AUTO_INCREMENT PRIMARY KEY,
        category_name VARCHAR(255) NOT NULL,
        description TEXT DEFAULT NULL,
        total_amount DECIMAL(10,2) NOT NULL,
        num_investors INT NOT NULL,
        amount_per_investor DECIMAL(10,2) NOT NULL,
        status ENUM('open', 'closed') DEFAULT 'open',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    ) $charset_collate;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);
}
register_activation_hook(__FILE__, 'tracker_flow_create_investment_table');

// Create Investment Subcategory Table
function tracker_flow_create_subcategory_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'tracker_investment_subcategories';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id BIGINT(20) AUTO_INCREMENT PRIMARY KEY,
        investment_id BIGINT(20) NOT NULL,
        subcategory_name VARCHAR(255) NOT NULL,
        total_required DECIMAL(10,2) NOT NULL,
        amount_collected DECIMAL(10,2) DEFAULT 0,
        status ENUM('pending', 'funded') DEFAULT 'pending',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (investment_id) REFERENCES {$wpdb->prefix}tracker_investments(id) ON DELETE CASCADE
    ) $charset_collate;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);
}
register_activation_hook(__FILE__, 'tracker_flow_create_subcategory_table');

// Handle Investment Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_investment'])) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'tracker_investments';

    $category_name = sanitize_text_field($_POST['category_name']);
    $description = sanitize_textarea_field($_POST['description']);
    $total_amount = floatval($_POST['total_amount']);
    $num_investors = intval($_POST['num_investors']);
    $amount_per_investor = $total_amount / $num_investors;

    $wpdb->insert($table_name, array(
        'category_name' => $category_name,
        'description' => $description,
        'total_amount' => $total_amount,
        'num_investors' => $num_investors,
        'amount_per_investor' => $amount_per_investor,
        'status' => 'open'
    ));

    echo "Investment category created successfully!";
}

// Handle Subcategory Creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_subcategory'])) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'tracker_investment_subcategories';

    $investment_id = intval($_POST['investment_id']);
    $subcategory_name = sanitize_text_field($_POST['subcategory_name']);
    $total_required = floatval($_POST['total_required']);

    $wpdb->insert($table_name, array(
        'investment_id' => $investment_id,
        'subcategory_name' => $subcategory_name,
        'total_required' => $total_required,
        'amount_collected' => 0,
        'status' => 'pending'
    ));

    echo "Subcategory created successfully!";
}

// Handle Investment Contribution
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_contribution'])) {
    global $wpdb;
    $subcategory_table = $wpdb->prefix . 'tracker_investment_subcategories';
    $user_id = get_current_user_id();
    $subcategory_id = intval($_POST['subcategory_id']);
    $amount_paid = floatval($_POST['amount_paid']);

    // Get the subcategory details
    $subcategory = $wpdb->get_row($wpdb->prepare("SELECT * FROM $subcategory_table WHERE id = %d", $subcategory_id));
    if (!$subcategory) {
        echo "Invalid subcategory.";
        exit;
    }

    $new_amount_collected = $subcategory->amount_collected + $amount_paid;
    $remaining_amount = $subcategory->total_required - $new_amount_collected;

    // Update the collected amount
    $wpdb->update($subcategory_table, array(
        'amount_collected' => $new_amount_collected,
        'status' => ($remaining_amount <= 0) ? 'funded' : 'pending'
    ), array('id' => $subcategory_id));

    echo "Contribution recorded successfully!";
}

// List Investment Categories
function tracker_flow_get_investment_categories() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'tracker_investments';
    return $wpdb->get_results("SELECT * FROM $table_name WHERE status = 'open'");
}

// List Subcategories
function tracker_flow_get_subcategories($investment_id) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'tracker_investment_subcategories';
    return $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name WHERE investment_id = %d", $investment_id));
}
?>

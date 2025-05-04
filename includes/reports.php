<?php
if (!defined('ABSPATH')) {
    exit; // Prevent direct access
}

// Generate Reports Page in Admin Menu
function tracker_flow_reports_menu() {
    add_submenu_page(
        'tracker-flow',
        'Financial Reports',
        'Reports',
        'manage_options',
        'tracker-flow-reports',
        'tracker_flow_reports_page'
    );
}
add_action('admin_menu', 'tracker_flow_reports_menu');

// Display Reports Page
function tracker_flow_reports_page() {
    ?>
    <div class="wrap">
        <h2>Financial Reports</h2>
        
        <form method="post" action="">
            <label>Select Date Range:</label>
            <input type="date" name="start_date">
            <input type="date" name="end_date">
            <input type="submit" name="generate_report" value="Generate Report" class="button button-primary">
        </form>

        <?php
        if (isset($_POST['generate_report'])) {
            $start_date = sanitize_text_field($_POST['start_date']);
            $end_date = sanitize_text_field($_POST['end_date']);
            tracker_flow_generate_report($start_date, $end_date);
        }
        ?>
    </div>
    <?php
}

// Generate Report Data
function tracker_flow_generate_report($start_date, $end_date) {
    global $wpdb;

    // Fetch Deposits
    $deposits = $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}tracker_deposits WHERE deposit_date BETWEEN %s AND %s", $start_date, $end_date
    ));

    // Fetch Expenses
    $expenses = $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}tracker_expenses WHERE expense_date BETWEEN %s AND %s", $start_date, $end_date
    ));

    // Fetch Sales
    $sales = $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}tracker_sales WHERE sale_date BETWEEN %s AND %s", $start_date, $end_date
    ));

    // Display Results
    echo "<h3>Report from $start_date to $end_date</h3>";

    echo "<h4>Total Deposits:</h4>";
    foreach ($deposits as $deposit) {
        echo "Deposited by: " . esc_html($deposit->deposited_by) . " - ₹" . esc_html($deposit->deposit_amount) . "<br>";
    }

    echo "<h4>Total Expenses:</h4>";
    foreach ($expenses as $expense) {
        echo "Expense Type: " . esc_html($expense->expense_type) . " - ₹" . esc_html($expense->expense_amount) . "<br>";
    }

    echo "<h4>Total Sales:</h4>";
    foreach ($sales as $sale) {
        echo "Product: " . esc_html($sale->product_name) . " - ₹" . esc_html($sale->sale_amount) . "<br>";
    }

    echo "<br><a href='#' class='button button-secondary'>Export CSV</a> ";
    echo "<a href='#' class='button button-secondary'>Export PDF</a>";
}

// Export to CSV Function (Placeholder)
function tracker_flow_export_csv() {
    // CSV Export Logic Here
}

// Export to PDF Function (Placeholder)
function tracker_flow_export_pdf() {
    // PDF Export Logic Here
}
?>

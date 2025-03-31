<?php
function tracker_flow_dashboard() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'investment_tracker';

    $total_deposits = (float) $wpdb->get_var("SELECT COALESCE(SUM(amount), 0) FROM $table_name WHERE type = 'deposit'");
    $total_expenses = (float) $wpdb->get_var("SELECT COALESCE(SUM(amount), 0) FROM $table_name WHERE type = 'expense'");
    $current_balance = $total_deposits - $total_expenses;

    echo "<h2>Investment Dashboard</h2>";
    echo "<p><strong>Total Deposits:</strong> ₹" . number_format($total_deposits, 2) . "</p>";
    echo "<p><strong>Total Expenses:</strong> ₹" . number_format($total_expenses, 2) . "</p>";
    echo "<p><strong>Current Balance:</strong> ₹" . number_format($current_balance, 2) . "</p>";

    // Breakdown by investor
    echo "<h3>Investor Contributions</h3>";
    $investors = get_users(['role' => 'investor']);
    foreach ($investors as $investor) {
        $investor_id = $investor->ID;
        $name = $investor->display_name;
        $investor_total = (float) $wpdb->get_var("SELECT COALESCE(SUM(amount), 0) FROM $table_name WHERE user_id = $investor_id AND type = 'deposit'");
        echo "<p>$name: ₹" . number_format($investor_total, 2) . "</p>";
    }
}

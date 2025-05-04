<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_expense'])) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'tracker_expenses';
    $user_id = get_current_user_id();
    $amount = floatval($_POST['amount']);
    $expense_date = sanitize_text_field($_POST['expense_date']);
    $expense_type = sanitize_text_field($_POST['expense_type']);

    $wpdb->insert($table_name, array(
        'user_id' => $user_id,
        'type' => $expense_type,
        'amount' => $amount,
        'expense_date' => $expense_date
    ));

    echo "Expense submitted successfully!";
}
?>

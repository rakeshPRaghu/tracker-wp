<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_deposit'])) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'tracker_deposits';
    $user_id = get_current_user_id();
    $amount = floatval($_POST['amount']);
    $deposit_date = sanitize_text_field($_POST['deposit_date']);
    $transaction_id = sanitize_text_field($_POST['transaction_id']);

    $wpdb->insert($table_name, array(
        'deposited_by' => $user_id,
        'deposit_amount' => $amount,
        'deposit_date' => $deposit_date,
        'transaction_id' => $transaction_id,
        'status' => 'pending'
    ));

    echo "Deposit submitted successfully!";
}
?>

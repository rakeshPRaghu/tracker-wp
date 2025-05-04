<?php
global $wpdb;
$table_name = $wpdb->prefix . 'tracker_deposits';
$total_deposits = $wpdb->get_var("SELECT SUM(amount) FROM $table_name WHERE status = 'approved'");
?>
<h2>Financial Dashboard</h2>
<p>Total Deposits: <?php echo number_format($total_deposits, 2); ?></p>

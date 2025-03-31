<?php
function it_save_transaction() {
    if (!is_user_logged_in() || !isset($_POST['submit_transaction'])) {
        return;
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'investment_tracker';

    $user_id = get_current_user_id();
    $type = sanitize_text_field($_POST['type']);
    $amount = floatval($_POST['amount']);
    $category = sanitize_text_field($_POST['category']);
    $subcategory = isset($_POST['subcategory']) ? sanitize_text_field($_POST['subcategory']) : null;
    $month = isset($_POST['deposit_month']) ? sanitize_text_field($_POST['deposit_month']) : null;

    // Get total required for this category (divide by 4)
    $total_required = $amount / 4;

    // Get current contributions by each investor
    $investors = get_users(['role' => 'investor']);
    $investor_contributions = [];
    foreach ($investors as $investor) {
        $investor_id = $investor->ID;
        $contributed = (float) $wpdb->get_var("SELECT SUM(amount) FROM $table_name WHERE user_id = $investor_id AND category = '$category' AND deposit_month = '$month'");
        $investor_contributions[$investor_id] = $contributed;
    }

    // Check if current user overpaid
    $overpaid = 0;
    if ($investor_contributions[$user_id] + $amount > $total_required) {
        $overpaid = ($investor_contributions[$user_id] + $amount) - $total_required;
        $amount -= $overpaid;
    }

    // Save deposit
    $wpdb->insert($table_name, array(
        'user_id' => $user_id,
        'type' => $type,
        'amount' => $amount,
        'category' => $category,
        'subcategory' => $subcategory,
        'deposit_month' => $month,
        'created_at' => current_time('mysql')
    ));

    // Reallocate overpaid amount to underpaid investors
    if ($overpaid > 0) {
        foreach ($investors as $investor) {
            $investor_id = $investor->ID;
            if ($investor_id != $user_id && $investor_contributions[$investor_id] < $total_required) {
                $remaining = $total_required - $investor_contributions[$investor_id];
                $adjustment = min($remaining, $overpaid);
                $overpaid -= $adjustment;

                $wpdb->insert($table_name, array(
                    'user_id' => $investor_id,
                    'type' => 'deposit',
                    'amount' => $adjustment,
                    'category' => $category,
                    'subcategory' => $subcategory,
                    'deposit_month' => $month,
                    'created_at' => current_time('mysql')
                ));

                if ($overpaid <= 0) {
                    break;
                }
            }
        }
    }

    wp_redirect($_SERVER['REQUEST_URI']);
    exit;
}
add_action('init', 'it_save_transaction');

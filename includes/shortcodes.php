<?php
if (!defined('ABSPATH')) {
    exit; // Prevent direct access
}

// Shortcode to display list of Deposits
function tracker_flow_deposits_shortcode($atts)
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'tracker_deposits';

    // Query to get the deposits
    $deposits = $wpdb->get_results("SELECT * FROM $table_name ORDER BY deposit_date DESC");

    if ($deposits) {
        // Start the output for the table
        $output = '<table class="tracker-deposits-table">';
        $output .= '<thead><tr><th>Deposited By</th><th>Amount</th><th>Date</th><th>Transaction ID</th><th>Proof of Payment</th></tr></thead>';
        $output .= '<tbody>';

        // Loop through each deposit and display it
        foreach ($deposits as $deposit) {
            // Get the user's name based on the user ID
            $user_info = get_userdata($deposit->deposited_by);
            $user_name = $user_info ? $user_info->display_name : 'Unknown User';

            $output .= '<tr>';
            $output .= '<td>' . esc_html($user_name) . '</td>';
            $output .= '<td>' . esc_html($deposit->deposit_amount) . '</td>';
            $output .= '<td>' . esc_html($deposit->deposit_date) . '</td>';
            $output .= '<td>' . esc_html($deposit->transaction_id) . '</td>';
            $output .= '<td><a href="' . esc_url($deposit->proof_of_payment) . '" target="_blank">View Proof</a></td>';
            $output .= '</tr>';
        }

        $output .= '</tbody></table>';
    } else {
        $output = '<p>No deposits found.</p>';
    }

    return $output;
}
add_shortcode('tracker_deposits', 'tracker_flow_deposits_shortcode');

// Shortcode to display list of Expenses
function tracker_flow_expenses_shortcode($atts)
{
    global $wpdb;
    $user_id = get_current_user_id();
    $table_name = $wpdb->prefix . 'tracker_expenses';

    $expenses = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT * FROM $table_name ORDER BY expense_date DESC",
            '%%'
        )
    );

    if (empty($expenses)) {
        return 'No expenses found.';
    }

    $output = '<table><thead><tr><th>Expense Type</th><th>Amount Spent</th><th>Expense Date</th><th>Proof</th></tr></thead><tbody>';
    foreach ($expenses as $expense) {
        $output .= "<tr>
                        <td>" . esc_html($expense->expense_type) . "</td>
                        <td>" . esc_html($expense->expense_amount) . "</td>
                        <td>" . esc_html($expense->expense_date) . "</td>
                        <td><a href='" . esc_url($expense->proof_of_payment) . "' target='_blank'>View Proof</a></td>
                    </tr>";
    }
    $output .= '</tbody></table>';

    return $output;
}
add_shortcode('tracker_expenses', 'tracker_flow_expenses_shortcode');

// Shortcode to display list of Sales
function tracker_flow_sales_shortcode($atts)
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'tracker_sales';

    $sales = $wpdb->get_results(
        "SELECT * FROM $table_name ORDER BY sale_date DESC"
    );

    if (empty($sales)) {
        return 'No sales found.';
    }

    $output = '<table><thead><tr><th>Product Name</th><th>Quantity Sold</th><th>Sale Amount</th><th>Payment Method</th><th>Order Status</th><th>Sale Date</th></tr></thead><tbody>';
    foreach ($sales as $sale) {
        $output .= "<tr>
                        <td>" . esc_html($sale->product_name) . "</td>
                        <td>" . esc_html($sale->quantity_sold) . "</td>
                        <td>" . esc_html($sale->sale_amount) . "</td>
                        <td>" . esc_html($sale->payment_method) . "</td>
                        <td>" . esc_html($sale->order_status) . "</td>
                        <td>" . esc_html($sale->sale_date) . "</td>
                    </tr>";
    }
    $output .= '</tbody></table>';

    return $output;
}
add_shortcode('tracker_sales', 'tracker_flow_sales_shortcode');

// Shortcode to display list of Investments
function tracker_flow_investments_shortcode($atts)
{
    global $wpdb;
    $user_id = get_current_user_id();
    $table_name = $wpdb->prefix . 'tracker_investments';

    $investments = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT * FROM $table_name WHERE invested_by = %s ORDER BY investment_date DESC",
            get_the_author_meta('display_name', $user_id)
        )
    );

    if (empty($investments)) {
        return 'No investments found.';
    }

    $output = '<table><thead><tr><th>Investment Amount</th><th>Investment Date</th><th>Subcategory</th></tr></thead><tbody>';
    foreach ($investments as $investment) {
        // Get subcategory name
        $subcategory = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT name FROM {$wpdb->prefix}tracker_subcategories WHERE id = %d",
                $investment->subcategory_id
            )
        );

        $output .= "<tr>
                        <td>" . esc_html($investment->investment_amount) . "</td>
                        <td>" . esc_html($investment->investment_date) . "</td>
                        <td>" . esc_html($subcategory->name) . "</td>
                    </tr>";
    }
    $output .= '</tbody></table>';

    return $output;
}
add_shortcode('tracker_investments', 'tracker_flow_investments_shortcode');

// Shortcode to display list of Subcategories
function tracker_flow_subcategories_shortcode($atts)
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'tracker_subcategories';

    $subcategories = $wpdb->get_results(
        "SELECT * FROM $table_name WHERE status = 'open' ORDER BY created_at DESC"
    );

    if (empty($subcategories)) {
        return 'No subcategories available.';
    }

    $output = '<table><thead><tr><th>Subcategory Name</th><th>Description</th><th>Total Amount Required</th><th>Amount Per Investor</th><th>Status</th></tr></thead><tbody>';
    foreach ($subcategories as $subcategory) {
        $output .= "<tr>
                        <td>" . esc_html($subcategory->name) . "</td>
                        <td>" . esc_html($subcategory->description) . "</td>
                        <td>" . esc_html($subcategory->total_amount) . "</td>
                        <td>" . esc_html($subcategory->amount_per_investor) . "</td>
                        <td>" . esc_html($subcategory->status) . "</td>
                    </tr>";
    }
    $output .= '</tbody></table>';

    return $output;
}
add_shortcode('tracker_subcategories', 'tracker_flow_subcategories_shortcode');

// Shortcode to display Deposit Submission Form
function tracker_flow_deposit_form_shortcode($atts)
{
    if (!is_user_logged_in()) {
        return 'You must be logged in to submit a deposit.';
    }
    if (!function_exists('wp_handle_upload')) {
        require_once(ABSPATH . 'wp-admin/includes/file.php');
    }
    $user_id = get_current_user_id();

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_deposit'])) {
        // Process Deposit Form Submission
        $deposit_amount = sanitize_text_field($_POST['deposit_amount']);
        $deposit_date = sanitize_text_field($_POST['deposit_date']);
        $transaction_id = sanitize_text_field($_POST['transaction_id']);
        $proof_of_payment = $_FILES['proof_of_payment'];

        // Validate the fields
        if (empty($deposit_amount) || empty($deposit_date) || empty($proof_of_payment)) {
            return 'Please fill in all the required fields.';
        }

        // Handle file upload for proof of payment
        $uploaded_file = wp_handle_upload($proof_of_payment, ['test_form' => false]);
        if ($uploaded_file && !isset($uploaded_file['error'])) {
            $proof_url = $uploaded_file['url'];
        } else {
            return 'There was an error uploading your proof of payment.';
        }

        global $wpdb;
        $table_name = $wpdb->prefix . 'tracker_deposits';
        $wpdb->insert(
            $table_name,
            [
                'deposited_by' => $user_id,
                'deposit_amount' => $deposit_amount,
                'deposit_date' => $deposit_date,
                'transaction_id' => $transaction_id,
                'proof_of_payment' => $proof_url,
            ]
        );

        return 'Your deposit has been submitted successfully!';
    }

    // Deposit Form HTML
    $form_html = '<form method="POST" enctype="multipart/form-data">
                    <label for="deposit_amount">Deposit Amount:</label>
                    <input type="number" name="deposit_amount" required><br>
                    
                    <label for="deposit_date">Deposit Date:</label>
                    <input type="date" name="deposit_date" required><br>
                    
                    <label for="transaction_id">Transaction ID (Optional):</label>
                    <input type="text" name="transaction_id"><br>
                    
                    <label for="proof_of_payment">Proof of Payment (Upload Screenshot):</label>
                    <input type="file" name="proof_of_payment" required><br>
                    
                    <input type="submit" name="submit_deposit" value="Submit Deposit">
                  </form>';

    return $form_html;
}
add_shortcode('tracker_deposit_form', 'tracker_flow_deposit_form_shortcode');

// Shortcode to display Expense Submission Form
function tracker_flow_expense_form_shortcode($atts)
{
    if (!is_user_logged_in()) {
        return 'You must be logged in to submit an expense.';
    }
    if (!function_exists('wp_handle_upload')) {
        require_once(ABSPATH . 'wp-admin/includes/file.php');
    }
    $user_id = get_current_user_id();

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_expense'])) {
        // Process Expense Form Submission
        $expense_type = sanitize_text_field($_POST['expense_type']);
        $expense_amount = sanitize_text_field($_POST['expense_amount']);
        $expense_date = sanitize_text_field($_POST['expense_date']);
        $proof_of_payment = $_FILES['proof_of_payment'];

        // Validate the fields
        if (empty($expense_type) || empty($expense_amount) || empty($expense_date) || empty($proof_of_payment)) {
            return 'Please fill in all the required fields.';
        }

        // Handle file upload for proof of payment
        $uploaded_file = wp_handle_upload($proof_of_payment, ['test_form' => false]);
        if ($uploaded_file && !isset($uploaded_file['error'])) {
            $proof_url = $uploaded_file['url'];
        } else {
            return 'There was an error uploading your proof of payment.';
        }

        global $wpdb;
        $table_name = $wpdb->prefix . 'tracker_expenses';
        $wpdb->insert(
            $table_name,
            [
                'user_id' => $user_id,
                'expense_type' => $expense_type,
                'expense_amount' => $expense_amount,
                'expense_date' => $expense_date,
                'proof_of_payment' => $proof_url,
            ]
        );

        return 'Your expense has been submitted successfully!';
    }

    // Expense Form HTML
    $form_html = '<form method="POST" enctype="multipart/form-data">
                    <label for="expense_type">Expense Type:</label>
                    <select name="expense_type">
                        <option value="Product Purchase">Product Purchase</option>
                        <option value="Branding/Marketing">Branding/Marketing</option>
                        <option value="Rent">Rent</option>
                        <option value="Labour Fee">Labour Fee</option>
                        <option value="Other">Other</option>
                    </select><br>

                    <label for="expense_amount">Amount Spent:</label>
                    <input type="number" name="expense_amount" required><br>
                    
                    <label for="expense_date">Expense Date:</label>
                    <input type="date" name="expense_date" required><br>

                    <label for="proof_of_payment">Proof of Payment (Upload Screenshot):</label>
                    <input type="file" name="proof_of_payment" required><br>
                    
                    <input type="submit" name="submit_expense" value="Submit Expense">
                  </form>';

    return $form_html;
}
add_shortcode('tracker_expense_form', 'tracker_flow_expense_form_shortcode');

// Shortcode to display Investment Submission Form
function tracker_flow_investment_form_shortcode($atts)
{
    if (!is_user_logged_in()) {
        return 'You must be logged in to submit an investment.';
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_investment'])) {
        // Process Investment Form Submission
        $subcategory_id = sanitize_text_field($_POST['subcategory_id']);
        $investment_amount = sanitize_text_field($_POST['investment_amount']);
        $investment_date = sanitize_text_field($_POST['investment_date']);

        // Validate the fields
        if (empty($subcategory_id) || empty($investment_amount) || empty($investment_date)) {
            return 'Please fill in all the required fields.';
        }

        global $wpdb;
        $table_name = $wpdb->prefix . 'tracker_investments';
        $wpdb->insert(
            $table_name,
            [
                'user_id' => get_current_user_id(),
                'subcategory_id' => $subcategory_id,
                'investment_amount' => $investment_amount,
                'investment_date' => $investment_date,
            ]
        );

        return 'Your investment has been submitted successfully!';
    }

    // Investment Form HTML
    global $wpdb;
    $table_name = $wpdb->prefix . 'tracker_subcategories';
    $subcategories = $wpdb->get_results("SELECT * FROM $table_name WHERE status = 'open'");

    if (empty($subcategories)) {
        return 'No open subcategories available for investment.';
    }

    $form_html = '<form method="POST">
                    <label for="subcategory_id">Select Subcategory:</label>
                    <select name="subcategory_id" required>';
    foreach ($subcategories as $subcategory) {
        $form_html .= '<option value="' . esc_attr($subcategory->id) . '">' . esc_html($subcategory->name) . '</option>';
    }
    $form_html .= '</select><br>

                    <label for="investment_amount">Investment Amount:</label>
                    <input type="number" name="investment_amount" required><br>
                    
                    <label for="investment_date">Investment Date:</label>
                    <input type="date" name="investment_date" required><br>
                    
                    <input type="submit" name="submit_investment" value="Submit Investment">
                  </form>';

    return $form_html;
}
add_shortcode('tracker_investment_form', 'tracker_flow_investment_form_shortcode');

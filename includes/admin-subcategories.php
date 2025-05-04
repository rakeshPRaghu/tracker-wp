<?php 
function tracker_flow_subcategories_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'tracker_subcategories';

    // Handle form submission
    if (isset($_POST['submit_subcategory'])) {
        $category_name = sanitize_text_field($_POST['category_name']);
        $name = sanitize_text_field($_POST['name']);
        $total_amount = floatval($_POST['total_amount']);

        $wpdb->insert($table_name, [
            'name' => $name,
            'total_amount' => $total_amount
        ]);
    }

    // Retrieve subcategories
    $subcategories = $wpdb->get_results("SELECT * FROM $table_name");

    ?>
    <div class="wrap">
        <h1>Sub-Category Management</h1>
        <form method="post">
            <table class="form-table">
                <tr>
                    <th><label for="name">Subcategory Name</label></th>
                    <td><input type="text" name="name" required></td>
                </tr>
                <tr>
                    <th><label for="total_amount">Total Amount</label></th>
                    <td><input type="number" name="total_amount" required></td>
                </tr>
            </table>
            <p><input type="submit" name="submit_subcategory" value="Add Subcategory" class="button button-primary"></p>
        </form>

        <h2>Existing Subcategories</h2>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Subcategory</th>
                    <th>Total Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($subcategories as $sub) : ?>
                    <tr>
                        <td><?php echo esc_html($sub->id); ?></td>
                        <td><?php echo esc_html($sub->name); ?></td>
                        <td><?php echo esc_html($sub->total_amount); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php
}

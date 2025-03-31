<?php
function tracker_flow_send_email($type, $amount, $user_id) {
    $user = get_userdata($user_id);
    $admin_email = get_option('admin_email');
    $subject = "New $type Recorded";
    $message = "User: " . $user->display_name . " has recorded a $type of ₹$amount.";

    wp_mail($admin_email, $subject, $message);
}

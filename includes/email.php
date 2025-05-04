<?php
function tracker_flow_send_email($to, $subject, $message) {
    wp_mail($to, $subject, $message);
}
?>

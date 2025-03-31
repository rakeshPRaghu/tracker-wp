<?php
function tracker_flow_shortcode() {
    ob_start();
    include TRACKER_FLOW_PATH . 'templates/form.php';
    return ob_get_clean();
}
add_shortcode('investment_dashboard', 'tracker_flow_shortcode');

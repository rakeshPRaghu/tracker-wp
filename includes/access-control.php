<?php
// Hide Admin Bar for Investors
function it_hide_admin_bar_for_investors() {
    if (current_user_can('investor')) {
        show_admin_bar(false);
    }
}
add_action('after_setup_theme', 'it_hide_admin_bar_for_investors');

// Redirect Investors from Admin Panel
function it_redirect_investors_from_admin() {
    if (is_admin() && !current_user_can('administrator')) {
        wp_redirect(home_url('/investment-dashboard'));
        exit;
    }
}
add_action('admin_init', 'it_redirect_investors_from_admin');

// Redirect Non-Logged-In Users to Login Page
function it_redirect_non_logged_in_users() {
    if (!is_user_logged_in() && !is_page('wp-login.php')) {
        wp_redirect(wp_login_url());
        exit;
    }
}
add_action('template_redirect', 'it_redirect_non_logged_in_users');
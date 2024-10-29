<?php
/**
 * Plugin Name: Elementor Role API Widget
 * Description: Custom Elementor widget to display roles from a remote REST API.
 * Version: 1.0
 * Author: Becky
 * Text Domain: eraw
 */

// Exit if accessed directly
if (! defined('ABSPATH')) {
    exit;
}

// Check if Elementor is activated
function elementor_role_api_widget_init()
{

    if (! did_action('elementor/loaded')) {
        add_action('admin_notices', 'elementor_role_api_widget_fail_notice');
        return;
    }

    // Register the widget with Elementor
    function register_role_api_widget($widgets_manager)
    {
        require_once(__DIR__ . '/includes/class-elementor-role-api-widget.php');
        $widgets_manager->register(new \Elementor_Role_API_Widget());
    }

    add_action('elementor/widgets/register', 'register_role_api_widget');
}
add_action('plugins_loaded', 'elementor_role_api_widget_init');

// Show a notice if Elementor is not activated
function elementor_role_api_widget_fail_notice()
{
    echo '<div class="notice notice-error"><p>' . esc_html__('Elementor needs to be activated to use the Elementor Role API Widget plugin.', 'eraw') . '</p></div>';
}

<?php

/*
Plugin Name: Auto Import Posts
Description: A custom plugin to fetch and create posts from an Mockaroo API response.
Version: 1.0
Author: Viktoriia Paster
Text Domain: auto-import-posts
*/

if (!defined('ABSPATH')) {
    exit;
}

// Define plugin path
define('AIP_PLUGIN_URL', plugin_dir_url(__FILE__));
define('AIP_PLUGIN_PATH', plugin_dir_path(__FILE__));

require_once AIP_PLUGIN_PATH . 'includes/admin.php';
require_once AIP_PLUGIN_PATH . 'includes/fetch-posts.php';
require_once AIP_PLUGIN_PATH . 'includes/helpers.php';
require_once AIP_PLUGIN_PATH . 'includes/insert-posts.php';
require_once AIP_PLUGIN_PATH . 'includes/hooks.php';
require_once AIP_PLUGIN_PATH . 'includes/shortcode.php';

register_activation_hook(__FILE__, 'aip_init_posts_schedule');
register_deactivation_hook(__FILE__, 'aip_remove_posts_schedule');

// Load admin files
add_action('init', 'aip_load_admin_files');

// Create custom cron hook
add_action('aip_cron_hook', 'aip_handle_insert_posts');

// Handle shortcode adding
add_shortcode('aip', 'aip_handle_shortcode');

// Add option page
add_action('admin_menu', 'aip_add_option_page');

// Add settings field
add_action('admin_init', 'aip_add_api_settings');

<?php

/**
 * Plugin Name:       WP open AI posts
 * Description:       A WordPress plugin to generate posts using the OpenAI API
 * Requires at least: 6.1
 * Requires PHP:      8.0
 * Version:           1.0.0
 * Author:            Maxim Tkachov
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       wp_open_ai_posts
 *
 * @package           wp-open-ai-posts
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

$public_path = plugin_dir_url(__FILE__);
define('WP_OPEN_AI_POSTS_PUBLIC_PATH', $public_path);

// Include the Singleton class
require_once plugin_dir_path(__FILE__) . 'includes/class-openai-plugin-singleton.php';

// Initialize the Singleton instance
OpenAI_Plugin_Singleton::get_instance();

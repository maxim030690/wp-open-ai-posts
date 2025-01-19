<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class OpenAI_Plugin_Singleton
{

    /**
     * @var OpenAI_Plugin_Singleton|null
     */
    private static ?self $instance = null;

    /**
     * @param string $plugin_url
     * @param string $plugin_path
     */
    private function __construct(
        private string $plugin_url = '',
        private string $plugin_path = ''
    ) {
        // Initialize your plugin functionalities here (e.g., hooks, actions)
        add_action('admin_menu', [$this, 'add_settings_page']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('admin_init', [$this, 'register_settings']);
        add_action('wp_ajax_generate_post', [$this, 'generate_post']);
    }

    /**
     * Public method to get the singleton instance
     *
     * @return self
     */
    public static function get_instance(): self
    {
        if (self::$instance === null) {
            // Initialize the instance with proper values for URL and path
            self::$instance = new self(plugin_dir_url(__FILE__), plugin_dir_path(__FILE__));
        }
        return self::$instance;
    }

    /**
     * Register settings for the plugin
     *
     * @return void
     */
    public function register_settings(): void
    {
        // Register the setting for the API Key
        register_setting('openai_options_group', 'openai_api_key');

        // Add a settings section
        add_settings_section(
            'openai_api_section',     // Section ID
            'OpenAI API Settings',    // Section title
            null,                     // Callback for section description
            'openai-plugin'           // Page slug where this section will appear
        );

        // Add a settings field for the OpenAI API Key
        add_settings_field(
            'openai_api_key_field',   // Field ID
            'OpenAI API Key',         // Field label
            [$this, 'api_key_field_callback'], // Callback for displaying the field
            'openai-plugin',          // Page slug
            'openai_api_section'      // Section ID
        );
    }

    /**
     * Callback function to display the input field for API Key
     *
     * @return void
     */
    public function api_key_field_callback(): void
    {
        $api_key = get_option('openai_api_key');
        echo '<input type="text" name="openai_api_key" value="' . esc_attr($api_key) . '" />';
    }

    /**
     * Enqueue JS file and localize AJAX URL
     *
     * @param string $hook
     * @return void
     */
    public function enqueue_scripts(string $hook): void
    {
        // Only enqueue scripts on the admin page where the plugin is active
        if ($hook === 'settings_page_openai-settings') {
            wp_enqueue_script('openai-plugin-js', WP_OPEN_AI_POSTS_PUBLIC_PATH . 'assets/js/openai-plugin.js', ['jquery'], null, true);

            // Localize the script with AJAX URL and nonce for security
            wp_localize_script('openai-plugin-js', 'openai_plugin_vars', [
                'ajax_url' => admin_url('admin-ajax.php'), // WordPress AJAX URL
                'nonce'    => wp_create_nonce('openai_plugin_nonce'), // Create nonce for security
                'api_key'  => get_option('openai_api_key') // Include the API key
            ]);
        }
    }

    /**
     * Add settings page to WordPress admin
     *
     * @return void
     */
    public function add_settings_page(): void
    {
        add_options_page(
            'OpenAI Plugin Settings',
            'OpenAI Settings',
            'manage_options',
            'openai-settings',
            [$this, 'render_settings_page']
        );
    }

    /**
     * Render the settings page
     *
     * @return void
     */
    public function render_settings_page(): void
    {
        ?>
        <div class="wrap">
            <h1>OpenAI Plugin Settings</h1>
            <form method="post" action="options.php">
                <?php settings_fields('openai_options_group'); ?>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row">OpenAI API Key</th>
                        <td><input type="text" name="openai_api_key" value="<?php echo esc_attr(get_option('openai_api_key')); ?>" /></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Custom Prompt for OpenAI</th>
                        <td>
                            <input type="text" id="promptField" name="openai_prompt" value="<?php echo esc_attr(get_option('openai_prompt', '')); ?>" />
                            <p class="description">Specify a custom prompt that will be sent to OpenAI when generating posts.</p>
                        </td>
                    </tr>
                </table>
                <?php submit_button('Save Settings'); ?>
            </form>

            <button id="generatePostButton">Generate Post</button>
            <div id="loadingSpinner" style="display:none;">Loading...</div>
        </div>
        <?php
    }

    /**
     * Generate post by communicating with OpenAI API
     *
     * @return void
     */
    public function generate_post(): void
    {
        // Verify nonce for security
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'openai_plugin_nonce')) {
            wp_send_json_error(['message' => 'Nonce verification failed.']);
        }

        // Get API key and prompt from the request
        $api_key = get_option('openai_api_key');
        $prompt = sanitize_text_field($_POST['prompt']);

        if (!$api_key || empty($prompt)) {
            wp_send_json_error(['message' => 'API key or prompt is missing.']);
        }

        $openai_url = 'https://api.openai.com/v1/chat/completions';

        $response = wp_remote_post($openai_url, [
            'body' => json_encode([
                'model' => 'gpt-3.5-turbo', // Correct model
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a helpful assistant.' // Optional: Set a system message to guide the model
                    ],
                    [
                        'role' => 'user',
                        'content' => 'Write an article for this topic: ' . $prompt // User prompt
                    ],
                ],
                'max_tokens' => 500, // Control the length of the output
            ]),
            'headers' => [
                'Authorization' => 'Bearer ' . $api_key, // Authorization header with your API key
                'Content-Type' => 'application/json', // Content type as JSON
            ],
            'timeout' => 30, // Increase timeout to 30 seconds
        ]);

        // Check if the response is a WP_Error object
        if (is_wp_error($response)) {
            // Log the error message if it's a WP_Error object
            error_log('OpenAI request failed with error: ' . $response->get_error_message());
            wp_send_json_error(['message' => 'Failed to connect to OpenAI.']);
        }

        // Ensure we get a successful response from OpenAI API
        $status_code = wp_remote_retrieve_response_code($response);
        if ($status_code !== 200) {
            // If the request was not successful, log the error and show an appropriate message
            error_log('OpenAI API returned error with status code: ' . $status_code);
            wp_send_json_error(['message' => 'Failed to retrieve a response from OpenAI.']);
        }

        // Log the response body for debugging
        $body = wp_remote_retrieve_body($response);
        error_log('OpenAI response body: ' . $body);

        $data = json_decode($body, true);
        $text = $data['choices'][0]['message']['content'];

        // Extract the title using regular expression
        preg_match('/^Title:\s*(.*)$/m', $text, $matches);

        // The title is stored in the first capture group
        $title = $matches[1] ?? 'No title found';

        // Extract the content (everything after the title)
        $content = preg_replace('/^Title:.*$/m', '', $text); // Remove the title line from the text
        $content = trim($content); // Remove leading/trailing whitespace

        wp_insert_post([
            'post_title'   => $title,
            'post_content' => $content,
            'post_status'  => 'publish',
            'post_author'  => get_current_user_id(),
        ]);

        wp_send_json_success(['message' => 'Post created successfully!']);
    }
}

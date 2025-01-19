# OpenAI Plugin for WordPress

**Plugin Name:** OpenAI Plugin  
**Plugin URI:** `https://yourpluginwebsite.com`  
**Description:**  
This plugin integrates the OpenAI API with your WordPress site, allowing you to easily generate posts based on user-defined prompts. The plugin supports secure API key management, real-time AJAX interactions, and dynamic post generation without page reloads.

**Version:** 1.0.0  
**Author:** Your Name  
**Author URI:** `https://yourwebsite.com`  
**License:** GPL2 or later  
**Text Domain:** openai-plugin  

---

## Features

- **API Key Integration**: Securely save and use your OpenAI API key within the WordPress settings.
- **Post Generation**: Dynamically generate posts based on user-provided prompts via OpenAI's language models.
- **Real-Time AJAX**: Uses ES6 `async/await` and `fetch()` to interact with the backend without page reloads.
- **PHP 8 Support**: The plugin is fully compatible with PHP 8+ features like constructor property promotion, typed properties, and `match` expressions for cleaner and more performant code.

---

## Installation

### 1. Install the Plugin

- Download the plugin zip file and upload it to your WordPress site via **Plugins > Add New > Upload Plugin**.
- Alternatively, clone the repository and upload the plugin folder directly into the `wp-content/plugins` directory.

### 2. Activate the Plugin

- Once uploaded, go to the **Plugins** section in the WordPress dashboard and activate the **OpenAI Plugin**.

### 3. Configure the Plugin

- After activation, go to **Settings > OpenAI Settings**.
- Enter your **OpenAI API Key** in the provided input field and save the settings.

---

## Usage

1. Once the plugin is activated and configured, navigate to the **OpenAI Plugin Settings** page.
2. Click the **Generate Post** button.
3. A prompt will appear asking you to enter a topic for the generated post.
4. After submitting the prompt, the plugin will call the OpenAI API and generate a post with the suggested topic. A new post will be created automatically using the generated content.

---

## Code Structure

The plugin utilizes modern PHP (PHP 8) and JavaScript (ES6) approaches for better performance, security, and maintainability.

### PHP 8 Features:

- **Constructor Property Promotion**: Reduces boilerplate code by promoting properties directly within the constructor.
- **Typed Properties**: Ensures better type safety and consistency by defining property types.
- **`match` Expressions**: Used in conditional logic for cleaner and more expressive code.
  
```php
// Example of PHP 8 constructor property promotion
public function __construct(
    private string $plugin_url = '',
    private string $plugin_path = ''
) {}
```

### JavaScript (ES6+) Features:

- **`async/await`**: Handles AJAX requests asynchronously, improving user experience with smoother interactions.
- **`fetch()`**: Replaces `$.ajax()` for making HTTP requests.
- **Arrow Functions**: Used for concise and readable code.
- **Template Literals**: Provides easy-to-read string interpolation.

```javascript
// Example of ES6+ (async/await) AJAX request
generatePostButton.addEventListener('click', async () => {
    const prompt = prompt('Enter a topic for the post:');
    const response = await fetch(openai_plugin_vars.ajax_url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ action: 'generate_post', nonce: openai_plugin_vars.nonce, prompt })
    });
    const data = await response.json();
    alert(data.success ? 'Post generated successfully!' : `Error: ${data.data.message}`);
});
```

---

## Frequently Asked Questions (FAQ)

### 1. **How can I use the plugin without an API key?**

Unfortunately, you need a valid OpenAI API key to generate posts using this plugin. You can obtain an API key by registering on [OpenAI's website](https://beta.openai.com/signup/).

### 2. **Can I use this plugin for generating custom content?**

Yes! The plugin allows you to specify the topic or theme for the generated posts. Simply input the desired subject in the prompt, and the plugin will use the OpenAI API to generate a relevant article.

### 3. **How do I troubleshoot if the plugin is not working?**

- Ensure that you’ve entered a valid OpenAI API key.
- Check your browser’s developer console for any JavaScript errors.
- If the issue persists, check the WordPress debug log for errors related to the plugin.

---

## Changelog

### Version 1.0.0 (2025-01-14)

- Initial release featuring:
  - Integration with OpenAI API to generate WordPress posts.
  - Secure API key management via WordPress settings page.
  - AJAX interaction for dynamic content generation (no page reloads).
  - Compatibility with PHP 8 features (constructor promotion, typed properties, `match` expressions).
  - Modern JavaScript (ES6+) used for AJAX and interactivity.

---

## Contributing

We welcome contributions! Feel free to fork the plugin, submit issues, or create pull requests to improve the functionality or add features. Please ensure that your code adheres to the WordPress coding standards and that all changes are properly tested.

---

## License

This plugin is licensed under the **GPL2** license. You can redistribute and/or modify it under the terms of the GPL2 license, which is available at [https://www.gnu.org/licenses/](https://www.gnu.org/licenses/).

---

### Notes:
- This readme includes **PHP 8** features like constructor property promotion and **ES6+** features (like `async/await`, `fetch`, and template literals) to highlight the modern approach in the code.
- It also contains sections about installation, usage, and contributing to help other developers get started with the plugin.
- The **Changelog** section includes a version history to track updates over time.

Let me know if you need further adjustments!

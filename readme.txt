=== OpenAI Plugin ===
Contributors: (Maxim Tkachov)
Tags: OpenAI, AI, GPT-3, Content Generation, API, WordPress, PHP 8
Requires at least: 5.6
Tested up to: 6.0
Requires PHP: 8.0
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==

OpenAI Plugin allows you to integrate OpenAI’s powerful GPT-3 language models with your WordPress site. With this plugin, you can automatically generate blog posts directly within the WordPress admin using the OpenAI API.

**Key Features**:
- **Generate Posts**: Automatically generate posts based on a prompt using OpenAI's GPT-3 or other supported models.
- **AJAX Content Generation**: Seamless, no-page reload AJAX integration for content generation.
- **API Key Integration**: Enter your OpenAI API key in the settings page.
- **Secure AJAX Calls**: Utilizes WordPress nonces for secure AJAX requests.
- **PHP 8 Compatible**: Fully supports PHP 8 features and performance improvements.

== Installation ==

1. Upload the plugin to your `wp-content/plugins` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Navigate to `Settings > OpenAI Settings` to configure your OpenAI API key.
4. Use the "Generate Post" button to start generating content.

== Frequently Asked Questions ==

= Do I need an OpenAI API key? =
Yes, you need a valid API key from OpenAI to use this plugin. You can obtain an API key from [OpenAI's platform](https://platform.openai.com/).

= Which OpenAI models are supported? =
The plugin currently supports GPT-3 models such as `text-davinci-003` and `gpt-3.5-turbo`. Future updates may include support for newer models like `gpt-4`.

== Changelog ==

= 1.0 =
* Initial release with basic functionality.
* Integration with OpenAI API for automatic content generation.
* Admin page for setting API key.
* AJAX integration for seamless post generation.

== Upgrade Notice ==

= 1.0 =
First stable release.

== Acknowledgements ==

- OpenAI API for providing the language models.
- The WordPress community for continuous support and contributions.

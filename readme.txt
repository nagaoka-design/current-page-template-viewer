=== Current Page Template Viewer ===
Contributors: nagaokadesign
Tags: development, template, debug, developer, theme
Requires at least: 5.0
Tested up to: 6.8
Requires PHP: 7.4
Stable tag: 1.0.1
License: GPL-2.0+
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Display current template file and directory name on screen for WordPress development.

== Description ==

This plugin helps WordPress developers by showing which template files are being used on the current page. It displays the current template file name and directory path in a convenient overlay, making it easy to identify which template is rendering the current page during development.

The plugin shows a small, unobtrusive display that can be clicked to reveal detailed information about all template files loaded for the current page.

**Key Features:**
* Shows current template file name
* Displays template directory path
* Click to view all included template files
* Configurable display position (top-left, top-right, bottom-left, bottom-right)
* Customizable background and text colors
* Admin-only display option for security
* Debug mode option (only shows when WP_DEBUG is enabled)
* Lightweight and performance-optimized
* Clean, modern interface

**Perfect for:**
* Theme developers
* WordPress developers
* Site debugging
* Template hierarchy understanding
* Development and staging environments

The plugin is designed to be completely safe and non-intrusive, with options to restrict visibility to administrators only.

== Installation ==

1. Upload the plugin files to `/wp-content/plugins/current-page-template-viewer/`
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Go to Settings > Current Page Template Viewer to configure display options
4. The template information will appear on the frontend according to your settings

== Frequently Asked Questions ==

= How do I enable the template display? =

After activating the plugin, it will automatically display template information. You can configure the display options by going to Settings > Current Page Template Viewer.

= How do I see all included template files? =

Click on the template display overlay on the frontend. This will open a popup showing all template files that were loaded for the current page.

= Can I change the display position? =

Yes, you can choose from four positions: top-left, top-right, bottom-left, or bottom-right through the plugin settings.

= Can I customize the colors? =

Yes, you can customize both the background color and text color. The plugin supports both hex colors (#ffffff) and rgba values (rgba(255,255,255,0.5)).

= Will this plugin slow down my website? =

No, this plugin is designed to be lightweight and only displays information when needed. It has minimal impact on performance.

= Can I restrict who sees the template information? =

Yes, you can set the plugin to only show template information to administrators, or you can set it to only display when WP_DEBUG is enabled.

= Is this plugin safe to use on production sites? =

While the plugin is safe, it's primarily intended for development and staging environments. The admin-only and debug-only options make it safer for production use, but we recommend disabling it on live sites.

= Does it work with child themes? =

Yes, the plugin correctly identifies and displays both parent and child theme template files.

= Can I hide the theme directory or template file name? =

Yes, you can choose to display only the theme directory, only the template file name, or both through the plugin settings.

== Screenshots ==

1. Template display overlay showing current template information
2. Popup window displaying all included template files
3. Plugin settings page with all configuration options

== Changelog ==

= 1.0.1 =
* Fixed variable escaping for WordPress.org security standards
* Updated all function and variable names to use unique prefixes
* Improved code structure and security
* Added external JavaScript file for better performance
* Enhanced popup functionality
* Better error handling

= 1.0.0 =
* Initial release
* Template file name display
* Directory path display
* Basic configuration options
* Popup showing included files
* Position and color customization

== Upgrade Notice ==

= 1.0.1 =
Security and code improvements. Recommended update for all users.

= 1.0.0 =
Initial release of Current Page Template Viewer plugin.
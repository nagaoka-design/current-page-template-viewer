<?php
/**
 * Plugin Name: Current Page Template Viewer
 * Plugin URI: https://github.com/nagaoka-design/current-page-template-viewer/
 * Description: Display current template file and directory name on screen
 * Version: 1.0.0
 * Author: Nagaoka Design Office
 * Author URI: https://nag-design.com
 * License: GPL-2.0+
 * Text Domain: current-page-template-viewer
 * Domain Path: /languages
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class Current_Page_Template_Viewer {
    // Default
    private $default_options = [
        'position' => 'top-right',
        'bg_color' => 'rgba(255, 255, 255, 0.5)',
        'text_color' => '#000000',
        'display_mode' => 'always',
        'enable_for_admins_only' => 'yes',
        'show_theme_directory' => 'yes',
        'show_template_file' => 'yes',
    ];

    // Instance
    private static $instance = null;

    /**
     * Get Instance
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {        
        // Add actions and filters
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('wp_footer', array($this, 'display_template_info'));
    }

    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_options_page(
            __('Current Page Template Viewer Settings', 'current-page-template-viewer'),
            __('Current Page Template Viewer', 'current-page-template-viewer'),
            'manage_options',
            'current-page-template-viewer',
            array($this, 'options_page')
        );
    }

    /**
     * Register settings
     */
    public function register_settings() {
        register_setting('current_page_template_viewer', 'current_page_template_viewer_options', array($this, 'sanitize_options'));

        add_settings_section(
            'current_page_template_viewer_section',
            __('Display Settings', 'current-page-template-viewer'),
            array($this, 'settings_section_callback'),
            'current_page_template_viewer'
        );

        add_settings_field(
            'position',
            __('Position', 'current-page-template-viewer'),
            array($this, 'position_field_callback'),
            'current_page_template_viewer',
            'current_page_template_viewer_section'
        );

        add_settings_field(
            'bg_color',
            __('Background Color', 'current-page-template-viewer'),
            array($this, 'bg_color_field_callback'),
            'current_page_template_viewer',
            'current_page_template_viewer_section'
        );

        add_settings_field(
            'text_color',
            __('Text Color', 'current-page-template-viewer'),
            array($this, 'text_color_field_callback'),
            'current_page_template_viewer',
            'current_page_template_viewer_section'
        );

        add_settings_field(
            'display_mode',
            __('Display Mode', 'current-page-template-viewer'),
            array($this, 'display_mode_callback'),
            'current_page_template_viewer',
            'current_page_template_viewer_section'
        );
        
        add_settings_field(
            'enable_for_admins_only',
            __('Show to Admins Only', 'current-page-template-viewer'),
            array($this, 'enable_for_admins_only_callback'),
            'current_page_template_viewer',
            'current_page_template_viewer_section'
        );
        
        add_settings_field(
            'show_theme_directory',
            __('Show Theme Directory', 'current-page-template-viewer'),
            array($this, 'show_theme_directory_callback'),
            'current_page_template_viewer',
            'current_page_template_viewer_section'
        );
        
        add_settings_field(
            'show_template_file',
            __('Show Template File', 'current-page-template-viewer'),
            array($this, 'show_template_file_callback'),
            'current_page_template_viewer',
            'current_page_template_viewer_section'
        );
    }

    /**
     * Sanitize options
     */
    public function sanitize_options($input) {
        $sanitized_input = array();

        if (isset($input['position'])) {
            $sanitized_input['position'] = sanitize_text_field($input['position']);
        }

        if (isset($input['bg_color'])) {
            $sanitized_input['bg_color'] = sanitize_text_field($input['bg_color']);
        }

        if (isset($input['text_color'])) {
            $sanitized_input['text_color'] = sanitize_text_field($input['text_color']);
        }

        if (isset($input['display_mode'])) {
            $sanitized_input['display_mode'] = sanitize_text_field($input['display_mode']);
        }

        if (isset($input['enable_for_admins_only'])) {
            $sanitized_input['enable_for_admins_only'] = sanitize_text_field($input['enable_for_admins_only']);
        }
        
        if (isset($input['show_theme_directory'])) {
            $sanitized_input['show_theme_directory'] = sanitize_text_field($input['show_theme_directory']);
        }
        
        if (isset($input['show_template_file'])) {
            $sanitized_input['show_template_file'] = sanitize_text_field($input['show_template_file']);
        }

        return $sanitized_input;
    }

    /**
     * Section description
     */
    public function settings_section_callback() {
        echo esc_html__('Configure how template information is displayed.', 'current-page-template-viewer');
    }

    /**
     * Position field
     */
    public function position_field_callback() {
        $options = $this->get_options();
        ?>
        <select name="current_page_template_viewer_options[position]">
            <option value="top-left" <?php selected($options['position'], 'top-left'); ?>><?php esc_html_e('Top Left', 'current-page-template-viewer'); ?></option>
            <option value="top-right" <?php selected($options['position'], 'top-right'); ?>><?php esc_html_e('Top Right', 'current-page-template-viewer'); ?></option>
            <option value="bottom-left" <?php selected($options['position'], 'bottom-left'); ?>><?php esc_html_e('Bottom Left', 'current-page-template-viewer'); ?></option>
            <option value="bottom-right" <?php selected($options['position'], 'bottom-right'); ?>><?php esc_html_e('Bottom Right', 'current-page-template-viewer'); ?></option>
        </select>
        <?php
    }

    /**
     * Background color field
     */
    public function bg_color_field_callback() {
        $options = $this->get_options();
        ?>
        <input type="text" name="current_page_template_viewer_options[bg_color]" value="<?php echo esc_attr($options['bg_color']); ?>" class="color-picker" />
        <p class="description"><?php esc_html_e('Example: rgba(255, 255, 255, 0.5) or #ffffff', 'current-page-template-viewer'); ?></p>
        <?php
    }

    /**
     * Text color field
     */
    public function text_color_field_callback() {
        $options = $this->get_options();
        ?>
        <input type="text" name="current_page_template_viewer_options[text_color]" value="<?php echo esc_attr($options['text_color']); ?>" class="color-picker" />
        <?php
    }

    /**
     * Display mode field
     */
    public function display_mode_callback() {
        $options = $this->get_options();
        ?>
        <select name="current_page_template_viewer_options[display_mode]">
            <option value="always" <?php selected($options['display_mode'], 'always'); ?>><?php esc_html_e('Always Display', 'current-page-template-viewer'); ?></option>
            <option value="debug_only" <?php selected($options['display_mode'], 'debug_only'); ?>><?php esc_html_e('Only When WP_DEBUG is Enabled', 'current-page-template-viewer'); ?></option>
        </select>
        <p class="description"><?php esc_html_e('Set when template information should be displayed', 'current-page-template-viewer'); ?></p>
        <?php
    }

    /**
     * Admin only field
     */
    public function enable_for_admins_only_callback() {
        $options = $this->get_options();
        ?>
        <select name="current_page_template_viewer_options[enable_for_admins_only]">
            <option value="yes" <?php selected($options['enable_for_admins_only'], 'yes'); ?>><?php esc_html_e('Yes', 'current-page-template-viewer'); ?></option>
            <option value="no" <?php selected($options['enable_for_admins_only'], 'no'); ?>><?php esc_html_e('No', 'current-page-template-viewer'); ?></option>
        </select>
        <p class="description"><?php esc_html_e('If "No" is selected, the template info will be shown to all users', 'current-page-template-viewer'); ?></p>
        <?php
    }
    
    /**
     * Show theme directory field
     */
    public function show_theme_directory_callback() {
        $options = $this->get_options();
        ?>
        <select name="current_page_template_viewer_options[show_theme_directory]">
            <option value="yes" <?php selected($options['show_theme_directory'], 'yes'); ?>><?php esc_html_e('Yes', 'current-page-template-viewer'); ?></option>
            <option value="no" <?php selected($options['show_theme_directory'], 'no'); ?>><?php esc_html_e('No', 'current-page-template-viewer'); ?></option>
        </select>
        <p class="description"><?php esc_html_e('Display the theme directory name', 'current-page-template-viewer'); ?></p>
        <?php
    }
    
    /**
     * Show template file field
     */
    public function show_template_file_callback() {
        $options = $this->get_options();
        ?>
        <select name="current_page_template_viewer_options[show_template_file]">
            <option value="yes" <?php selected($options['show_template_file'], 'yes'); ?>><?php esc_html_e('Yes', 'current-page-template-viewer'); ?></option>
            <option value="no" <?php selected($options['show_template_file'], 'no'); ?>><?php esc_html_e('No', 'current-page-template-viewer'); ?></option>
        </select>
        <p class="description"><?php esc_html_e('Display the template file name', 'current-page-template-viewer'); ?></p>
        <?php
    }

    /**
     * Settings page
     */
    public function options_page() {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <form action="options.php" method="post">
                <?php
                settings_fields('current_page_template_viewer');
                do_settings_sections('current_page_template_viewer');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    /**
     * Get options
     */
    public function get_options() {
        $options = get_option('current_page_template_viewer_options', $this->default_options);
        return wp_parse_args($options, $this->default_options);
    }

    /**
     * Get included theme files
     */
    public function get_included_theme_files() {
        $included_files = get_included_files();
        $theme_directory = get_template_directory();
        $stylesheet_directory = get_stylesheet_directory();
        $theme_files = array();

        foreach ($included_files as $file) {
            // Check if file is in current theme or child theme directory
            if (strpos($file, $theme_directory) === 0 || strpos($file, $stylesheet_directory) === 0) {
                // Get relative path from theme directory
                $relative_path = '';
                if (strpos($file, $stylesheet_directory) === 0) {
                    $relative_path = str_replace($stylesheet_directory . '/', '', $file);
                    $theme_name = basename($stylesheet_directory);
                } else {
                    $relative_path = str_replace($theme_directory . '/', '', $file);
                    $theme_name = basename($theme_directory);
                }
                
                // Skip functions.php and common WordPress files that are always loaded
                if (!in_array(basename($file), array('functions.php', 'style.css'))) {
                    $theme_files[] = $theme_name . '/' . $relative_path;
                }
            }
        }

        return array_unique($theme_files);
    }

    /**
     * Display template info on frontend
     */
    public function display_template_info() {
        $options = $this->get_options();
        
        // If display mode is debug_only and WP_DEBUG is not enabled, don't show
        if ($options['display_mode'] === 'debug_only' && (!defined('WP_DEBUG') || !WP_DEBUG)) {
            return;
        }

        // Check for admin-only setting
        if ($options['enable_for_admins_only'] === 'yes' && !current_user_can('manage_options')) {
            return;
        }
        
        // If both display options are set to 'no', don't show anything
        if ($options['show_theme_directory'] === 'no' && $options['show_template_file'] === 'no') {
            return;
        }

        global $template;
        $template_name = basename($template, '.php');
        $template_dir = basename(dirname($template));

        // Get included theme files
        $included_files = $this->get_included_theme_files();

        // Set CSS based on position
        $position_css = $this->get_position_css($options['position']);

        echo '<div id="current-page-template-viewer-display" style="position: fixed; ' . esc_attr($position_css) . ' z-index: 9999; cursor: pointer;">';
        echo '<code style="background-color: ' . esc_attr($options['bg_color']) . '; ' .
             'padding: 0.5em 1em; ' .
             'font-size: 12px; ' .
             'line-height: 1.5em; ' .
             'border-radius: 6px; ' .
             'color: ' . esc_attr($options['text_color']) . '; ' .
             'display: block;">';
             
        // Build display string
        $display_parts = array();
        
        if ($options['show_theme_directory'] === 'yes') {
            $display_parts[] = esc_html($template_dir);
        }
        
        if ($options['show_template_file'] === 'yes') {
            $display_parts[] = esc_html($template_name) . '.php';
        }
        
        // Display as "ThemeName/template.php" format
        echo esc_html(implode('/', $display_parts));
        
        echo "</code>";
        echo "</div>\n";

        // Popup modal
        echo '<div id="current-page-template-viewer-popup" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.7); z-index: 10000;">';
        echo '<div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: white; padding: 20px; border-radius: 8px; max-width: 600px; max-height: 80%; overflow-y: auto; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">';
        
        echo '<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px;">';
        echo '<h3 style="margin: 0; color: #333;">' . esc_html__('Included Template Files', 'current-page-template-viewer') . '</h3>';
        echo '<span id="current-page-template-viewer-close" style="cursor: pointer; font-size: 24px; color: #999; font-weight: bold;">&times;</span>';
        echo '</div>';
        
        if (!empty($included_files)) {
            echo '<ul style="list-style: none; padding: 0; margin: 0;">';
            foreach ($included_files as $file) {
                echo '<li style="padding: 8px 0; border-bottom: 1px solid #f0f0f0; font-family: monospace; font-size: 13px; color: #666;">';
                echo esc_html($file);
                echo '</li>';
            }
            echo '</ul>';
        } else {
            echo '<p style="color: #999; font-style: italic;">' . esc_html__('No additional template files found.', 'current-page-template-viewer') . '</p>';
        }
        
        echo '</div>';
        echo '</div>';

        // JavaScript for popup functionality
        ?>
        <script>
        (function() {
            var display = document.getElementById('current-page-template-viewer-display');
            var popup = document.getElementById('current-page-template-viewer-popup');
            var close = document.getElementById('current-page-template-viewer-close');

            if (display && popup && close) {
                display.addEventListener('click', function(e) {
                    e.preventDefault();
                    popup.style.display = 'block';
                });

                close.addEventListener('click', function(e) {
                    e.preventDefault();
                    popup.style.display = 'none';
                });

                popup.addEventListener('click', function(e) {
                    if (e.target === popup) {
                        popup.style.display = 'none';
                    }
                });

                // ESC key to close
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && popup.style.display === 'block') {
                        popup.style.display = 'none';
                    }
                });
            }
        })();
        </script>
        <?php
    }

    /**
     * Get CSS based on position
     */
    private function get_position_css($position) {
        switch ($position) {
            case 'top-left':
                return 'top: 10px; left: 10px; ';
            case 'bottom-left':
                return 'bottom: 10px; left: 10px; ';
            case 'bottom-right':
                return 'bottom: 10px; right: 10px; ';
            case 'top-right':
            default:
                return 'top: 10px; right: 10px; ';
        }
    }
}

// Initialize plugin
function current_page_template_viewer_init() {
    Current_Page_Template_Viewer::get_instance();
}
add_action('plugins_loaded', 'current_page_template_viewer_init');
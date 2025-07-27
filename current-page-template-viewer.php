<?php

/**
 * Plugin Name: Current Page Template Viewer
 * Plugin URI: https://github.com/nagaoka-design/current-page-template-viewer/
 * Description: Display current template file and directory name on screen
 * Version: 1.0.1
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

class CURRPATE_Current_Page_Template_Viewer
{
    // Default options
    private $currpate_default_options = [
        'position' => 'top-right',
        'bg_color' => 'rgba(255, 255, 255, 0.5)',
        'text_color' => '#000000',
        'display_mode' => 'always',
        'enable_for_admins_only' => 'yes',
        'show_theme_directory' => 'yes',
        'show_template_file' => 'yes',
    ];

    // Instance
    private static $currpate_instance = null;

    /**
     * Get Instance
     */
    public static function currpate_get_instance()
    {
        if (null === self::$currpate_instance) {
            self::$currpate_instance = new self();
        }
        return self::$currpate_instance;
    }

    /**
     * Constructor
     */
    private function __construct()
    {
        add_action('admin_menu', array($this, 'currpate_add_admin_menu'));
        add_action('admin_init', array($this, 'currpate_register_settings'));
        add_action('wp_footer', array($this, 'currpate_display_template_info'));
        add_action('wp_enqueue_scripts', array($this, 'currpate_enqueue_scripts'));
    }

    /**
     * Enqueue scripts
     */
    public function currpate_enqueue_scripts()
    {
        wp_register_script(
            'currpate-popup-script',
            plugin_dir_url(__FILE__) . 'js/currpate-popup.js',
            array(),
            '1.0.1',
            true
        );
    }

    /**
     * Add admin menu
     */
    public function currpate_add_admin_menu()
    {
        add_options_page(
            __('Current Page Template Viewer Settings', 'current-page-template-viewer'),
            __('Current Page Template Viewer', 'current-page-template-viewer'),
            'manage_options',
            'current-page-template-viewer',
            array($this, 'currpate_options_page')
        );
    }

    /**
     * Register settings
     */
    public function currpate_register_settings()
    {
        register_setting('currpate_current_page_template_viewer', 'currpate_current_page_template_viewer_options', array($this, 'currpate_sanitize_options'));

        add_settings_section(
            'currpate_current_page_template_viewer_section',
            __('Display Settings', 'current-page-template-viewer'),
            array($this, 'currpate_settings_section_callback'),
            'currpate_current_page_template_viewer'
        );

        // Individual field registration with correct callback names
        add_settings_field(
            'position',
            __('Position', 'current-page-template-viewer'),
            array($this, 'currpate_position_field_callback'),
            'currpate_current_page_template_viewer',
            'currpate_current_page_template_viewer_section'
        );

        add_settings_field(
            'bg_color',
            __('Background Color', 'current-page-template-viewer'),
            array($this, 'currpate_bg_color_field_callback'),
            'currpate_current_page_template_viewer',
            'currpate_current_page_template_viewer_section'
        );

        add_settings_field(
            'text_color',
            __('Text Color', 'current-page-template-viewer'),
            array($this, 'currpate_text_color_field_callback'),
            'currpate_current_page_template_viewer',
            'currpate_current_page_template_viewer_section'
        );

        add_settings_field(
            'display_mode',
            __('Display Mode', 'current-page-template-viewer'),
            array($this, 'currpate_display_mode_callback'),
            'currpate_current_page_template_viewer',
            'currpate_current_page_template_viewer_section'
        );

        add_settings_field(
            'enable_for_admins_only',
            __('Show to Admins Only', 'current-page-template-viewer'),
            array($this, 'currpate_enable_for_admins_only_callback'),
            'currpate_current_page_template_viewer',
            'currpate_current_page_template_viewer_section'
        );

        add_settings_field(
            'show_theme_directory',
            __('Show Theme Directory', 'current-page-template-viewer'),
            array($this, 'currpate_show_theme_directory_callback'),
            'currpate_current_page_template_viewer',
            'currpate_current_page_template_viewer_section'
        );

        add_settings_field(
            'show_template_file',
            __('Show Template File', 'current-page-template-viewer'),
            array($this, 'currpate_show_template_file_callback'),
            'currpate_current_page_template_viewer',
            'currpate_current_page_template_viewer_section'
        );
    }

    /**
     * Sanitize options
     */
    public function currpate_sanitize_options($currpate_input)
    {
        $currpate_sanitized_input = array();

        $currpate_allowed_fields = array('position', 'bg_color', 'text_color', 'display_mode', 'enable_for_admins_only', 'show_theme_directory', 'show_template_file');

        foreach ($currpate_allowed_fields as $currpate_field) {
            if (isset($currpate_input[$currpate_field])) {
                $currpate_sanitized_input[$currpate_field] = sanitize_text_field($currpate_input[$currpate_field]);
            }
        }

        return $currpate_sanitized_input;
    }

    /**
     * Section description
     */
    public function currpate_settings_section_callback()
    {
        echo esc_html__('Configure how template information is displayed.', 'current-page-template-viewer');
    }

    /**
     * Position field
     */
    public function currpate_position_field_callback()
    {
        $currpate_options = $this->currpate_get_options();
        $currpate_positions = array(
            'top-left' => __('Top Left', 'current-page-template-viewer'),
            'top-right' => __('Top Right', 'current-page-template-viewer'),
            'bottom-left' => __('Bottom Left', 'current-page-template-viewer'),
            'bottom-right' => __('Bottom Right', 'current-page-template-viewer'),
        );
?>
        <select name="currpate_current_page_template_viewer_options[position]">
            <?php foreach ($currpate_positions as $currpate_value => $currpate_label) : ?>
                <option value="<?php echo esc_attr($currpate_value); ?>" <?php selected($currpate_options['position'], $currpate_value); ?>>
                    <?php echo esc_html($currpate_label); ?>
                </option>
            <?php endforeach; ?>
        </select>
    <?php
    }

    /**
     * Background color field
     */
    public function currpate_bg_color_field_callback()
    {
        $currpate_options = $this->currpate_get_options();
    ?>
        <input type="text" name="currpate_current_page_template_viewer_options[bg_color]" value="<?php echo esc_attr($currpate_options['bg_color']); ?>" class="color-picker" />
        <p class="description"><?php esc_html_e('Example: rgba(255, 255, 255, 0.5) or #ffffff', 'current-page-template-viewer'); ?></p>
    <?php
    }

    /**
     * Text color field
     */
    public function currpate_text_color_field_callback()
    {
        $currpate_options = $this->currpate_get_options();
    ?>
        <input type="text" name="currpate_current_page_template_viewer_options[text_color]" value="<?php echo esc_attr($currpate_options['text_color']); ?>" class="color-picker" />
    <?php
    }

    /**
     * Display mode field
     */
    public function currpate_display_mode_callback()
    {
        $currpate_options = $this->currpate_get_options();
        $currpate_modes = array(
            'always' => __('Always Display', 'current-page-template-viewer'),
            'debug_only' => __('Only When WP_DEBUG is Enabled', 'current-page-template-viewer'),
        );
    ?>
        <select name="currpate_current_page_template_viewer_options[display_mode]">
            <?php foreach ($currpate_modes as $currpate_value => $currpate_label) : ?>
                <option value="<?php echo esc_attr($currpate_value); ?>" <?php selected($currpate_options['display_mode'], $currpate_value); ?>>
                    <?php echo esc_html($currpate_label); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <p class="description"><?php esc_html_e('Set when template information should be displayed', 'current-page-template-viewer'); ?></p>
    <?php
    }

    /**
     * Admin only field
     */
    public function currpate_enable_for_admins_only_callback()
    {
        $currpate_options = $this->currpate_get_options();
        $currpate_yes_no_options = array(
            'yes' => __('Yes', 'current-page-template-viewer'),
            'no' => __('No', 'current-page-template-viewer'),
        );
    ?>
        <select name="currpate_current_page_template_viewer_options[enable_for_admins_only]">
            <?php foreach ($currpate_yes_no_options as $currpate_value => $currpate_label) : ?>
                <option value="<?php echo esc_attr($currpate_value); ?>" <?php selected($currpate_options['enable_for_admins_only'], $currpate_value); ?>>
                    <?php echo esc_html($currpate_label); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <p class="description"><?php esc_html_e('If "No" is selected, the template info will be shown to all users', 'current-page-template-viewer'); ?></p>
    <?php
    }

    /**
     * Show theme directory field
     */
    public function currpate_show_theme_directory_callback()
    {
        $currpate_options = $this->currpate_get_options();
        $currpate_yes_no_options = array(
            'yes' => __('Yes', 'current-page-template-viewer'),
            'no' => __('No', 'current-page-template-viewer'),
        );
    ?>
        <select name="currpate_current_page_template_viewer_options[show_theme_directory]">
            <?php foreach ($currpate_yes_no_options as $currpate_value => $currpate_label) : ?>
                <option value="<?php echo esc_attr($currpate_value); ?>" <?php selected($currpate_options['show_theme_directory'], $currpate_value); ?>>
                    <?php echo esc_html($currpate_label); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <p class="description"><?php esc_html_e('Display the theme directory name', 'current-page-template-viewer'); ?></p>
    <?php
    }

    /**
     * Show template file field
     */
    public function currpate_show_template_file_callback()
    {
        $currpate_options = $this->currpate_get_options();
        $currpate_yes_no_options = array(
            'yes' => __('Yes', 'current-page-template-viewer'),
            'no' => __('No', 'current-page-template-viewer'),
        );
    ?>
        <select name="currpate_current_page_template_viewer_options[show_template_file]">
            <?php foreach ($currpate_yes_no_options as $currpate_value => $currpate_label) : ?>
                <option value="<?php echo esc_attr($currpate_value); ?>" <?php selected($currpate_options['show_template_file'], $currpate_value); ?>>
                    <?php echo esc_html($currpate_label); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <p class="description"><?php esc_html_e('Display the template file name', 'current-page-template-viewer'); ?></p>
    <?php
    }

    /**
     * Settings page
     */
    public function currpate_options_page()
    {
    ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <form action="options.php" method="post">
                <?php
                settings_fields('currpate_current_page_template_viewer');
                do_settings_sections('currpate_current_page_template_viewer');
                submit_button();
                ?>
            </form>
        </div>
<?php
    }

    /**
     * Get options
     */
    public function currpate_get_options()
    {
        $currpate_options = get_option('currpate_current_page_template_viewer_options', $this->currpate_default_options);
        return wp_parse_args($currpate_options, $this->currpate_default_options);
    }

    /**
     * Get included theme files
     */
    public function currpate_get_included_theme_files()
    {
        $currpate_included_files = get_included_files();
        $currpate_theme_directory = get_template_directory();
        $currpate_stylesheet_directory = get_stylesheet_directory();
        $currpate_theme_files = array();

        foreach ($currpate_included_files as $currpate_file) {
            if (strpos($currpate_file, $currpate_theme_directory) === 0 || strpos($currpate_file, $currpate_stylesheet_directory) === 0) {
                $currpate_relative_path = '';
                if (strpos($currpate_file, $currpate_stylesheet_directory) === 0) {
                    $currpate_relative_path = str_replace($currpate_stylesheet_directory . '/', '', $currpate_file);
                    $currpate_theme_name = basename($currpate_stylesheet_directory);
                } else {
                    $currpate_relative_path = str_replace($currpate_theme_directory . '/', '', $currpate_file);
                    $currpate_theme_name = basename($currpate_theme_directory);
                }

                if (!in_array(basename($currpate_file), array('functions.php', 'style.css'))) {
                    $currpate_theme_files[] = $currpate_theme_name . '/' . $currpate_relative_path;
                }
            }
        }

        return array_unique($currpate_theme_files);
    }

    /**
     * Display template info on frontend
     */
    public function currpate_display_template_info()
    {
        $currpate_options = $this->currpate_get_options();

        // Check display conditions
        if ($currpate_options['display_mode'] === 'debug_only' && (!defined('WP_DEBUG') || !WP_DEBUG)) {
            return;
        }

        if ($currpate_options['enable_for_admins_only'] === 'yes' && !current_user_can('manage_options')) {
            return;
        }

        if ($currpate_options['show_theme_directory'] === 'no' && $currpate_options['show_template_file'] === 'no') {
            return;
        }

        // Get current template information - 修正: グローバル変数も独自プレフィックスを使用
        $currpate_current_template_path = get_page_template();
        if (empty($currpate_current_template_path)) {
            global $currpate_template;
            $currpate_current_template_path = $currpate_template;
        }

        $currpate_template_name = basename($currpate_current_template_path, '.php');
        $currpate_template_dir = basename(dirname($currpate_current_template_path));

        // Get included theme files
        $currpate_included_files = $this->currpate_get_included_theme_files();

        // Set CSS based on position
        $currpate_position_css = $this->currpate_get_position_css($currpate_options['position']);

        // Output display element
        echo '<div id="currpate-current-page-template-viewer-display" style="position: fixed; ' . esc_attr($currpate_position_css) . ' z-index: 9999; cursor: pointer;">';
        echo '<code style="background-color: ' . esc_attr($currpate_options['bg_color']) . '; ' .
            'padding: 0.5em 1em; ' .
            'font-size: 12px; ' .
            'line-height: 1.5em; ' .
            'border-radius: 6px; ' .
            'color: ' . esc_attr($currpate_options['text_color']) . '; ' .
            'display: block;">';

        // Build display string
        $currpate_display_parts = array();

        if ($currpate_options['show_theme_directory'] === 'yes') {
            $currpate_display_parts[] = esc_html($currpate_template_dir);
        }

        if ($currpate_options['show_template_file'] === 'yes') {
            $currpate_display_parts[] = esc_html($currpate_template_name) . '.php';
        }

        echo esc_html(implode('/', $currpate_display_parts));
        echo "</code></div>\n";

        // Output popup modal
        $this->currpate_output_popup_modal($currpate_included_files);

        // Enqueue external script
        wp_enqueue_script('currpate-popup-script');
    }

    /**
     * Output popup modal HTML
     */
    private function currpate_output_popup_modal($currpate_included_files)
    {
        echo '<div id="currpate-current-page-template-viewer-popup" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.7); z-index: 10000;">';
        echo '<div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: white; padding: 20px; border-radius: 8px; max-width: 600px; max-height: 80%; overflow-y: auto; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">';

        echo '<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px;">';
        echo '<h3 style="margin: 0; color: #333;">' . esc_html__('Included Template Files', 'current-page-template-viewer') . '</h3>';
        echo '<span id="currpate-current-page-template-viewer-close" style="cursor: pointer; font-size: 24px; color: #999; font-weight: bold;">&times;</span>';
        echo '</div>';

        if (!empty($currpate_included_files)) {
            echo '<ul style="list-style: none; padding: 0; margin: 0;">';
            foreach ($currpate_included_files as $currpate_file) {
                echo '<li style="padding: 8px 0; border-bottom: 1px solid #f0f0f0; font-family: monospace; font-size: 13px; color: #666;">';
                echo esc_html($currpate_file);
                echo '</li>';
            }
            echo '</ul>';
        } else {
            echo '<p style="color: #999; font-style: italic;">' . esc_html__('No additional template files found.', 'current-page-template-viewer') . '</p>';
        }

        echo '</div></div>';
    }

    /**
     * Get CSS based on position
     */
    private function currpate_get_position_css($currpate_position)
    {
        $currpate_position_map = array(
            'top-left' => 'top: 10px; left: 10px;',
            'bottom-left' => 'bottom: 10px; left: 10px;',
            'bottom-right' => 'bottom: 10px; right: 10px;',
            'top-right' => 'top: 10px; right: 10px;',
        );

        return isset($currpate_position_map[$currpate_position]) ? $currpate_position_map[$currpate_position] : $currpate_position_map['top-right'];
    }
}

// Initialize plugin
function currpate_current_page_template_viewer_init()
{
    CURRPATE_Current_Page_Template_Viewer::currpate_get_instance();
}
add_action('plugins_loaded', 'currpate_current_page_template_viewer_init');

<?php
/**
 * Uninstall Current Page Template Viewer
 * 
 * This file is executed when the plugin is deleted from WordPress.
 * It removes all plugin data from the database.
 * 
 * @package Current_Page_Template_Viewer
 */

// If uninstall not called from WordPress, then exit.
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Delete plugin options
delete_option('current_page_template_viewer_options');

// For multisite installations
if (is_multisite()) {
    $sites = get_sites(array(
        'fields' => 'ids',
        'number' => 0, // Get all sites
    ));
    
    foreach ($sites as $site_id) {
        switch_to_blog($site_id);
        delete_option('current_page_template_viewer_options');
        restore_current_blog();
    }
}
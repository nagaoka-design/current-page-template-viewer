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
    global $wpdb;
    $blog_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
    $original_blog_id = get_current_blog_id();
    
    foreach ($blog_ids as $blog_id) {
        switch_to_blog($blog_id);
        delete_option('current_page_template_viewer_options');
    }
    
    switch_to_blog($original_blog_id);
}
<?php

/**
 * @package   Flower Tree
 * @author    Khoi Tran <khoitq.1992@gmail.com>
 *
 * Plugin Name:     Flower Tree
 * Description:     Flower Tree presentation plugin
 * Version:         1.0
 * Author:          Khoi Tran
 * Text Domain:     flower-tree
 * Domain Path:     /languages
 * Requires PHP:    7.4
 */

// If this file is called directly, abort.
if ( !defined( 'ABSPATH' ) ) {
	die( 'We\'re sorry, but you can not directly access this file.' );
}

define( 'PN_VERSION', '1.0' );
define( 'PN_TEXTDOMAIN', 'flower-tree' );
define( 'PN_NAME', 'Flower Tree' );
define( 'PN_PLUGIN_ROOT', plugin_dir_path( __FILE__ ) );
define( 'PN_PLUGIN_ABSOLUTE', __FILE__ );


// Add WordPress menu item for Flower Tree
function pn_add_menu_item() {
    add_menu_page(
        'Flower Tree',        // Page title
        'Flower Tree',        // Menu title
        'manage_options',     // Capability required to access
        'flower-tree-menu',   // Menu slug
        'pn_render_menu_page', // Callback function to render the menu page
        'dashicons-palmtree', // Icon for the menu item (change to your preference)
        6                     // Menu position (adjust as needed)
    );
}
add_action( 'admin_menu', 'pn_add_menu_item' );


// Add shortcode that displays Flower Tree menu
function pn_flower_tree_shortcode() {
    ob_start();

	// render
	wp_nav_menu(
        array(
            'menu' => 'flower-tree-menu',
            'menu_class' => 'nav-menu',
            'container' => false,
            'fallback_cb' => 'pn_fallback_cb',
        )
    );

	echo ob_get_clean();
}
add_shortcode( 'flower_tree_display', 'pn_flower_tree_shortcode' );


// Activate the plugin and check for ACF plugin
function pn_plugin_activation() {
    if ( !is_plugin_active( 'advanced-custom-fields/acf.php' ) ) {
        deactivate_plugins( plugin_basename( __FILE__ ) );

        wp_die( 'The Flower Tree plugin requires the Advanced Custom Fields (ACF) plugin. Please install and activate ACF to use this plugin.' );
    }
}
register_activation_hook( __FILE__, 'pn_plugin_activation' );


// Deactivate the plugin when ACF plugin is deactivated
function pn_plugin_deactivation() {
}
register_deactivation_hook( __FILE__, 'pn_plugin_deactivation' );


// Register Custom Post Type
function register_flower_post_type() {
    $labels = array(
        'name'                  => 'Flowers',
        'singular_name'         => 'Flower',
        'menu_name'             => 'Flowers',
        'all_items'             => 'All Flowers',
        'add_new'               => 'Add New',
        'add_new_item'          => 'Add New Flower',
        'edit_item'             => 'Edit Flower',
        'new_item'              => 'New Flower',
        'view_item'             => 'View Flower',
        'search_items'          => 'Search Flowers',
        'not_found'             => 'No flowers found',
        'not_found_in_trash'    => 'No flowers found in Trash',
        'featured_image'        => 'Featured Image',
        'set_featured_image'    => 'Set featured image',
        'remove_featured_image' => 'Remove featured image',
        'use_featured_image'    => 'Use as featured image',
        'archives'              => 'Flower Archives',
        'insert_into_item'      => 'Insert into flower',
        'uploaded_to_this_item' => 'Uploaded to this flower',
        'filter_items_list'     => 'Filter flowers list',
        'items_list_navigation' => 'Flowers list navigation',
        'items_list'            => 'Flowers list',
    );
    $args = array(
        'label'                 => 'Flower',
        'description'           => 'Custom post type for flowers',
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
        'public'                => true,
        'menu_icon'             => 'dashicons-palmtree', // You can change the icon
        'rewrite'               => array( 'slug' => 'flowers' ),
        'has_archive'           => true,
        'hierarchical'          => false,
    );
    register_post_type( 'flower', $args );
}
add_action( 'init', 'register_flower_post_type' );

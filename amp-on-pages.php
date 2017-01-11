<?php
/**
 * @package     Amp_On_Pages
 * @author      ryanml
 * @license     GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name: Amp On Pages
 * Plugin URI:  https://github.com/ryanml
 * Description: Quick and light way to get amp on 'page' post types
 * Version:     0.0.1
 */

define( 'AOP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
include_once( AOP_PLUGIN_DIR . 'includes/class-amp-on-pages.php');

// This plugin is dependent on the AMP plugin. 
if ( ! is_plugin_active( 'amp/amp.php' ) )
    wp_die( __( 'Error: You must have the Amp plugin installed.' ) ); 

// Create new instance of Amp_On_Pages class
$amp_on_pages = new Amp_On_Pages();

// If the Glue for Yoast SEO & AMP plugin is active, we need to enable pages as supported post types.
if ( is_plugin_active( 'glue-for-yoast-seo-amp/yoastseo-amp.php' ) )
    $amp_on_pages->enable_pages_in_glue();
?>
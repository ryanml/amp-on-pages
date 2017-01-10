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

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

if ( ! is_plugin_active( 'amp/amp.php' ) )
    wp_die( __( 'Error: You must have the Amp plugin installed.' ) ); 


class Amp_On_Pages {

    public function __construct() {

        add_action(
            'amp_init',
            [
                $this,
                'addAmpToPages'
            ]
        );

        add_action(
            'init',
            [
                $this,
                'ampPageRewrite'
            ]
        );

    }

    public function addAmpToPages() {

        add_post_type_support( 'page', AMP_QUERY_VAR );

    }

    public function ampPageRewrite() {

        $exp_uri = explode( '/amp', $_SERVER['REQUEST_URI'] );

        $post_id = url_to_postid( $exp_uri[0] );

        $post_type = get_post_type( $post_id );

        if ( $post_type !== 'page' )
            return;

        $redirect = "index.php?page_id={$post_id}&amp=1";

        add_rewrite_rule( '(.+)/amp', $redirect, 'top' );   

        flush_rewrite_rules();
    }

}

new Amp_On_Pages();
?>
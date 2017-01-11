<?php
/**
 * The Amp_On_Pages class handles all of the logic
 * for enabling AMP on page post types.
 *
 * @since 0.0.1
 *
 * @package Amp_On_Pages
 */

class Amp_On_Pages {

    /**
     * Constructor
     *
     * @since 0.0.1
     * @access public
     */
    public function __construct() {

        add_action(
            'amp_init',
            [
                $this,
                'enable_amp_on_pages'
            ]
        );

        add_action(
            'init',
            [
                $this,
                'amp_page_rewrite'
            ]
        );

    }

    /**
     * Registers post type support for amp pages
     *
     * @since 0.0.1
     * @access public
     */
    public function enable_amp_on_pages() {

        add_post_type_support( 'page', AMP_QUERY_VAR );

    }

    /**
     * Handles page requests and directs to appropriate AMP enabled page.
     *
     * @since 0.0.1
     * @access public
     */
    public function amp_page_rewrite() {
        // Split path at /amp
        $exp_uri = explode( '/amp', $_SERVER['REQUEST_URI'] );

        // If /amp was not part of the request, return
        if ( count( $exp_uri ) < 2 )
            return;

        // Get the post id associated with the request
        $post_id = url_to_postid( $exp_uri[0] );
        // Get the post type given the ID
        $post_type = get_post_type( $post_id );

        // If the post isn't a page, return
        if ( $post_type !== 'page' )
            return;

        // Redirect to the page with the given id, amp set to true
        $redirect = "index.php?page_id={$post_id}&amp=1";

        // Redirecting to redirect when /amp is appended to the path
        add_rewrite_rule( '(.+)/amp', $redirect, 'top' );   

        // Flush rewrite rules after adding one. (soft flush)
        flush_rewrite_rules(false);
    }

    /**
     * Enables page as a supported post type in the Glue for Yoast SEO & AMP plugin. 
     *
     * @since 0.0.1
     * @access public
     */
    public function enable_pages_in_glue() {
        // Get wordpress database global object
        global $wpdb;

        // Key declarations
        $option_name = 'wpseo_amp';
        $page_key = 'post_types-page-amp';
        $table = $wpdb->prefix . 'options';

        // Query options table for yoast meta amp options
        $query_results = $wpdb->get_results(
            "SELECT option_value FROM {$table} WHERE option_name='{$option_name}'"
        );
        // Unserialize results
        $amp_options = unserialize( $query_results[0]->option_value );
        
        // If pages are already enabled, return
        if ( $amp_options[$page_key] === 'on' )
            return;

        // Set pages to on (enabled)
        $amp_options[$page_key] = 'on';
        // Serialize new amp options
        $amp_options = serialize( $amp_options );

        // Update table with new options
        $wpdb->query(
            "UPDATE {$table} SET option_value='{$amp_options}' WHERE option_name='{$option_name}'"
        );

    }

}
?>

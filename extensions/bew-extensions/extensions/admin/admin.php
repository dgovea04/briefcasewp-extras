<?php

/**
 * All Admin parts are here
 */
if( ! defined( 'ABSPATH' ) ) {
    return;
} 

// Include our AJAX processing only when AJAX is active.
if( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
    require_once 'ajax.php';
}

add_action( 'admin_enqueue_scripts', 'ext_admin_enqueue_scripts' );

function ext_admin_enqueue_scripts( $hook_suffix ) {
    $enqueue_scripts = false;

    // Uncomment the one below to find your hook. Only that you need to change is portfolio_page probably.
    // var_dump( $hook_suffix ); die();
    if( 'briefcasewp_page_' . BEWXT_SLUG . '_extensions' === $hook_suffix ) {
        // Be sure to change the URL to the script where you will have it.
        wp_register_script( BEWXT_SLUG . '-admin-js', BEWXT_URL . '/assets/js/extensions.js', array( 'jquery' ) );
        // Localizing our script so we have a global 
        // Javascript variable myplugin to access the values such as nonce.
        wp_localize_script( 
            BEWXT_SLUG . '-admin-js',
            'extjsobject', 
            array(
                'ajax_url' => admin_url( 'admin-ajax.php' ),
                'nonce'    => wp_create_nonce( BEWXT_SLUG . '-nonce' ),
                'actions'  => array(
                    'activate' => BEWXT_SLUG . '_extension_activate',
                    'deactivate' => BEWXT_SLUG . '_extension_deactivate',
                 ),
                'text'     => array(
                    'activate' => __( 'Activate', 'briefcase-extras' ),
                    'deactivate' => __( 'Deactivate', 'briefcase-extras' ),
                )
            ));
        wp_enqueue_script( BEWXT_SLUG . '-admin-js' );
    }
    
}

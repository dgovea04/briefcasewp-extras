<?php

/**
 * All AJAX processing is here. Deactivating and activating.
 */

if( ! defined( 'ABSPATH' ) ) {
    return;
}

add_action( 'wp_ajax_' . BEWXT_SLUG . '_extension_activate', 'ext_activate_extension_ajax' );

/**
 * Activating the Extension through AJAX
 * @return void 
 */
function ext_activate_extension_ajax() {
    // Check if there is a nonce and if it is, verify it. Otherwise throw an error
    if( ! isset( $_POST['nonce'] ) 
        || ! wp_verify_nonce( $_POST['nonce'],  BEWXT_SLUG . '-nonce' ) ) {
        
        wp_send_json_error( array( 'message' => __( 'Something went wrong!', 'briefcase-extras' ) ) );
        die();
    }
    // If we don't have an extension id, don't process any further.
    if( ! isset( $_POST['extension'] ) ) {
        wp_send_json_error( array( 'message' => __( 'No Integration Sent', 'briefcase-extras' ) ) );
        die();
    }
    // The extension to activate
    $extension = $_POST['extension'];

    $active_extensions = ext_get_active_extensions();
    // If that extension is already active, don't process it further.
    // If the extension is not active yet, let's try to activate it.
    if( ! isset( $active_extensions[ $extension ] ) ) {
        // Let's get all the registered extensions.
        $extensions = ext_get_extensions(); 
        // Check if we have that extensions registered.
        if( isset( $extensions[ $extension ] ) ) {
            // Put it in the active extensions array
            $active_extensions[ $extension ] = $extensions[ $extension ];
            // Trigger an action so some plugins can also process some data here.
            do_action( BEWXT_SLUG . '_' . $extension . '_extension_activated' );
            // Update the active extensions.
            ext_save_active_extensions( $active_extensions );
            wp_send_json_success( array( 'message' => __( 'Activated', 'briefcase-extras' ) ) );
            die();
        }
        
    } else {
        // Our extension is already active.
        wp_send_json_success( array( 'message' => __( 'Already Activated', 'briefcase-extras' ) ) );
        die();
    }

    // Extension might not be registered.
    wp_send_json_error( array( 'message' => __( 'Nothing Happened', 'briefcase-extras' ) ) );
    die();
}

add_action( 'wp_ajax_' . BEWXT_SLUG . '_extension_deactivate', 'ext_deactivate_extension_ajax' );

/**
 * Deactivating the Integration through AJAX
 * @return void 
 */
function ext_deactivate_extension_ajax() {
    // Check if there is a nonce and if it is, verify it. Otherwise throw an error
    if( ! isset( $_POST['nonce'] ) 
        || ! wp_verify_nonce( $_POST['nonce'], BEWXT_SLUG . '-nonce' ) ) {
        
        wp_send_json_error( array( 'message' => __( 'Something went wrong!', 'briefcase-extras' ) ) );
        die();
    }
    // If we don't have an extension id, don't process any further.
    if( ! isset( $_POST['extension'] ) ) {
        wp_send_json_error( array( 'message' => __( 'No Extension Sent', 'briefcase-extras' ) ) );
        die();
    }
    // The extension to activate
    $extension = $_POST['extension'];

    $active_extensions = ext_get_active_extensions();
    // If that extension is already deactived, don't process it further.
    // If the extension is active, let's try to deactivate it.
    if( isset( $active_extensions[ $extension ] ) ) {
        // Remove the extension from the active extensions.
        unset( $active_extensions[ $extension ] );
        do_action( BEWXT_SLUG . '_' . $extension . '_extension_activated' );
        // Update the active extensions.
        ext_save_active_extensions( $active_extensions );
        wp_send_json_success( array( 'message' => __( 'Deactivated', 'briefcase-extras' ) ) );
        die();
    } else {
        wp_send_json_error( array( 'message' => __( 'Not Activated', 'briefcase-extras' ) ) );
        die();
    }

    wp_send_json_error( array( 'message' => __( 'Nothing Happened', 'briefcase-extras' ) ) );
    die();
}
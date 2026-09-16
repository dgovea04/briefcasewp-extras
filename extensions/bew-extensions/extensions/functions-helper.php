<?php

/**
 * Helper functions
 */


if( ! defined ('ABSPATH' ) ) {
    return;
}

/**
* Get all activated extensions
*/
function ext_get_active_extensions() {
  return get_option( BEWXT_SLUG . '_active_extensions', array() );
}

/**
* Save activated extensions
*/
function ext_save_active_extensions( $extensions ) {
  return update_option( BEWXT_SLUG . '_active_extensions', $extensions );
}


/**
* Get all the registered extensions through a filter
*/
function ext_get_extensions() {
  return apply_filters( BEWXT_SLUG . '_extensions', array() );
}
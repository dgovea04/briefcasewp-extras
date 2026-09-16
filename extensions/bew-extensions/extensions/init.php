<?php
/**
 * The main extensions file that will include everything we need.
 */

if( ! defined ('ABSPATH' ) ) {
    return;
}

// Add something that will define your plugin slug
// This will be using in filters and also options
define( 'BEWXT_SLUG', 'briefcasewp' );
define( 'BEWXT_URL', plugin_dir_url( __FILE__ ) );

require_once 'abstracts/class-extension.php';
require_once 'functions-helper.php';

if( is_admin() ) {
    require_once 'admin/admin.php';
}

// Including an example right here. Might be also removed and saved as a separate plugin.

require_once 'inside/bew-wpf-filter/bew-wpf-filter.php';
require_once 'inside/bew-checkout/bew-checkout.php';
require_once 'inside/bew-fullpage/bew-fullpage.php';
require_once 'inside/bew-orders/bew-orders.php';
require_once 'inside/bew-blocks/bew-blocks.php';
require_once 'inside/bew-gallery-video/bew-gallery-video.php';
require_once 'inside/bew-autocomplete/bew-autocomplete.php';
require_once 'inside/bew-fast-checkout/bew-fast-checkout.php';
require_once 'inside/bew-delivery/bew-delivery.php';
require_once 'inside/bew-ai-smart-upsells/bew-ai-smart-upsells.php';
//require_once 'inside/bew-builder/bew-builder.php';

add_action( 'init', 'ext_load_extensions' );

/**
 * Loading all extensions
 * @return void
 */
function ext_load_extensions() {
    $active_extensions = ext_get_active_extensions();
    if( $active_extensions ) {
        foreach( $active_extensions as $slug => $extension ) {
            if( class_exists( $extension ) ) {
                $extension = new $extension();
                $extension->load();
            }
        }
    }
}

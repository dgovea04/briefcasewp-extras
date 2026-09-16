<?php
namespace BriefcasewpExtras\Modules\WooCheckout\AIUpsells;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

interface BEWIA_AI_Upsell_Provider_Interface {

	public function get_recommendations( $settings, $context, $limit = 3 );
}

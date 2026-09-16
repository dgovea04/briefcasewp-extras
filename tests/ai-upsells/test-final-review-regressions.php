<?php
/** Final review regression contracts. */
$root = dirname( __DIR__, 2 );
$ajax = file_get_contents( $root . '/modules/woo-checkout/ai-upsells/class-bcwp-ai-upsell-ajax.php' );
$analytics = file_get_contents( $root . '/modules/woo-checkout/ai-upsells/class-bcwp-ai-upsell-analytics.php' );
if ( false === $ajax || false === $analytics ) { throw new RuntimeException( 'Unable to read AI upsell sources.' ); }

function bewia_final_review_assert( $condition, $message ) {
	if ( ! $condition ) { throw new RuntimeException( $message ); }
}

bewia_final_review_assert( strpos( $ajax, 'get_saved_settings' ) !== false, 'get_ai_upsell must load server-side settings.' );
bewia_final_review_assert( strpos( $ajax, 'candidate_product_ids\' => $posted' ) === false, 'POST candidate IDs must not be authoritative.' );
bewia_final_review_assert( strpos( $ajax, 'campaign_key' ) !== false && strpos( $ajax, 'variants' ) !== false, 'Critical campaign configuration must be resolved server-side.' );
bewia_final_review_assert( strpos( $analytics, "bewia_ai_upsell_ids" ) !== false && strpos( $analytics, 'bewia_ai_upsell_offers' ) !== false, 'Legacy and new attribution inputs must both remain supported.' );
bewia_final_review_assert( strpos( $analytics, '$updated = 0' ) !== false, 'Revenue confirmation must count updated rows.' );
bewia_final_review_assert( strpos( $analytics, "'_bewia_ai_revenue_confirmed'" ) !== false, 'Revenue confirmation marker must remain explicit.' );
echo "Final review regression tests passed.\n";

<?php
/** Security and legacy attribution regressions. */
$root = dirname( __DIR__, 2 );
$ajax = file_get_contents( $root . '/modules/woo-checkout/ai-upsells/class-bcwp-ai-upsell-ajax.php' );
$analytics = file_get_contents( $root . '/modules/woo-checkout/ai-upsells/class-bcwp-ai-upsell-analytics.php' );
if ( false === $ajax || false === $analytics ) { throw new RuntimeException( 'Unable to read AI upsell sources.' ); }

function bewia_security_assert( $condition, $message ) {
	if ( ! $condition ) { throw new RuntimeException( $message ); }
}

bewia_security_assert( strpos( $ajax, 'if ( array_key_exists( $key, $posted ) )' ) === false, 'POST settings must not override server-side eligibility.' );
bewia_security_assert( strpos( $ajax, "'offer_id'" ) !== false && strpos( $ajax, 'register_emitted_offer' ) !== false, 'The emitted offer identity must be registered.' );
bewia_security_assert( strpos( $analytics, "return \$identity['session_id'] . '|' . \$identity['campaign_key'] . '|' . \$identity['variant_id'] . '|' . \$identity['offer_id'];" ) === false, 'Attribution deduplication must distinguish product IDs.' );
bewia_security_assert( strpos( $analytics, "explode( ','" ) !== false && strpos( $analytics, 'bewia_ai_upsell_ids' ) !== false, 'Legacy comma-separated IDs must all be parsed.' );
echo "Security regression tests passed.\n";

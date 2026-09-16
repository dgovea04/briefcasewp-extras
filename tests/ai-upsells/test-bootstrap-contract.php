<?php
/** Dependency-free final review regression tests. */
$root = dirname( __DIR__, 2 );
$module = file_get_contents( $root . '/modules/woo-checkout/module.php' );
if ( false === $module ) { throw new RuntimeException( 'Unable to read checkout module.' ); }
foreach ( array( 'class-bcwp-ai-upsell-ai-client.php', 'class-bcwp-ai-upsell-experiments.php' ) as $file ) {
	if ( false === strpos( $module, "require_once BEW_EXTRAS_PATH . 'modules/woo-checkout/ai-upsells/" . $file . "';" ) ) {
		throw new RuntimeException( 'Bootstrap must explicitly load ' . $file . '.' );
	}
}
echo "Bootstrap contract tests passed.\n";

<?php
/**
 * templates loader view
 */
?>
<div class="elementor-library-error">
	<div class="elementor-library-error-message"><?php
		_e( 'Template couldn\'t be loaded. Please activate you license key before.', 'bew-extras' );
	?></div>
	<div class="elementor-library-error-link"><?php
		printf(
			'<a class="template-library-activate-license" href="%1$s" target="_blank">%2$s %3$s</a>',
			'#',
			'<i class="fa fa-external-link" aria-hidden="true"></i>',
			__( 'Activate license', 'bew-extras' )
		);
	?></div>
</div>
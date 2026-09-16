<?php
/**
 * Template item
 */
?>
<# var activeTab = window.BewBuilderData.tabs[ type ]; #>
<div class="elementor-template-library-template-body">
	<# if ( 'bew-local' !== source ) { #>
	<div class="elementor-template-library-template-screenshot">
		<# if ( 'bew-local' !== source ) { #>
		<div class="elementor-template-library-template-preview">
			<i class="eicon-zoom-in-bold"></i>
		</div>
		<# } #>
		<img src="{{ thumbnail }}" alt="">
	</div>
	<# } #>
</div>
<div class="elementor-template-library-template-controls">
	<# if ( 'bew-local' === source || window.BewBuilderData.license.activated ) { #>
	<button class="elementor-template-library-template-action bew-template-library-template-insert elementor-button elementor-button-success">
		<i class="eicon-file-download"></i>
		<span class="elementor-button-title"><?php esc_html_e( 'Insert', 'bew-extras' ); ?></span>
	</button>
	<# } else if ( 'bew-local' !== source ) { #>
		{{{ window.BewBuilderData.license.link }}}
	<# } #>
</div>
<# if ( 'bew-local' === source || true == activeTab.settings.show_title ) { #>
<div class="elementor-template-library-template-name">{{{ title }}}</div>
<# } else { #>
<div class="elementor-template-library-template-name-holder"></div>
<# } #>
<# if ( 'bew-local' === source ) { #>
<div class="elementor-template-library-template-type">
	<?php esc_html_e( 'Type:', 'bew-extras' ); ?> {{{ typeLabel }}}
</div>
<# } #>
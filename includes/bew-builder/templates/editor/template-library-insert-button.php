<# if ( window.BewBuilderData.license.activated ) { #>
<button class="elementor-template-library-template-action bew-template-library-template-insert elementor-button elementor-button-success">
	<i class="eicon-file-download"></i><span class="elementor-button-title"><?php
		esc_html_e( 'Insert', 'bew-extras' );
	?></span>
</button>
<# } else { #>
{{{ window.BewBuilderData.license.link }}}
<# } #>
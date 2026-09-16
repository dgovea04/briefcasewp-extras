<?php
/**
 * Template Library Header Template
 */
?>
<label class="bew-template-library-filter-label">
	<input type="radio" value="{{ slug }}" <# if ( '' === slug ) { #> checked<# } #> name="bew-library-filter">
	<span>{{ title }}</span>
</label>
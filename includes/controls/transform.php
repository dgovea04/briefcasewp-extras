<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Control_Transform extends Control_Base_Units {

	public function get_type() {
		return 'transform';
	}
	public function get_default_value() {
		return array_merge(
			parent::get_default_value(), [
				'translate' => '',
				'scale' => '',
				'isLinked' => false,
			]
		);
	}

	protected function get_default_settings() {
		return array_merge(
			parent::get_default_settings(), [
				'label_block' => true,
				'allowed_dimensions' => 'all',
				'placeholder' => '',
			]
		);
	}

	public function content_template() {
		$dimensions = [
			'translate' => esc_html__( 'TranslateY', 'bew-extras' ),
			'scale' => esc_html__( 'Scale', 'bew-extras' ),
		];
		?>
		<div class="elementor-control-field">
			<label class="elementor-control-title">{{{ data.label }}}</label>
			<?php $this->print_units_template(); ?>
			<div class="elementor-control-input-wrapper">
				<ul class="elementor-control-dimensions">
					<?php
					foreach ( $dimensions as $dimension_key => $dimension_title ) :
						?>
						<li class="elementor-control-dimension">
							<input id="<?php $this->print_control_uid( $dimension_key ); ?>" type="number" data-setting="<?php
								// PHPCS - the variable $dimension_key is a plain text.
								echo $dimension_key; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							?>" placeholder="<#
								placeholder = view.getControlPlaceholder();
								if ( _.isObject( placeholder ) ) {
									if ( ! _.isUndefined( placeholder.<?php
										// PHPCS - the variable $dimension_key is a plain text.
										echo $dimension_key; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									?> ) ) {
										print( placeholder.<?php
											// PHPCS - the variable $dimension_key is a plain text.
											echo $dimension_key; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
										?> );
									}
								} else {
								print( placeholder );
								} #>"
							<# if ( -1 === _.indexOf( allowed_dimensions, '<?php
								// PHPCS - the variable $dimension_key is a plain text.
								echo $dimension_key; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							?>' ) ) { #>
								disabled
								<# } #>
									/>
							<label for="<?php $this->print_control_uid( $dimension_key ); ?>" class="elementor-control-dimension-label"><?php
								// PHPCS - the variable $dimension_title holds an escaped translated value.
								echo $dimension_title; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							?></label>
						</li>
					<?php endforeach; ?>
					<li>
						<button class="elementor-link-dimensions tooltip-target" data-tooltip="<?php echo esc_attr__( 'Link values together', 'elementor' ); ?>">
							<span class="elementor-linked">
								<i class="eicon-link" aria-hidden="true"></i>
								<span class="elementor-screen-only"><?php echo esc_html__( 'Link values together', 'elementor' ); ?></span>
							</span>
							<span class="elementor-unlinked">
								<i class="eicon-chain-broken" aria-hidden="true"></i>
								<span class="elementor-screen-only"><?php echo esc_html__( 'Unlinked values', 'elementor' ); ?></span>
							</span>
						</button>
					</li>
				</ul>
			</div>
		</div>
		<# if ( data.description ) { #>
		<div class="elementor-control-field-description">{{{ data.description }}}</div>
		<# } #>
		<?php
	}
}

Plugin::instance()->controls_manager->register_control( 'transform', new Control_Transform() );

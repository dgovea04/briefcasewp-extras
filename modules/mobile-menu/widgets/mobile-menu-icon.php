<?php
namespace BriefcasewpExtras\Modules\MobileMenu\Widgets;

use Elementor;
use Elementor\Plugin;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;  
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use BriefcasewpExtras\Base\Base_Widget;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Mobile_Menu_Icon extends Base_Widget {

	public function get_name() {
		return 'mobile-menu-icon';
	}

	public function get_title() {
		return __( 'Mobile Menu', 'briefcase-extras' );
	}

	public function get_icon() {
		return 'eicon-navigation-horizontal';
	}

	public function get_categories() {
		return [ 'bew-extras' ];
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_mobile_menu_icon',
			[
				'label' => __( 'Mobile Icon Menu', 'briefcase-extras' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'icon_view',
			[
				'label' => __( 'Layout', 'briefcase-extras' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'inline',
				'options' => [
					'inline' => [
						'title' => __( 'Inline', 'briefcase-extras' ),
						'icon' => 'eicon-ellipsis-h',
					],
					'vertical' => [
						'title' => __( 'Vertical', 'briefcase-extras' ),
						'icon' => 'eicon-editor-list-ul',
					],					
				],
				'render_type' => 'template',
				'classes' => 'elementor-control-start-end',
				'label_block' => false,
				'style_transfer' => true,
				'prefix_class' => 'bew-menu-icon--layout-',
			]
		);
		
		$repeater = new Repeater();

		$repeater->add_control(
			'menu_text',
			[
				'label' => __( 'Text', 'briefcase-extras' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => __( 'Menu text', 'briefcase-extras' ),
				'default' => __( 'Menu text', 'briefcase-extras' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'menu_selected_icon',
			[
				'label' => __( 'Icon', 'briefcase-extras' ),
				'type' => Controls_Manager::ICONS,
				'label_block' => true,
				'default' => [
					'value' => 'fas fa-check',
					'library' => 'fa-solid',
				],
				'fa4compatibility' => 'icon',
			]
		);

		$repeater->add_control(
			'menu_link',
			[
				'label' => __( 'Link', 'briefcase-extras' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'label_block' => true,
				'placeholder' => __( 'https://your-link.com', 'briefcase-extras' ),
			]
		);

		$this->add_control(
			'menu_icon_list',
			[
				'label' => '',
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'menu_text' => __( 'Home', 'briefcase-extras' ),
						'menu_selected_icon' => [
							'value' => 'fas fa-home',
							'library' => 'fa-solid',
						],
					],
					[
						'menu_text' => __( 'Category', 'briefcase-extras' ),
						'menu_selected_icon' => [
							'value' => 'fas fa-layer-group',
							'library' => 'fa-solid',
						],
					],
					[
						'menu_text' => __( 'Account', 'briefcase-extras' ),
						'menu_selected_icon' => [
							'value' => 'fas fa-user',
							'library' => 'fa-solid',
						],
					],
					
					[
						'menu_text' => __( 'More', 'briefcase-extras' ),
						'menu_selected_icon' => [
							'value' => 'fas fa-ellipsis-h',
							'library' => 'fa-solid',
						],
					],
				],
				'title_field' => '{{{ elementor.helpers.renderIcon( this, menu_selected_icon, {}, "i", "panel" ) || \'<i class="{{ icon }}" aria-hidden="true"></i>\' }}} {{{ menu_text }}}',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_menu_icon_list',
			[
				'label' => __( 'Menu', 'briefcase-extras' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_responsive_control(
			'menu_height',
			[
				'label' => __( 'Height', 'briefcase-extras' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bew-menu-icon-items.bew-inline-items' => 'min-height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'menu_icon_list_bg',
			[
				'label' => __( 'Background Color', 'briefcase-extras' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bew-menu-icon-items' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'menu_space_between',
			[
				'label' => __( 'Space Between', 'briefcase-extras' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bew-menu-icon-items:not(.bew-inline-items) .bew-menu-icon-item:not(:last-child)' => 'padding-bottom: calc({{SIZE}}{{UNIT}}/2)',
					'{{WRAPPER}} .bew-menu-icon-items:not(.bew-inline-items) .bew-menu-icon-item:not(:first-child)' => 'margin-top: calc({{SIZE}}{{UNIT}}/2)',
					'{{WRAPPER}} .bew-menu-icon-items.bew-inline-items .bew-menu-icon-item' => 'margin-right: calc({{SIZE}}{{UNIT}}/2); margin-left: calc({{SIZE}}{{UNIT}}/2)',
					'{{WRAPPER}} .bew-menu-icon-items.bew-inline-items' => 'margin-right: calc(-{{SIZE}}{{UNIT}}/2); margin-left: calc(-{{SIZE}}{{UNIT}}/2)',
					'body.rtl {{WRAPPER}} .bew-menu-icon-items.bew-inline-items .bew-menu-icon-item:after' => 'left: calc(-{{SIZE}}{{UNIT}}/2)',
					'body:not(.rtl) {{WRAPPER}} .bew-menu-icon-items.bew-inline-items .bew-menu-icon-item:after' => 'right: calc(-{{SIZE}}{{UNIT}}/2)',
				],
			]
		);
		
		$this->add_control(
			'menu_icon_fixed',
			[
				'label' => __( 'Fixed Menu', 'briefcase-extras' ),
				'type' => Controls_Manager::SWITCHER,
				'default' 		=> '',
				'label_off' => __( 'Off', 'briefcase-extras' ),
				'label_on' => __( 'On', 'briefcase-extras' ),
				'return_value' 	=> 'fixed',
				'separator' => 'before',
				'prefix_class' => 'bew-',
			]
		);
		
		$this->add_control(
			'menu_icon_position',
			[
				'label'        => __( 'Position', 'briefcase-extras' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'bottom',
				'options'      => [					
					'bottom' => __( 'Bottom', 'briefcase-extras' ),
					'top' => __( 'Top', 'briefcase-extras' ),
				],
				'condition' => [
					'menu_icon_fixed' => 'fixed',
				],
				'prefix_class' => 'fixed-',				
			]
		);

		$this->add_control(
			'menu_divider',
			[
				'label' => __( 'Divider', 'briefcase-extras' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => __( 'Off', 'briefcase-extras' ),
				'label_on' => __( 'On', 'briefcase-extras' ),								
				'separator' => 'before',
			]
		);

		$this->add_control(
			'menu_divider_style',
			[
				'label' => __( 'Style', 'briefcase-extras' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'solid' => __( 'Solid', 'briefcase-extras' ),
					'double' => __( 'Double', 'briefcase-extras' ),
					'dotted' => __( 'Dotted', 'briefcase-extras' ),
					'dashed' => __( 'Dashed', 'briefcase-extras' ),
				],
				'default' => 'solid',
				'condition' => [
					'menu_divider' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .bew-menu-icon-items:not(.bew-inline-items) .bew-menu-icon-item:not(:last-child)' => 'border-bottom-style: {{VALUE}}',
					'{{WRAPPER}} .bew-menu-icon-items.bew-inline-items .bew-menu-icon-item:not(:last-child)' => 'border-right-style: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'menu_divider_weight',
			[
				'label' => __( 'Weight', 'briefcase-extras' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 1,
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 20,
					],
				],
				'condition' => [
					'menu_divider' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .bew-menu-icon-items:not(.bew-inline-items) .bew-menu-icon-item:not(:last-child)' => 'border-bottom-width: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .bew-inline-items .bew-menu-icon-item:not(:last-child)' => 'border-right-width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'menu_divider_width',
			[
				'label' => __( 'Width', 'briefcase-extras' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => '%',
				],
				'condition' => [
					'menu_divider' => 'yes',
					'view!' => 'inline',
				],
				'selectors' => [
					'{{WRAPPER}} .bew-menu-icon-item:not(:last-child)' => 'width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'menu_divider_color',
			[
				'label' => __( 'Color', 'briefcase-extras' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ddd',
				'condition' => [
					'menu_divider' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .bew-menu-icon-item:not(:last-child)' => 'border-color: {{VALUE}}',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'menu_border',
				'label' => __( 'Menu Border', 'briefcase-extras' ),
				'selector' => '{{WRAPPER}} .bew-menu-icon-item',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'menu_border_radius',
			[
				'label' => __( 'Border Radius', 'briefcase-extras' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bew-menu-icon-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'menu_padding',
			[
				'label' => __( 'Padding', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bew-menu-icon-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				
			]
		);
		
		$this->add_responsive_control(
			'menu_margin',
			[
				'label' => __( 'Margin', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bew-menu-icon-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
					
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_menu_icon_style',
			[
				'label' => __( 'Icon', 'briefcase-extras' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'menu_icon_color',
			[
				'label' => __( 'Color', 'briefcase-extras' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bew-menu-icon-icon i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .bew-menu-icon-icon svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'menu_icon_color_hover',
			[
				'label' => __( 'Hover', 'briefcase-extras' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bew-menu-icon-item:hover .bew-menu-icon-icon i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .bew-menu-icon-item:hover .bew-menu-icon-icon svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'menu_icon_size',
			[
				'label' => __( 'Size', 'briefcase-extras' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 20,
				],
				'range' => [
					'px' => [
						'min' => 6,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bew-menu-icon-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .bew-menu-icon-icon svg' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'menu_icon_self_align',
			[
				'label' => __( 'Alignment', 'briefcase-extras' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'briefcase-extras' ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'briefcase-extras' ),
						'icon' => 'eicon-h-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'briefcase-extras' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bew-menu-icon-icon' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_menu_text_style',
			[
				'label' => __( 'Text', 'briefcase-extras' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'menu_text_color',
			[
				'label' => __( 'Text Color', 'briefcase-extras' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bew-menu-icon-text' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'menu_text_color_hover',
			[
				'label' => __( 'Hover', 'briefcase-extras' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bew-menu-icon-item:hover .bew-menu-icon-text' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'menu_text_indent',
			[
				'label' => __( 'Text Indent', 'briefcase-extras' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bew-menu-icon-text' => is_rtl() ? 'padding-right: {{SIZE}}{{UNIT}};' : 'padding-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'menu_icon_typography',
				'selector' => '{{WRAPPER}} .bew-menu-icon-item',
			]
		);

		$this->end_controls_section();
	}
	/**
	 * Render menu icon widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$fallback_defaults = [
			'fa fa-home',
			'fa fa-layer-group',
			'fa fa-user',
			'fa fa-ellipsis-h',
		];

		$this->add_render_attribute( 'icon_list', 'class', 'bew-menu-icon-items' );
		$this->add_render_attribute( 'list_item', 'class', 'bew-menu-icon-item' );

		if ( 'inline' === $settings['icon_view'] ) {
			$this->add_render_attribute( 'icon_list', 'class', 'bew-inline-items' );
			$this->add_render_attribute( 'list_item', 'class', 'bew-inline-item' );
		}
		?>
		<ul <?php echo $this->get_render_attribute_string( 'icon_list' ); ?>>
			<?php
			foreach ( $settings['menu_icon_list'] as $index => $item ) :
				$repeater_setting_key = $this->get_repeater_setting_key( 'menu_text', 'icon_list', $index );

				$this->add_render_attribute( $repeater_setting_key, 'class', 'bew-menu-icon-text' );

				$this->add_inline_editing_attributes( $repeater_setting_key );
				$migration_allowed = Icons_Manager::is_migration_allowed();
				?>
				<li class="bew-menu-icon-item bew-justify-content" >
					<?php
					if ( ! empty( $item['menu_link']['url'] ) ) {
						$link_key = 'link_' . $index;

						$this->add_render_attribute( $link_key, 'href', $item['menu_link']['url'] );

						if ( $item['menu_link']['is_external'] ) {
							$this->add_render_attribute( $link_key, 'target', '_blank' );
						}

						if ( $item['menu_link']['nofollow'] ) {
							$this->add_render_attribute( $link_key, 'rel', 'nofollow' );
						}

						echo '<a ' . $this->get_render_attribute_string( $link_key ) . '>';
					}

					// add old default
					if ( ! isset( $item['icon'] ) && ! $migration_allowed ) {
						$item['icon'] = isset( $fallback_defaults[ $index ] ) ? $fallback_defaults[ $index ] : 'fa fa-check';
					}

					$migrated = isset( $item['__fa4_migrated']['menu_selected_icon'] );
					$is_new = ! isset( $item['icon'] ) && $migration_allowed;
					if ( ! empty( $item['icon'] ) || ( ! empty( $item['menu_selected_icon']['value'] ) && $is_new ) ) :
						?>
						<span class="bew-menu-icon-icon">
							<?php
							if ( $is_new || $migrated ) {
								Icons_Manager::render_icon( $item['menu_selected_icon'], [ 'aria-hidden' => 'true' ] );
							} else { ?>
									<i class="<?php echo esc_attr( $item['icon'] ); ?>" aria-hidden="true"></i>
							<?php } ?>
						</span>
					<?php endif; ?>
					<span <?php echo $this->get_render_attribute_string( $repeater_setting_key ); ?>><?php echo $item['menu_text']; ?></span>
					<?php if ( ! empty( $item['menu_link']['url'] ) ) : ?>
						</a>
					<?php endif; ?>
				</li>
				<?php
			endforeach;
			?>
		</ul>
		<?php
	}

	/**
	 * Render icon list widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _content_template() {
		?>
		<#
			view.addRenderAttribute( 'icon_list', 'class', 'bew-menu-icon-items' );
			view.addRenderAttribute( 'list_item', 'class', 'bew-menu-icon-item' );

			if ( 'inline' == settings.icon_view ) {
				view.addRenderAttribute( 'icon_list', 'class', 'bew-inline-items' );
				view.addRenderAttribute( 'list_item', 'class', 'bew-inline-item bew-justify-content ' );
			}
			var iconsHTML = {},
				migrated = {};
		#>
		<# if ( settings.menu_icon_list ) { #>
			<ul {{{ view.getRenderAttributeString( 'icon_list' ) }}}>
			<# _.each( settings.menu_icon_list, function( item, index ) {

					var iconTextKey = view.getRepeaterSettingKey( 'menu_text', 'icon_list', index );

					view.addRenderAttribute( iconTextKey, 'class', 'bew-menu-icon-text' );

					view.addInlineEditingAttributes( iconTextKey ); #>

					<li {{{ view.getRenderAttributeString( 'list_item' ) }}}>
						<# if ( item.menu_link && item.menu_link.url ) { #>
							<a href="{{ item.menu_link.url }}">
						<# } #>
						<# if ( item.icon || item.menu_selected_icon.value ) { #>
						<span class="bew-menu-icon-icon">
							<#
								iconsHTML[ index ] = elementor.helpers.renderIcon( view, item.menu_selected_icon, { 'aria-hidden': true }, 'i', 'object' );
								migrated[ index ] = elementor.helpers.isIconMigrated( item, 'menu_selected_icon' );
								if ( iconsHTML[ index ] && iconsHTML[ index ].rendered && ( ! item.icon || migrated[ index ] ) ) { #>
									{{{ iconsHTML[ index ].value }}}
								<# } else { #>
									<i class="{{ item.icon }}" aria-hidden="true"></i>
								<# }
							#>
						</span>
						<# } #>
						<span {{{ view.getRenderAttributeString( iconTextKey ) }}}>{{{ item.menu_text }}}</span>
						<# if ( item.menu_link && item.menu_link.url ) { #>
							</a>
						<# } #>
					</li>
				<#
				} ); #>
			</ul>
		<#	} #>

		<?php
	}

	public function on_import( $element ) {
		return Icons_Manager::on_import_migration( $element, 'icon', 'menu_selected_icon', true );
	}
}

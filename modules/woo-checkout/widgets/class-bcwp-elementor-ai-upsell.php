<?php
namespace BriefcasewpExtras\Modules\WooCheckout\Widgets;

use Elementor\Controls_Manager;
use Elementor\Plugin;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use ElementorPro\Modules\QueryControl\Module as QueryControlModule;
use BriefcasewpExtras\Base\Base_Widget;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class BEWIA_BCWP_Elementor_AI_Upsell extends Base_Widget {

	public function get_name() {
		return 'bewia-ai-smart-upsells';
	}

	public function get_title() {
		return __( 'Bew AI Smart Upsells', 'bew-extras' );
	}

	public function get_icon() {
		return 'eicon-woocommerce';
	}

	public function get_categories() {
		return array( 'bew-extras-checkout' );
	}

	public function get_script_depends() {
		return array( 'bcwp-ai-upsell' );
	}

	public function get_style_depends() {
		return array( 'bcwp-ai-upsell' );
	}

	protected function _register_controls() {
		if ( ! class_exists( 'WooCommerce' ) ) {
			$this->start_controls_section(
				'bewia_section_notice',
				array(
					'label' => __( 'General', 'bew-extras' ),
					'tab'   => Controls_Manager::TAB_CONTENT,
				)
			);

			$this->add_control(
				'bewia_wc_notice',
				array(
					'type'            => Controls_Manager::RAW_HTML,
					'raw'             => esc_html__( 'WooCommerce must be active to use this widget.', 'bew-extras' ),
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
				)
			);

			$this->end_controls_section();

			return;
		}

		$this->start_controls_section(
			'bewia_section_general',
			array(
				'label' => __( 'General', 'bew-extras' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'enable_ai',
			array(
				'label'        => __( 'Enable AI', 'bew-extras' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'On', 'bew-extras' ),
				'label_off'    => __( 'Off', 'bew-extras' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'mode',
			array(
				'label'   => __( 'Mode', 'bew-extras' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'rules',
				'options' => array(
					'rules'  => __( 'Rules', 'bew-extras' ),
					'ai'     => __( 'AI', 'bew-extras' ),
					'hybrid' => __( 'Hybrid', 'bew-extras' ),
				),
			)
		);

		$this->add_control(
			'layout',
			array(
				'label'   => __( 'Layout', 'bew-extras' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'card',
				'options' => array(
					'checkbox' => __( 'Checkbox', 'bew-extras' ),
					'card'     => __( 'Card', 'bew-extras' ),
					'minimal'  => __( 'Minimal', 'bew-extras' ),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'bewia_section_products',
			array(
				'label' => __( 'Upsell Products', 'bew-extras' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		if ( $this->bewia_is_elementor_pro_installed() ) {
			$this->add_control(
				'candidate_products',
				array(
					'label'         => __( 'Candidate Products', 'bew-extras' ),
					'type'          => QueryControlModule::QUERY_CONTROL_ID,
					'label_block'   => true,
					'multiple'      => true,
					'default'       => array(),
					'autocomplete'  => array(
						'object' => QueryControlModule::QUERY_OBJECT_POST,
						'query'  => array(
							'post_type' => array( 'product' ),
						),
					),
					'description'   => __( 'Search products by name to build the candidate pool without loading the full catalog.', 'bew-extras' ),
				)
			);
		} else {
			$this->add_control(
				'candidate_products',
				array(
					'label'       => __( 'Candidate Products', 'bew-extras' ),
					'type'        => Controls_Manager::SELECT2,
					'multiple'    => true,
					'label_block' => true,
					'options'     => $this->bewia_get_product_options(),
					'description' => __( 'Install Elementor Pro for async product search on large catalogs. This fallback loads the first 200 published products.', 'bew-extras' ),
				)
			);
		}

		$this->add_control(
			'exclude_cart_products',
			array(
				'label'        => __( 'Exclude Cart Products', 'bew-extras' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'On', 'bew-extras' ),
				'label_off'    => __( 'Off', 'bew-extras' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'required_cart_categories',
			array(
				'label'       => __( 'Cart Categories', 'bew-extras' ),
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'label_block' => true,
				'options'     => $this->bewia_get_product_category_options(),
				'description' => __( 'Only show the upsell when the cart contains at least one product from these categories.', 'bew-extras' ),
			)
		);

		$this->add_control(
			'minimum_cart_total',
			array(
				'label'   => __( 'Minimum Cart Total', 'bew-extras' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 0,
				'min'     => 0,
			)
		);

		$this->add_control(
			'maximum_cart_total',
			array(
				'label'   => __( 'Maximum Cart Total', 'bew-extras' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 0,
				'min'     => 0,
				'description' => __( 'Set to 0 to allow any cart total above the minimum.', 'bew-extras' ),
			)
		);

		$this->add_control(
			'customer_status',
			array(
				'label'   => __( 'Logged-In Status', 'bew-extras' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'any',
				'options' => array(
					'any'       => __( 'Any Customer', 'bew-extras' ),
					'logged_in' => __( 'Logged-In Only', 'bew-extras' ),
					'guest'     => __( 'Guest Only', 'bew-extras' ),
				),
			)
		);

		$this->add_control(
			'device_type',
			array(
				'label'   => __( 'Device Type', 'bew-extras' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'any',
				'options' => array(
					'any'     => __( 'Any Device', 'bew-extras' ),
					'desktop' => __( 'Desktop Only', 'bew-extras' ),
					'mobile'  => __( 'Mobile Only', 'bew-extras' ),
				),
			)
		);

		$this->add_control(
			'maximum_product_price_ratio',
			array(
				'label'   => __( 'Maximum Product Price Ratio', 'bew-extras' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 1,
				'min'     => 0,
				'step'    => 0.1,
			)
		);

		$priority_repeater = new Repeater();

		if ( $this->bewia_is_elementor_pro_installed() ) {
			$priority_repeater->add_control(
				'product_id',
				array(
					'label'        => __( 'Product', 'bew-extras' ),
					'type'         => QueryControlModule::QUERY_CONTROL_ID,
					'label_block'  => true,
					'autocomplete' => array(
						'object' => QueryControlModule::QUERY_OBJECT_POST,
						'query'  => array(
							'post_type' => array( 'product' ),
						),
					),
				)
			);
		} else {
			$priority_repeater->add_control(
				'product_id',
				array(
					'label'       => __( 'Product', 'bew-extras' ),
					'type'        => Controls_Manager::SELECT2,
					'label_block' => true,
					'options'     => $this->bewia_get_product_options(),
				)
			);
		}

		$priority_repeater->add_control(
			'priority_score',
			array(
				'label'   => __( 'Priority Score', 'bew-extras' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 10,
				'step'    => 1,
			)
		);

		$this->add_control(
			'product_priorities',
			array(
				'label'       => __( 'Manual Priority Scores', 'bew-extras' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $priority_repeater->get_controls(),
				'title_field' => '{{{ product_id ? "Product #" + product_id : "Priority Rule" }}}',
				'description' => __( 'Boost or lower specific products. Positive numbers increase rank, negative numbers reduce it.', 'bew-extras' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'bewia_section_fallback',
			array(
				'label' => __( 'Content Fallback', 'bew-extras' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'fallback_title',
			array(
				'label'   => __( 'Fallback Title', 'bew-extras' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Recommended for your order', 'bew-extras' ),
			)
		);

		$this->add_control(
			'fallback_description',
			array(
				'label'   => __( 'Fallback Description', 'bew-extras' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => __( 'A personalized offer will appear here during checkout.', 'bew-extras' ),
			)
		);

		$this->add_control(
			'button_text',
			array(
				'label'   => __( 'Button Text', 'bew-extras' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Add this offer', 'bew-extras' ),
			)
		);

		$this->add_control(
			'show_dismiss',
			array(
				'label'        => __( 'Show Dismiss Link', 'bew-extras' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'On', 'bew-extras' ),
				'label_off'    => __( 'Off', 'bew-extras' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'success_message',
			array(
				'label'   => __( 'Success Message', 'bew-extras' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Offer added to cart.', 'bew-extras' ),
			)
		);

		$this->add_control(
			'collapse_delay_ms',
			array(
				'label'       => __( 'Collapse Delay (ms)', 'bew-extras' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 1200,
				'min'         => 0,
				'step'        => 100,
				'description' => __( 'How long the success state stays visible before the widget collapses.', 'bew-extras' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'bewia_section_style',
			array(
				'label' => __( 'Style', 'bew-extras' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'background_color',
			array(
				'label'     => __( 'Background Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .bewia-ai-smart-wrapper' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'border_color',
			array(
				'label'     => __( 'Border Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .bewia-ai-smart-wrapper' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'border_radius',
			array(
				'label'      => __( 'Border Radius', 'bew-extras' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .bewia-ai-smart-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'padding',
			array(
				'label'      => __( 'Padding', 'bew-extras' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .bewia-ai-smart-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'label'    => __( 'Title Typography', 'bew-extras' ),
				'selector' => '{{WRAPPER}} .bewia-ai-upsell-title',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'description_typography',
				'label'    => __( 'Description Typography', 'bew-extras' ),
				'selector' => '{{WRAPPER}} .bewia-ai-upsell-description',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'price_typography',
				'label'    => __( 'Price Typography', 'bew-extras' ),
				'selector' => '{{WRAPPER}} .bewia-ai-upsell-price',
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}

		$settings = $this->get_settings_for_display();

		$widget_settings = array(
			'enable_ai'                  => ! empty( $settings['enable_ai'] ) ? $settings['enable_ai'] : '',
			'mode'                       => ! empty( $settings['mode'] ) ? sanitize_key( $settings['mode'] ) : 'rules',
			'layout'                     => ! empty( $settings['layout'] ) ? sanitize_key( $settings['layout'] ) : 'card',
			'candidate_product_ids'      => ! empty( $settings['candidate_products'] ) ? array_map( 'absint', (array) $settings['candidate_products'] ) : array(),
			'exclude_cart_products'      => ! empty( $settings['exclude_cart_products'] ) ? $settings['exclude_cart_products'] : '',
			'required_cart_category_ids' => ! empty( $settings['required_cart_categories'] ) ? array_map( 'absint', (array) $settings['required_cart_categories'] ) : array(),
			'minimum_cart_total'         => isset( $settings['minimum_cart_total'] ) ? (float) $settings['minimum_cart_total'] : 0,
			'maximum_cart_total'         => isset( $settings['maximum_cart_total'] ) ? (float) $settings['maximum_cart_total'] : 0,
			'customer_status'            => ! empty( $settings['customer_status'] ) ? sanitize_key( $settings['customer_status'] ) : 'any',
			'device_type'                => ! empty( $settings['device_type'] ) ? sanitize_key( $settings['device_type'] ) : 'any',
			'maximum_product_price_ratio'=> isset( $settings['maximum_product_price_ratio'] ) ? (float) $settings['maximum_product_price_ratio'] : 1,
			'priority_scores'            => $this->bewia_build_priority_map( ! empty( $settings['product_priorities'] ) ? $settings['product_priorities'] : array() ),
			'show_dismiss'               => ! empty( $settings['show_dismiss'] ) ? $settings['show_dismiss'] : '',
			'success_message'            => ! empty( $settings['success_message'] ) ? sanitize_text_field( $settings['success_message'] ) : '',
			'collapse_delay_ms'          => isset( $settings['collapse_delay_ms'] ) ? absint( $settings['collapse_delay_ms'] ) : 1200,
			'fallback_title'             => ! empty( $settings['fallback_title'] ) ? sanitize_text_field( $settings['fallback_title'] ) : '',
			'fallback_description'       => ! empty( $settings['fallback_description'] ) ? sanitize_textarea_field( $settings['fallback_description'] ) : '',
			'button_text'                => ! empty( $settings['button_text'] ) ? sanitize_text_field( $settings['button_text'] ) : '',
		);
		$widget_settings = \BriefcasewpExtras\Modules\WooCheckout\AIUpsells\BEWIA_AI_Upsell_Engine::normalize_settings( $widget_settings );

		if ( $this->bewia_should_render_static_preview() ) {
			$this->bewia_render_static_preview( $widget_settings );
			return;
		}
		?>
		<div
			class="bewia-ai-smart-upsells bewia-ai-smart-wrapper bewia-ai-upsell-wrapper is-loading"
			data-widget="bewia-ai-smart-upsell"
			data-layout="<?php echo esc_attr( $widget_settings['layout'] ); ?>"
			data-mode="<?php echo esc_attr( $widget_settings['mode'] ); ?>"
			data-settings="<?php echo esc_attr( wp_json_encode( $widget_settings ) ); ?>"
		>
			<div class="bewia-ai-smart-upsells__status" aria-live="polite"></div>
			<div class="bewia-ai-upsell-content">
				<h3 class="bewia-ai-upsell-title"><?php echo esc_html( $widget_settings['fallback_title'] ); ?></h3>
				<div class="bewia-ai-upsell-description"><?php echo esc_html( $widget_settings['fallback_description'] ); ?></div>
			</div>
			<div class="bewia-ai-smart-upsells__list"></div>
			<div class="bewia-ai-smart-upsells__actions">
				<button type="button" class="bewia-ai-smart-upsells__button bewia-ai-upsell-button button" disabled="disabled">
					<?php echo esc_html( $widget_settings['button_text'] ); ?>
				</button>
				<button type="button" class="bewia-ai-smart-upsells__dismiss button button-link" style="display:none;">
					<?php echo esc_html__( 'No thanks', 'bew-extras' ); ?>
				</button>
			</div>
		</div>
		<?php
	}

	private function bewia_should_render_static_preview() {
		if ( ! class_exists( '\Elementor\Plugin' ) ) {
			return false;
		}

		$is_editor  = Plugin::$instance->editor && Plugin::$instance->editor->is_edit_mode();
		$is_preview = Plugin::$instance->preview && Plugin::$instance->preview->is_preview_mode();

		if ( ! $is_editor && ! $is_preview ) {
			return false;
		}

		if ( ! function_exists( 'WC' ) || ! class_exists( 'WooCommerce' ) ) {
			return true;
		}

		$wc = WC();

		return ! $wc || empty( $wc->cart ) || ! is_object( $wc->cart );
	}

	private function bewia_is_elementor_pro_installed() {
		return class_exists( '\ElementorPro\Modules\QueryControl\Module' );
	}

	private function bewia_render_static_preview( $widget_settings ) {
		$layout_class = 'bewia-ai-upsell-card';

		if ( 'checkbox' === $widget_settings['layout'] ) {
			$layout_class = 'bewia-ai-upsell-checkbox';
		} elseif ( 'minimal' === $widget_settings['layout'] ) {
			$layout_class = 'bewia-ai-upsell-minimal';
		}
		?>
		<div
			class="bewia-ai-smart-upsells bewia-ai-smart-wrapper bewia-ai-upsell-wrapper"
			data-widget="bewia-ai-smart-upsell-preview"
			data-layout="<?php echo esc_attr( $widget_settings['layout'] ); ?>"
			data-mode="<?php echo esc_attr( $widget_settings['mode'] ); ?>"
		>
			<div class="bewia-ai-smart-upsells__status">
				<?php echo esc_html__( 'Editor preview', 'bew-extras' ); ?>
			</div>
			<div class="bewia-ai-smart-upsells__list">
				<div class="<?php echo esc_attr( $layout_class ); ?> is-selected">
					<div class="bewia-ai-upsell-image">
						<img src="<?php echo esc_url( wc_placeholder_img_src( 'woocommerce_thumbnail' ) ); ?>" alt="<?php echo esc_attr( $widget_settings['fallback_title'] ); ?>" />
					</div>
					<div class="bewia-ai-upsell-content">
						<h3 class="bewia-ai-upsell-title"><?php echo esc_html( $widget_settings['fallback_title'] ); ?></h3>
						<div class="bewia-ai-upsell-description"><?php echo esc_html( $widget_settings['fallback_description'] ); ?></div>
						<div class="bewia-ai-upsell-price"><?php echo esc_html__( 'Preview price', 'bew-extras' ); ?></div>
					</div>
				</div>
			</div>
			<div class="bewia-ai-smart-upsells__actions">
				<button type="button" class="bewia-ai-smart-upsells__button bewia-ai-upsell-button button" disabled="disabled">
					<?php echo esc_html( $widget_settings['button_text'] ); ?>
				</button>
				<button type="button" class="bewia-ai-smart-upsells__dismiss button button-link" disabled="disabled">
					<?php echo esc_html__( 'No thanks', 'bew-extras' ); ?>
				</button>
			</div>
		</div>
		<?php
	}

	private function bewia_get_product_options() {
		$options = array();

		if ( ! function_exists( 'wc_get_products' ) ) {
			return $options;
		}

		$products = wc_get_products(
			array(
				'limit'  => 200,
				'status' => 'publish',
				'return' => 'objects',
			)
		);

		if ( empty( $products ) || ! is_array( $products ) ) {
			return $options;
		}

		foreach ( $products as $product ) {
			if ( ! $product || ! method_exists( $product, 'get_id' ) ) {
				continue;
			}

			$options[ $product->get_id() ] = $product->get_name();
		}

		return $options;
	}

	private function bewia_get_product_category_options() {
		$options = array();

		if ( ! taxonomy_exists( 'product_cat' ) ) {
			return $options;
		}

		$terms = get_terms(
			array(
				'taxonomy'   => 'product_cat',
				'hide_empty' => false,
				'number'     => 200,
			)
		);

		if ( is_wp_error( $terms ) || empty( $terms ) ) {
			return $options;
		}

		foreach ( $terms as $term ) {
			if ( empty( $term->term_id ) ) {
				continue;
			}

			$options[ absint( $term->term_id ) ] = $term->name;
		}

		return $options;
	}

	private function bewia_build_priority_map( $rows ) {
		$priority_map = array();

		if ( ! is_array( $rows ) ) {
			return $priority_map;
		}

		foreach ( $rows as $row ) {
			if ( empty( $row['product_id'] ) ) {
				continue;
			}

			$product_id = absint( $row['product_id'] );

			if ( $product_id <= 0 ) {
				continue;
			}

			$priority_map[ $product_id ] = isset( $row['priority_score'] ) ? (float) $row['priority_score'] : 0.0;
		}

		return $priority_map;
	}
}

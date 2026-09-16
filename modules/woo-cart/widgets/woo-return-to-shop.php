<?php
namespace BriefcasewpExtras\Modules\WooCart\Widgets;

use Elementor;
use Elementor\Plugin;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;  
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;   
use Elementor\Group_Control_Box_Shadow;
use Elementor\Repeater;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use BriefcasewpExtras\Base\Base_Widget;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Woo_Return_To_Shop extends Base_Widget {

	public function get_name() {
		return 'woo-return-to-shop';
	}

	public function get_title() {
		return __( 'Woo Return to Shop', 'briefcase-extras' );
	}

	public function get_icon() {
		return 'eicon-woocommerce';
	}

	public function get_categories() {
		return [ 'bew-extras-cart' ];
	}
	
	public function get_script_depends() {
		return [ 'woo-general' ];
	}
	
	public function is_reload_preview_required() {
		return true;
	}
	
	protected function _register_controls() {
		
		 // Return shop content
        $this->start_controls_section(
            'wrts_content',
            [
                'label' => __( 'Woo Return to Shop', 'briefcase-extras' ),
            ]
        );
		
		$this->add_control(
			'wrts_button_text',
			[
				'label' 		=> __( 'Button Text', 'briefcase-extras' ),
				'type' 			=> Controls_Manager::TEXT,
				'default' 		=> __( 'Return to Shop', 'briefcase-extras' ),
				'placeholder' 	=> __( 'Type button text here', 'briefcase-extras' ),				
			]
		);
				
		$this->add_responsive_control(
            'wrts_text_align',
            [
                'label'        => __( 'Text Alignment', 'briefcase-extras' ),
                'type'         => Controls_Manager::CHOOSE,
				'options' 	   => [
					'left' 		=> [
						'title' 	=> __( 'Left', 'bew-extras' ),
						'icon' 		=> 'eicon-text-align-left',
					],
					'center' 	=> [
						'title' 	=> __( 'Center', 'bew-extras' ),
						'icon' 		=> 'eicon-text-align-center',
					],
					'right' 	=> [
						'title' 	=> __( 'Right', 'bew-extras' ),
						'icon' 		=> 'eicon-text-align-right',
					],
					'justify' => [
						'title' => __( 'Justified', 'bew-extras' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
                'selectors' => [
                    '{{WRAPPER}} .bew-return-to-shop' => 'text-align: {{VALUE}}',
                ],
            ]
        );	
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'wrts_section_style',
			array(
				'label' => __( 'Style', 'briefcase-extras' ),
				'tab' => Controls_Manager::TAB_STYLE,
			)
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'wrts_button_typography',
				'label'     => __( 'Typography', 'briefcase-extras' ),
				'selector'  => '{{WRAPPER}} .bew-return-to-shop .button',
			)
		);
		
		$this->start_controls_tabs( 'button_style_tabs' );
		
		$this->start_controls_tab( 'button_style_normal',
			[
				'label' => __( 'Normal', 'briefcase-extras' ),
			]
		);
		
		$this->add_control(
			'wrts_button_text_color',
			[
				'label' => __( 'Text Color', 'briefcase-extras' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-return-to-shop .button' => 'color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'wrts_button_bg_color',
			[
				'label' => __( 'Background Color', 'briefcase-extras' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-return-to-shop .button' => 'background-color: {{VALUE}}',
				],
			]
		);
				
		$this->end_controls_tab();
		
		$this->start_controls_tab( 'button_style_hover',
			[
				'label' => __( 'Hover', 'briefcase-extras' ),
			]
		);

		$this->add_control(
			'wrts_button_text_color_hover',
			[
				'label' => __( 'Text Color', 'briefcase-extras' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-return-to-shop .button:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'wrts_button_bg_color_hover',
			[
				'label' => __( 'Background Color', 'briefcase-extras' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-return-to-shop .button:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'wrts_button_border_color_hover',
			[
				'label' => __( 'Border Color', 'briefcase-extras' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'wrts_button_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .bew-return-to-shop .button:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'wrts_button_transition',
			[
				'label' => __( 'Transition Duration', 'briefcase-extras' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0.2,
				],
				'range' => [
					'px' => [
						'max' => 2,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bew-return-to-shop .button' => 'transition: all {{SIZE}}s',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();
				
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'wrts_button_border',
				'selector' => '{{WRAPPER}} .bew-return-to-shop .button',
			]
		);
				
		$this->add_control(
			'wrts_button_border_radius',
			[
				'label' => __( 'Border Radius', 'briefcase-extras' ),
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .bew-return-to-shop .button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_control(
            'wrts_button_width',
            [
                'label' => __( 'Button Width', 'bew-extras' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],                    
                'selectors' => [
                    '{{WRAPPER}} .bew-return-to-shop .button' => 'width: {{SIZE}}{{UNIT}} !important;',
                ],
            ]
        );
		
		$this->add_responsive_control(
			'wrts_button_padding',
			[
				'label' => __( 'Padding', 'briefcase-extras' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bew-return-to-shop .button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);	

		$this->add_responsive_control(
			'wrts_button__margin',
			[
				'label' 		=> __( 'Margin', 'briefcase-extras' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%', 'em' ],
				'selectors' 	=> [
					'{{WRAPPER}} .bew-return-to-shop .button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
		
	}

	/**
	 * Render widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		
		$settings  = $this->get_settings_for_display();
		
		$wrts_button_text    =  $settings['wrts_button_text'];			
		
		if ( wc_get_page_id( 'shop' ) > 0 ) : ?>
			<div class="bew-return-to-shop">
				<a class="button wc-backward" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>">
					<?php esc_html_e( $wrts_button_text , 'woocommerce' ); ?>
				</a>
			</div>
		<?php endif;
	}
	
}
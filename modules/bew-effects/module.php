<?php
namespace BriefcasewpExtras\Modules\BewEffects;

use Elementor;
use Elementor\Plugin;
use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use BriefcasewpExtras\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Module extends Module_Base {

	public function __construct() {
		parent::__construct();

		$this->add_actions();
	}

	public function get_name() {
		return 'bew-effects';
	}

	public function register_controls( Controls_Stack $element ) {
		$element->start_controls_section(
			'section_bew_effects',
			[
				'label' => __( 'Bew Effects', 'briefcase-extras' ),
				'tab' => Controls_Manager::TAB_ADVANCED,				
			]
		);

		$element->add_control(
			'bew_hover_effects',
			[
				'label' => __( 'Hover Effects', 'briefcase-extras' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'On', 'briefcase-extras' ),
				'label_off' => __( 'Off', 'briefcase-extras' ),
				'return_value' => 'yes',
				'default' => '',
				'frontend_available' => true,
				'prefix_class'  => 'bew-hover-'
			]
		);
		
		$element->add_control(
			'heading_bew_hover_section',
			[
				'label' => __( 'Hover Section', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'bew_hover_effects' => 'yes',					
				],
				
			]
		);
		
		$element->add_control(
			'bew_hover_selector',
			[
				'label' => __( 'Hover Section', 'briefcase-extras' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'On', 'briefcase-extras' ),
				'label_off' => __( 'Off', 'briefcase-extras' ),
				'return_value' => 'yes',
				'default' => '',
				'frontend_available' => true,
				'condition' => [
					'bew_hover_effects' => 'yes',					
				],
				'prefix_class'  => 'bew-hover-selector-'
			]
		);
		
		$element->add_control(
			'heading_bew_action_section',
			[
				'label' => __( 'Action Section', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'bew_hover_effects' => 'yes',					
				],				
			]
		);
		
		$element->add_control(
			'bew_hover',
			[
				'label' => __( 'Hover Animation', 'briefcase-extras' ),
				'type' => Controls_Manager::SELECT,
			'options' => [
				'' => __( 'None', 'briefcase-extras' ),
				'fade-in' => __( 'Fade In', 'briefcase-extras' ),
				'fade-up' => __( 'Fade Up', 'briefcase-extras' ),				
			],
			'condition' => [
					'bew_hover_effects' => 'yes',					
			],
			'prefix_class' => 'bew-hover-',
			]			
		);
		
		$element->add_responsive_control(
			'bew_hover_distance',
			[
				'label' => __( 'Translate Distance (px)', 'briefcase-extras' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px', 'vw' ],
				'range' => [
					'%' => [
						'min' => 1,
						'max' => 100,
					],
					'px' => [
						'min' => 1,
						'max' => 1000,
					],
					'vw' => [
						'min' => 1,
						'max' => 100,
					],
				],								
				'description' => __( 'Choose distance to translate section on hover up', 'briefcase-extras' ),
				'selectors' => [
					'.bew-hover-selector-yes:hover .bew-hover-fade-up' => 'transform: translateY(-{{SIZE}}{{UNIT}});',
				],
				'condition' => [
					'bew_hover' => 'fade-up',
				],
			]
		);
				
		$element->end_controls_section();
	}

	private function add_actions() {
				
		// Add Bew Effects on section
		add_action( 'elementor/element/column/section_custom_css/before_section_start', [ $this, 'register_controls' ] );	
		add_action( 'elementor/element/section/section_custom_css/before_section_start', [ $this, 'register_controls' ] );
		
		// Add Bew Effects on container
		add_action( 'elementor/element/container/section_custom_css/before_section_start', [ $this, 'register_controls' ] );
	}
	
	public function after_render_bew_scroll($element) {	}
		
	public function enqueue_styles() {	}
		
	public function enqueue_scripts() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_enqueue_script(
			'bew-scroll',
			BEW_EXTRAS_URL . 'assets/js/bew-scroll.js',
			[
				'jquery',
			],
			BEW_EXTRAS_VERSION,
			true
		);
	}
	
	
}

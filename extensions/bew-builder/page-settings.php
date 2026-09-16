<?php
namespace TheThemeio;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Main Plugin Class. Register new elementor widget.
 */
class Page_Settings {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->add_actions();
	}


	/**
	 * Add Actions
	 */
	private function add_actions() {

		add_action( 'elementor/element/page-settings-'. get_the_ID() .'/section_page_settings/after_section_end', function( $element, $args ) {
			$this->topbar_settings( $element );
			$this->custom_css_settings( $element );
			$this->custom_js_settings( $element );
			$this->og_settings( $element );
		}, 10, 2 );

		add_action( 'elementor/element/page-settings-'. get_the_ID() .'/section_page_settings/before_section_end', function( $element, $args ) {

			$element->add_control(
				'page_body_class',
				[
					'label' => esc_html__( 'Extra body classes', 'thesaas' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'description' => esc_html__( 'Space separated class names', 'thesaas' ),
					'label_block' => true,
				]
			);
		}, 10, 2 );

	}



	/**
	 * Topbar configs.
	 */
	private function topbar_settings( $element ) {

		$post_id = get_the_ID();
		$page = \Elementor\PageSettings\Manager::get_page( $post_id ); 

		$element->start_controls_section(
			'topbar_settings_section',
			[
				'tab' => \Elementor\Controls_Manager::TAB_SETTINGS,
				'label' => esc_html__( 'Topbar settings', 'thesaas' ),
			]
		);

		$element->add_control(
		  'html_msg',
		  [
		     'type'    => \Elementor\Controls_Manager::RAW_HTML,
		     'raw' => '<i>'. esc_html__( 'Refresh is required to see the changes.', 'thesaas' ) .'</i>',
			 'content_classes' => '',
		  ]
		);

		$menu_arr = array();
		$menu_arr[ 'default' ] = esc_html__( 'Default', 'thesaas' );
		$menu_arr[ 'none' ] = esc_html__( 'None', 'thesaas' );

		$menus = get_terms( 'nav_menu' );
		foreach( $menus as $menu ) {
		  $menu_arr[ $menu->slug ] = $menu->name;
		}

		$element->add_control(
			'topbar_menu',
			[
				'label' => esc_html__( 'Menu', 'thesaas' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => $menu_arr,
				'default' => 'default',
			]
		);

		$element->add_control(
			'topbar_is_sticky',
			[
				'label' => esc_html__( 'Sticky topbar', 'thesaas' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'No', 'thesaas' ),
				'label_on' => esc_html__( 'Yes', 'thesaas' ),
				'default' => 'topbar-sticky',
				'return_value' => 'topbar-sticky',
			]
		);

		$element->add_control(
			'topbar_inverse',
			[
				'label' => esc_html__( 'Light text color', 'thesaas' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'No', 'thesaas' ),
				'label_on' => esc_html__( 'Yes', 'thesaas' ),
				'default' => 'topbar-inverse',
				'return_value' => 'topbar-inverse',
			]
		);

		$element->add_control(
			'topbar_breakpoint',
			[
				'label' => esc_html__( 'Breakpoint', 'thesaas' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'xs'    => [
						'title' => esc_html__( 'Extra small', 'thesaas' ),
						'icon' => 'fa fa-mobile',
					],
					'sm' => [
						'title' => esc_html__( 'Small', 'thesaas' ),
						'icon' => 'fa fa-tablet',
					],
					'md' => [
						'title' => esc_html__( 'Medium', 'thesaas' ),
						'icon' => 'fa fa-laptop',
					],
					'lg' => [
						'title' => esc_html__( 'Large', 'thesaas' ),
						'icon' => 'fa fa-tv',
					],
				],
				'default' => 'md',
			]
		);


		$element->add_control(
			'topbar_container',
			[
				'label' => esc_html__( 'Container', 'thesaas' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'container',
				'options' => [
					'container'       => esc_html__( 'Default', 'thesaas' ),
					'container-fluid' => esc_html__( 'Fluid', 'thesaas' ),
					'container-wide'  => esc_html__( 'Wide', 'thesaas' ),
				],
			]
		);


		/**
		 * Button 1
		 */
		$element->add_control(
			'topbar_btn_1_text',
			[
				'label' => esc_html__( 'Button 1 text', 'thesaas' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'label_block' => true,
				'separator' => 'before',
			]
		);


		$element->add_control(
			'topbar_btn_1_link',
			[
				'label' => esc_html__( 'Button 1 link', 'thesaas' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'default' => '',
			]
		);

		$element->add_control(
			'topbar_btn_1_color',
			[
				'label' => esc_html__( 'Button 1 color', 'thesaas' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'white',
				'options' => [
					'primary'   => esc_html__( 'Primary', 'thesaas' ),
					'secondary' => esc_html__( 'Secondary', 'thesaas' ),
					'info'      => esc_html__( 'Info', 'thesaas' ),
					'success'   => esc_html__( 'Success', 'thesaas' ),
					'warning'   => esc_html__( 'Warning', 'thesaas' ),
					'danger'    => esc_html__( 'Danger', 'thesaas' ),
					'white'     => esc_html__( 'White', 'thesaas' ),
					'dark'      => esc_html__( 'Dark', 'thesaas' ),
				],
			]
		);

		$element->add_control(
			'topbar_btn_1_outline',
			[
				'label' => esc_html__( 'Outline 1 style', 'thesaas' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'No', 'thesaas' ),
				'label_on' => esc_html__( 'Yes', 'thesaas' ),
				'default' => '',
				'return_value' => 'btn-outline',
			]
		);


		/**
		 * Button 2
		 */
		$element->add_control(
			'topbar_btn_2_text',
			[
				'label' => esc_html__( 'Button 2 text', 'thesaas' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'label_block' => true,
				'separator' => 'before',
			]
		);


		$element->add_control(
			'topbar_btn_2_link',
			[
				'label' => esc_html__( 'Button 2 link', 'thesaas' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'default' => '',
			]
		);

		$element->add_control(
			'topbar_btn_2_color',
			[
				'label' => esc_html__( 'Button 2 color', 'thesaas' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'white',
				'options' => [
					'primary'   => esc_html__( 'Primary', 'thesaas' ),
					'secondary' => esc_html__( 'Secondary', 'thesaas' ),
					'info'      => esc_html__( 'Info', 'thesaas' ),
					'success'   => esc_html__( 'Success', 'thesaas' ),
					'warning'   => esc_html__( 'Warning', 'thesaas' ),
					'danger'    => esc_html__( 'Danger', 'thesaas' ),
					'white'     => esc_html__( 'White', 'thesaas' ),
					'dark'      => esc_html__( 'Dark', 'thesaas' ),
				],
			]
		);

		$element->add_control(
			'topbar_btn_2_outline',
			[
				'label' => esc_html__( 'Outline 2 style', 'thesaas' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'No', 'thesaas' ),
				'label_on' => esc_html__( 'Yes', 'thesaas' ),
				'default' => 'btn-outline',
				'return_value' => 'btn-outline',
			]
		);


		$element->end_controls_section();

	}



	/**
	 * Custom CSS code for page
	 */
	private function custom_css_settings( $element ) {

		$element->start_controls_section(
			'custom_css_settings_section',
			[
				'tab' => \Elementor\Controls_Manager::TAB_SETTINGS,
				'label' => esc_html__( 'Custom CSS', 'thesaas' ),
			]
		);


		/**
		 * Input
		 */
		$element->add_control(
			'page_custom_css',
			[
				//'label' => esc_html__(''),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'label_block' => true,
				'description' => esc_html__( 'Requires page reload', 'thesaas' ),
			]
		);


		$element->end_controls_section();

	}



	/**
	 * Custom JS code for page
	 */
	private function custom_js_settings( $element ) {

		$element->start_controls_section(
			'custom_js_settings_section',
			[
				'tab' => \Elementor\Controls_Manager::TAB_SETTINGS,
				'label' => esc_html__( 'Custom Javascript', 'thesaas' ),
			]
		);


		/**
		 * Input
		 */
		$element->add_control(
			'page_custom_js',
			[
				//'label' => esc_html__(''),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'label_block' => true,
				'description' => esc_html__( 'Requires page reload.<br>Please note that JS error can break your page\'s functionality.', 'thesaas' ),
			]
		);


		$element->end_controls_section();

	}




	/**
	 * Open Graph Meta tags.
	 */
	private function og_settings( $element ) {

		$element->start_controls_section(
			'og_settings_section',
			[
				'tab' => \Elementor\Controls_Manager::TAB_SETTINGS,
				'label' => esc_html__( 'Open Graph meta tags', 'thesaas' ),
			]
		);


		/**
		 * Image
		 */
		$element->add_control(
			'og_image',
			[
				'label' => esc_html__( 'Image', 'thesaas' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
			]
		);


		/**
		 * Title
		 */
		$element->add_control(
			'og_title',
			[
				'label' => esc_html__( 'Title', 'thesaas' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
			]
		);


		/**
		 * Description
		 */
		$element->add_control(
			'og_description',
			[
				'label' => esc_html__( 'Description', 'thesaas' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'label_block' => true,
			]
		);


		$element->end_controls_section();

	}


}


new Plugin();

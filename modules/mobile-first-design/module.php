<?php
namespace BriefcasewpExtras\Modules\MobileFirstDesign;

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
		return 'mobile-first-design';
	}


	public function get_script_depends() {
		return [ 'bew-swiper' ];
	}
	
	public function register_controls( Controls_Stack $element ) {
		$element->start_controls_section(
			'section_bew_mobile_first',
			[
				'label' => __( 'Bew Mobile First Design', 'briefcase-extras' ),
				'tab' => Controls_Manager::TAB_ADVANCED,				
			]
		);

		$element->add_control(
			'bew_mobile_first',
			[
				'label' => __( 'Enable', 'briefcase-extras' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'On', 'briefcase-extras' ),
				'label_off' => __( 'Off', 'briefcase-extras' ),
				'return_value' => 'yes',
				'default' => '',
				'frontend_available' => true,
				'prefix_class'  => 'bew-mobile-first-'
			]
		);
		
		$element->add_control(
			'bew_mobile_first_content_fold',
			[
				'label' => __( 'Content Fold', 'briefcase-extras' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'On', 'briefcase-extras' ),
				'label_off' => __( 'Off', 'briefcase-extras' ),
				'return_value' => 'yes',
				'default' => '',
				'frontend_available' => true,
				'condition' => [
					'bew_mobile_first' => 'yes',					
				],
				'prefix_class'  => 'bew-mobile-first-content-fold-'
			]
		);
		
		$element->add_control(
			'bew_mobile_first_ripple',
			[
				'label' => __( 'Ripple Effect', 'briefcase-extras' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'On', 'briefcase-extras' ),
				'label_off' => __( 'Off', 'briefcase-extras' ),
				'return_value' => 'yes',
				'default' => '',
				'frontend_available' => true,
				'condition' => [
					'bew_mobile_first' => 'yes',					
				],
				'prefix_class'  => 'bew-mobile-first-ripple-'
			]
		);
		
		$element->add_control(
			'bew_mobile_first_swipe',
			[
				'label' => __( 'Swipe', 'briefcase-extras' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'On', 'briefcase-extras' ),
				'label_off' => __( 'Off', 'briefcase-extras' ),
				'return_value' => 'yes',
				'default' => '',
				'frontend_available' => true,
				'condition' => [
					'bew_mobile_first' => 'yes',					
				],
				'prefix_class'  => 'bew-mobile-first-swipe-'
			]
		);

		$element->end_controls_section();
		
		$element->start_controls_section(
			'section_bew_mobile_first_style',
			[
				'label' => __( 'Bew Mobile First Design', 'briefcase-extras' ),
				'tab' => Controls_Manager::TAB_STYLE,	
				'condition' => [
					'bew_mobile_first_content_fold' => 'yes',					
				],
			]
		);
		
		$element->add_control(
			'bew_mobile_first_content_style',
			[
				'label'     => __( 'Content Accordion', 'briefcase-extras' ),
				'type'      => Controls_Manager::HEADING,				
				'condition' => [
					'bew_mobile_first_content_fold' => 'yes',					
				],
			]
		);
		
		$element->add_control(
			'bew_mobile_first_plus_style',
			[
				'label' => __( 'Plus Color', 'briefcase-extras' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'.fold .fold__toggle:before, .fold .fold__toggle:after' => 'background-color: {{VALUE}};',
				],				
				'condition' => [
					'bew_mobile_first_content_fold' => 'yes',					
				],				
			]
		);
		
		$element->end_controls_section();
	}

	private function add_actions() {
				
		// Add Bew Mobile on section
		add_action( 'elementor/element/column/section_custom_css/before_section_start', [ $this, 'register_controls' ] );	
		add_action( 'elementor/element/section/section_custom_css/before_section_start', [ $this, 'register_controls' ] );
		
		// Add Bew Mobile on container
		add_action( 'elementor/element/container/section_custom_css/before_section_start', [ $this, 'register_controls' ] );
		
		//add_action( 'elementor/frontend/column/after_render', [ $this, 'after_render_bew_mobile_first'], 10, 1 );
		//add_action( 'elementor/frontend/section/after_render', [ $this, 'after_render_bew_mobile_first'], 10, 1 );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		
	}
	
	public function after_render_bew_mobile_first($element) {
		$settings 		= $element->get_settings();
		if (isset($settings['bew_mobile_first'])){
		$bew_mobile_first    = $settings['bew_mobile_first'];
		
		if ($bew_mobile_first) {
		$id = $element->get_id();
		$selector = '".elementor-element-' . $id . '"';		 
		?>
		<script type="text/javascript">
			jQuery(function($) {				
			$(<?php echo $selector; ?>).addClass('bmfd__fold fold'); 
			$(<?php echo $selector; ?> + " .elementor-element.elementor-widget-heading:first-of-type").addClass('heading--add fold__toggle');
			$(<?php echo $selector; ?> + " .elementor-element").not(".elementor-widget-heading:first-of-type").not(".elementor-column").addClass('fold__content'); 
			});		
		</script>	
		<?php
		}
		}
					
	}
		
	public function enqueue_styles() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		
	}
		
	public function enqueue_scripts() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		wp_enqueue_script( 'bew-swiper' );
	}
	
	
}

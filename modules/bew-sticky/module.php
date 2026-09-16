<?php
namespace BriefcasewpExtras\Modules\BewSticky;

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
		return 'bew-sticky';
	}

	public function get_script_depends() {
		return [ 'woo-single-product', 'sticky-kit' ];
	}
	
	public function register_controls( Controls_Stack $element ) {
		$element->start_controls_section(
			'section_bew_sticky',
			[
				'label' => __( 'Bew Sticky', 'briefcase-extras' ),
				'tab' => Controls_Manager::TAB_ADVANCED,				
			]
		);

		$element->add_control(
			'bew_sticky',
			[
				'label' => __( 'Enable Sticky', 'briefcase-extras' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'On', 'briefcase-elementor-widgets' ),
				'label_off' => __( 'Off', 'briefcase-elementor-widgets' ),
				'return_value' => 'yes',
				'default' => '',
				'frontend_available' => true,
				'prefix_class'  => 'bew-sticky-'
			]
		);

		$element->add_control(
			'bew_sticky_bottom',
			[
				'label' => __('Enable On', 'briefcase-extras'),
				'type' => Controls_Manager::SELECT,				
				'options' => [
					'top' => __('Top', 'briefcase-extras'),
					'bottom' => __('Bottom', 'briefcase-extras'),
				],
				'default' => 'top',
				'frontend_available' => true,
				'prefix_class'  => 'bew-sticky-',
				'condition' => [
					'bew_sticky' => 'yes',
				],
			]
		);
		
		$element->add_control(
			'bew_sticky_bg',
			[
				'label' => __( 'Enable Background', 'briefcase-extras' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'On', 'briefcase-extras' ),
				'label_off' => __( 'Off', 'briefcase-extras' ),
				'return_value' => 'yes',
				'default' => '',
				'prefix_class'  => 'bew-sticky-bg-',
				'condition' => [
					'bew_sticky' => 'yes',
				],
			]
		);
		
		$element->add_control(
			'bew_sticky_bg_color',
			[
				'label'     => __( 'Background Color', 'briefcase-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}::after' => 'background: {{VALUE}}',
				],
				'condition' => [
					'bew_sticky' => 'yes',
					'bew_sticky_bg' => 'yes'
				],
			]
		);

		$element->add_control(
			'bew_sticky_absolute',
			[
				'label' => __( 'Enable Absolute', 'briefcase-extras' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'On', 'briefcase-elementor-widgets' ),
				'label_off' => __( 'Off', 'briefcase-elementor-widgets' ),
				'return_value' => 'yes',
				'default' => '',
				'frontend_available' => true,
				'prefix_class'  => 'bew-sticky-absolute-',
				'condition' => [
					'bew_sticky' => 'yes',
				],
			]
		);
		
		$element->add_control(
			'bew_sticky_absolute_float',
			[
				'label' => __( 'Enable Float', 'briefcase-extras' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'On', 'briefcase-extras' ),
				'label_off' => __( 'Off', 'briefcase-extras' ),
				'return_value' => 'yes',
				'default' => '',
				'frontend_available' => true,
				'prefix_class'  => 'bew-sticky-absolute-float',
				'condition' => [
					'bew_sticky' => 'yes',
				],
			]
		);
		
		$element->add_responsive_control(
			'offset_distance',
			[
				'label' => __( 'Offset Distance (px)', 'briefcase-extras' ),
				'type' => Controls_Manager::SLIDER,				
				'default' => [
					'size' => 0,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
					],
				],
				'size_units' => [ 'px'],
				'description' => __( 'Choose offset distance to enable Sticky element', 'briefcase-extras' ),				
				'condition' => [
					'bew_sticky' => 'yes',
				],
				'selectors' => [
                    '{{WRAPPER}}.elementor-column.bew-sticky-yes .elementor-widget-wrap, {{WRAPPER}}.elementor-element.bew-sticky-yes > .elementor-element' => 'top: {{SIZE}}{{UNIT}};',
					
                ],
			]
		);

		$element->end_controls_section();
		
		$element->start_controls_section(
			'section_bew_off_canvas',
			[
				'label' => __( 'Bew Off Canvas', 'briefcase-extras' ),
				'tab' => Controls_Manager::TAB_ADVANCED,				
			]
		);
		
		$element->add_control(
			'bew_off_canvas',
			[
				'label' => __( 'Enable Off Canvas', 'briefcase-extras' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'On', 'briefcase-elementor-widgets' ),
				'label_off' => __( 'Off', 'briefcase-elementor-widgets' ),
				'return_value' => 'yes',
				'default' => '',
				'frontend_available' => true,
				'prefix_class'  => 'bew-off-canvas-'
			]
		);

		
		$element->end_controls_section();
	}

	private function add_actions() {

		// Add Bew Sticky on section
		add_action( 'elementor/element/column/section_custom_css/before_section_start', [ $this, 'register_controls' ] );	
		add_action( 'elementor/element/section/section_custom_css/before_section_start', [ $this, 'register_controls' ] );
		
		// Add Bew Sticky on container
		add_action( 'elementor/element/container/section_custom_css/before_section_start', [ $this, 'register_controls' ] );
		
		add_action( 'elementor/frontend/column/after_render', [ $this, 'after_render_bew_sticky'], 10, 1 );
		add_action( 'elementor/frontend/section/after_render', [ $this, 'after_render_bew_sticky_section'], 10, 1 );
		
		// Add Bew Off Canvas Button on column
		add_action( 'elementor/frontend/column/before_render', [ $this, 'bew_off_canvas_start'] );
		// Add Bew Off Canvas Button on container
		add_action( 'elementor/frontend/container/before_render', [ $this, 'bew_off_canvas_start'] );
		
		
	}
	
	public function after_render_bew_sticky($element) {
		$settings 		= $element->get_settings();
		if (isset($settings['bew_sticky'])){
		$bew_sticky     = $settings['bew_sticky'];
		
		if ($bew_sticky) {
		$id = $element->get_id();
		$selector = '".elementor-element-' . $id . '"';		 
		?>
		<script type="text/javascript">
			jQuery(function($) {				
			$(<?php echo $selector; ?>).addClass('bew-sticky'); 
				
			});		
		</script>	
		<?php
		}
		}
	}
	
	public function after_render_bew_sticky_section($element) {
		$settings 		= $element->get_settings();
		if (isset($settings['bew_sticky'])){
			$bew_sticky           = $settings['bew_sticky'];
			$bew_sticky_absolute  = $settings['bew_sticky_absolute'];
			
			if ($bew_sticky) {
				$id = $element->get_id();
				$selector = '".elementor-element-' . $id . '"';		 
				?>
				<script type="text/javascript">
					jQuery(function($) {				
					$(<?php echo $selector; ?>).addClass('bew-sticky-section'); 
						
					});		
				</script>	
				<?php
			}
			
			if ($bew_sticky_absolute) {
				$id = $element->get_id();
				$selector = '".elementor-element-' . $id . '"';		 
				?>
				<script type="text/javascript">
					jQuery(function($) {				
					$(<?php echo $selector; ?>).addClass('bew-sticky-section-absolute'); 
						
					});		
				</script>	
				<?php
			}
		}
					
	}

	public function bew_off_canvas_start( $element ) {
		$settings = $element->get_settings_for_display();
		
		if( $settings['bew_off_canvas'] == 'yes' ) {
		?>
		<div class="product-info-togggle-wrapper">
			<div class="product-info-togggle-inner">
			  <button type="button" is="toggle-info" class="product-info-togggle" aria-label="view details">
				<span class="svg-wrapper">
				  
			<svg viewBox="0 0 6 12" fill="none" class="icon-arrow-left">
			  <path d="M-3.12348e-05 6.00003C-3.12292e-05 5.87216 0.0488436 5.74416 0.146468 5.64653L5.14647 0.646531C5.34184 0.451156 5.65822 0.451156 5.85347 0.646531C6.04872 0.841906 6.04884 1.15828 5.85347 1.35353L1.20697 6.00003L5.85347 10.6465C6.04884 10.8419 6.04884 11.1583 5.85347 11.3535C5.65809 11.5488 5.34172 11.5489 5.14647 11.3535L0.146468 6.35353C0.0488436 6.25591 -3.12404e-05 6.12791 -3.12348e-05 6.00003Z" fill="currentColor"></path>
			</svg>
		  

				</span>
			  </button>
			</div>
		</div>
			<?php
		}
	}
	
	public function bew_off_canvas_close( $element ) {

	}
	
	public function enqueue_styles() {		
		
	}
		
	public function enqueue_scripts() {

	}
	
	
}

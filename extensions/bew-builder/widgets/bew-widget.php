<?php
namespace BriefcasewpExtras\Widgets;

use Elementor;
use Elementor\Plugin;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Scheme_Typography;
use Elementor\Scheme_Color;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Bew_Widget extends Widget_Base {

	public $types_number = 0;
	public $current_id = 0;
	public $block = array();

	public function get_name() {
		return 'the-general-name';
	}

	public function get_categories() {
		return [ 'bew-block' ];
	}

	public function is_reload_preview_required() {
		//return true;
	}

	public function set_id( $id ) {
		$this->current_id = $id;
	}
	
	public function get_script_depends() {
		return [ 'bew-extras-scripts' , 'bew-swiper' ];
	}
	
	public function load_blocks() {
		$class = get_class( $this );
		$widget_name = strtolower( str_replace( 'BriefcasewpExtras\Widgets\Bew_', '', $class ) );
		$dir = __DIR__ .'/blocks/'. $widget_name;
		$blocks = array_diff( scandir( $dir ), array( '.', '..', 'base.php' ) );
		$this->types_number = count( $blocks );
		$block_prefix = $class .'_Block_';

		// Load blocks
		//
		for ( $i = 1; $i <= $this->types_number; $i++ ) {
			if ( ! is_child_theme() ) {
				require_once $dir . '/block-'. $i .'.php';
			}
			else {
				$filename = get_stylesheet_directory() . '/blocks/'. $widget_name . '/block-'. $i .'.php';
				if ( file_exists($filename) ) {
					require_once $filename;
				}
				else {
					require_once $dir . '/block-'. $i .'.php';
				}
			}
			
			$className = $block_prefix . $i;
			$this->block[ $i ] = new $className;
		}


		// Load extra blocks from child theme
		// 
		if ( is_child_theme() ) {
			$dir = get_stylesheet_directory() . '/blocks/'. $widget_name;
		
			foreach ( glob( $dir . "/block-1??.php" ) as $filename ) {
			  require_once $filename;

			  $i = substr( $filename, -7 );
			  $i = intval( substr( $i, 0, 3) );
			  $className = $block_prefix . $i;
				$this->block[ $i ] = new $className;
			}


		}

	}


	public function panel( $type, $arg = [] ) {
		$controls = Bew_Panels::$type( $arg );

		Bew_Controls::start_section( $this, $type, $this->current_id, $arg );
		Bew_Controls::add( $controls, $this, $this->current_id );
		Bew_Controls::end_section( $this );
	}



	public function html( $control, $arg = [] ) {
		Bew_Render::$id = $this->current_id;
		Bew_Render::$widget = $this;
		$method = $control .'_html';
		Bew_Render::$method( $arg );
	}


	public function js( $control, $arg = [] ) {
		Bew_Render::$id = $this->current_id;
		$method = $control .'_js';
		Bew_Render::$method( $arg );
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_type',
			[
				'label' => esc_html__( 'Type', 'briefcasewp-extras' ),
			]
		);

		$types = array();
		$block = substr( $this->get_name(), 4 );
		/*
		for ($i=1; $i <= $this->types_number ; $i++) {
			//$types[ 'type-'. $i ] = esc_html__( 'Type ', 'briefcasewp-extras' ) . $i;
			$types[ 'type-'. $i ] = thesaas_get_img_uri( '/blocks/'. $block .'/'. $i .'.jpg' );
		}
		*/
		foreach ($this->block as $i => $value) {
			
			$options[ 'type-'. $i ] = ["image"=> BEW_EXTRAS_URL . 'extensions/bew-builder/assets/img/blocks/'. $block .'/'. $i .'.jpg',
									 "title"=> $block . ' type-'. $i 
									];
			
		}
				
		$this->add_control(
			'type',
			[
				'label' => esc_html__( 'Type', 'briefcasewp-extras' ),
				'type' => 'chooseimagery',
				'default' => 'type-1',
				'options' => $options,
			]
		);

		$this->end_controls_section();

		/*
		for ( $i = 1; $i <= $this->types_number; $i++ ) {
			$this->block[ $i ]->controls( $this );
		}
		*/
		foreach ($this->block as $key => $value) {
			$value->controls( $this );
		}

	}

	protected function render() {
		$block_num = intval( str_replace( 'type-', '', $this->get_settings( 'type' ) ) );
		$this->block[ $block_num ]->html( $this );
	}
	
	
	protected function _content_template() {
	$class = get_class( $this );
	$widget_name = strtolower( str_replace( 'BriefcasewpExtras\Widgets\Bew_', '', $class ) );
	
		if($widget_name  == 'categories' || $widget_name  == 'product_tabs'){	
		
		}else{
			?>
			
			<#
			switch ( settings.type ) {
				<?php
					/*
					for ($i=1; $i <= $this->types_number; $i++) :
						echo "case 'type-$i': #>";
						$this->block[ $i ]->javascript( $this );
						echo "<# break;";
					endfor;
					*/
					
					foreach ($this->block as $i => $value) {
						echo "case 'type-$i': #>";
						$value->javascript( $this );
						echo "<# break;";
					}
				?>
			}
			#>
			
			<?php		
			
		}	
	}
	
}

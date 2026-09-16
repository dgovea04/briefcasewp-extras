<?php
namespace BriefcasewpExtras\Modules\BewTemplate\Widgets;

use Elementor;
use ElementorPro\Plugin;
use Elementor\Controls_Manager;
use Elementor\Core\Files\CSS\Post as Post_CSS;
use BriefcasewpExtras\Base\Base_Widget;
use Elementor\Core\Base\Document;
use ElementorPro\Modules\QueryControl\Module as QueryControlModule;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Single_Template extends Base_Widget {

	public function get_name() {
		return 'single-template';
	}

	public function get_title() {
		return __( 'Single Product Template', 'bew-extras' );
	}

	public function get_icon() {
		return 'eicon-woocommerce';
	}

	public function is_reload_preview_required() {
		return true;
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_template',
			[
				'label' => __( 'Single Product', 'bew-extras' ),
			]
		);

		$this->add_control(
			'template_id',
			[
				'label' => __( 'Choose Product', 'bew-extras' ),
				'type' => QueryControlModule::QUERY_CONTROL_ID,
				'label_block' => true,
				'post_type' => 'product',
				'autocomplete' => [
					'object' => QueryControlModule::QUERY_OBJECT_POST,
					'query' => [
						'post_type' => 'product',						
					],
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$id = $this->get_settings( 'template_id' );

		$shortcode = '[product_page id=' . $id . ']';
			
		if( Elementor\Plugin::instance()->editor->is_edit_mode() ) { 
			$shortcode = do_shortcode( shortcode_unautop( $shortcode ) );
		} 
		
		?>
		<div class="bew-single-product-template">
		
		<?php echo $shortcode; ?>
		
		</div>
		<?php
	}

	public function render_plain_content() {
		$id = $this->get_settings( 'template_id' );

		$shortcode = '[product_page id=' . $id . ']';
		
		?>
		<div class="bew-single-product-template">
		
		<?php echo $shortcode; ?>
		
		</div>
		<?php
		
	}
	
	protected function content_template() {}
}

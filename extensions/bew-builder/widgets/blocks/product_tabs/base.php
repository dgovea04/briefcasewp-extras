<?php
namespace BriefcasewpExtras\Widgets;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class Bew_Product_Tabs extends Bew_Widget {

	public function get_name() {
		$this->load_blocks();
		return 'bew-product-tabs';
	}

	public function get_title() {
		return esc_html__( 'Product Tabs', 'briefcasewp-extras' );
	}

	public function get_icon() {
		return 'eicon-woocommerce';
	}

	public function is_reload_preview_required() {
		return true;
	}

}

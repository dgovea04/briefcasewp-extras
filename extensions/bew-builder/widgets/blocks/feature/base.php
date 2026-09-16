<?php
namespace BriefcasewpExtras\Widgets;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class Bew_Feature extends Bew_Widget {

	public function get_name() {
		$this->load_blocks();
		return 'bew-feature';
	}

	public function get_title() {
		return esc_html__( 'Feature', 'briefcasewp-extras' );
	}

	public function get_icon() {
		return 'eicon-info-box';
	}
	
	public function is_reload_preview_required() {
		return true;
	}

}

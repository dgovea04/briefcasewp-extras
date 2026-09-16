<?php
namespace BriefcasewpExtras\Widgets;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class Bew_Content extends Bew_Widget {

	public function get_name() {
		$this->load_blocks();
		return 'bew-content';
	}

	public function get_title() {
		return esc_html__( 'Content', 'briefcasewp-extras' );
	}

	public function get_icon() {
		return 'eicon-flip-box';
	}

	public function is_reload_preview_required() {
		return true;
	}

}

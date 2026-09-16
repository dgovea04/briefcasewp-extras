<?php
namespace BriefcasewpExtras\Widgets;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class Bew_Textual_Feature extends Bew_Widget {

	public function get_name() {
		$this->load_blocks();
		return 'bew-textual-feature';
	}

	public function get_title() {
		return esc_html__( 'Textual Feature', 'briefcasewp-extras' );
	}

	public function get_icon() {
		return 'eicon-icon-box';
	}

}

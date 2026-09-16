<?php
namespace BriefcasewpExtras\Widgets;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class Bew_Faq extends Bew_Widget {

	public function get_name() {
		$this->load_blocks();
		return 'bew-faq';
	}

	public function get_title() {
		return esc_html__( 'FAQ', 'briefcasewp-extras' );
	}

	public function get_icon() {
		return 'eicon-accordion';
	}

}

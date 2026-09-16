<?php
namespace BriefcasewpExtras\Widgets;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class Bew_Subscribe extends Bew_Widget {

	public function get_name() {
		$this->load_blocks();
		return 'bew-subscribe';
	}

	public function get_title() {
		return esc_html__( 'Subscribe', 'briefcasewp-extras' );
	}

	public function get_icon() {
		return 'eicon-mailchimp';
	}

}

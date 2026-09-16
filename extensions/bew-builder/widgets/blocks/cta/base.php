<?php
namespace TheThemeio\Widgets;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class The_Cta extends The_Widget {

	public function get_name() {
		$this->load_blocks();
		return 'the-cta';
	}

	public function get_title() {
		return esc_html__( 'Call to action', 'thesaas' );
	}

	public function get_icon() {
		return 'eicon-call-to-action';
	}

}

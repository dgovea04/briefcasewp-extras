<?php
namespace BriefcasewpExtras\Widgets;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Bew_Panels {

	private static $arg = null;

	public static function section( $arg = [] ) {
		self::$arg = $arg;

		$controls = [
			'section_id'      => [],
			'bg_color'        => self::def('bg_color'),
			'gradient_start'  => self::def('gradient_start'),
			'gradient_end'    => self::def('gradient_end'),
			'switch_sides'    => self::def('switch_sides'),
			'wide_container'  => self::def('wide_container'),
			'section_inverse' => self::def('inverse'),
			'bg_gray'         => self::def('bg_gray'),
			'border_bottom'   => self::def('border_bottom'),
			'bg_image'        => self::def('bg_image'),
			'overlay'         => self::def('overlay'),
			'padding'         => self::def('padding'),
			'width_container' => self::def('wide_container'),
		];

		$required = [ 'section_id', 'border_bottom' ];

		if ( isset( $arg['includes'] ) ) {
			foreach ( $controls as $key => $value ) {
				if ( ! in_array( $key, $arg['includes'] ) && ! in_array( $key, $required ) ) {
					unset( $controls[ $key ] );
				}
			}
		}

		return $controls;

	}

	public static function header_style( $arg = [] ) {
		self::$arg = $arg;

		return [
			'header_style'    => self::def('options'),
		];

	}



	public static function header_content( $arg = [] ) {
		self::$arg = $arg;

		return [
			'small_text'    => self::def('small'),
			'header_text'   => self::def('header'),
			'lead_text'     => self::def('lead'),
		];

	}



	public static function button( $arg = [] ) {
		self::$arg = $arg;

		$controls =  [
			'btn_text'    		=> self::def('text'),
			'btn_link'    		=> self::def('link'),
			'btn_color'   		=> self::def('color'),
			'btn_custom_color'  => self::def('custom_color'),
			'btn_size'    		=> self::def('size'),
			'btn_round'   		=> self::def('round'),
			'btn_outline' 		=> self::def('outline'),
			'btn_block'   		=> self::def('block'),
			'btn_width'   		=> self::def('width'),
		];

		if ( isset( $arg['no_link'] ) ) {
			unset( $controls['btn_link'] );
		}

		return $controls;

	}


	public static function primary_button( $arg = [] ) {
		self::$arg = $arg;

		$controls =  [
			'btn_text'     		=> self::def('text'),
			'btn_link'     		=> self::def('link'),
			'btn_color'    		=> self::def('color'),
			'btn_custom_color'  => self::def('custom_color'),
			'btn_size'     		=> self::def('size'),
			'btn_round'   		=> self::def('round'),
			'btn_outline' 		=> self::def('outline'),
			'btn_block'    		=> self::def('block'),
			'btn_width'    		=> self::def('width'),			
		];

		if ( isset( $arg['no_link'] ) ) {
			unset( $controls['btn_link'] );
		}

		return $controls;
	}


	public static function secondary_button( $arg = [] ) {
		self::$arg = $arg;

		$controls =  [
			'btn2_text'    		=> self::def('text'),
			'btn2_link'    		=> self::def('link'),
			'btn2_color'   		=> self::def('color'),
			'btn2_custom_color' => self::def('custom_color'),
			'btn2_size'    		=> self::def('size'),
			'btn2_round'   		=> self::def('round'),
			'btn2_outline' 		=> self::def('outline'),
			'btn2_block'   		=> self::def('block'),
			'btn2_width'   		=> self::def('width'),
		];

		if ( isset( $arg['no_link'] ) ) {
			unset( $controls['btn2_link'] );
		}

		return $controls;
	}



	public static function text( $arg = [] ) {
		self::$arg = $arg;

		return [
			'editor' => self::def('editor'),
		];

	}


	public static function info_text( $arg = [] ) {
		self::$arg = $arg;

		return [
			'info_text' => self::def('text'),
			'info_link' => self::def('link'),
		];

	}


	public static function partners( $arg = [] ) {
		self::$arg = $arg;

		return [
			'gallery' => self::def('images'),
		];

	}


	public static function pricing_plans( $arg = [] ) {
		self::$arg = $arg;

		return [
			'pricing_plan' => self::def('plans'),
		];

	}


	public static function team( $arg = [] ) {
		self::$arg = $arg;

		return [
			'team_member' => self::def('members'),
		];

	}



	public static function flash_down( $arg = [] ) {
		self::$arg = $arg;

		return [
			'flash_down'             => self::def('display'),
			'flash_down_inverse'     => self::def('inverse'),
			'flash_down_style'       => self::def('style'),
			'flash_down_padding_top' => self::def('padding_top'),
			'flash_down_target'      => self::def('target'),			
		];

	}


	public static function testimonials( $arg = [] ) {
		self::$arg = $arg;

		if ( isset( $arg['no_columns'] ) ) {
			return [
				'swiper' => self::def('swiper'),
				'testimonials' => self::def('testimonials'),
			];
		}
		else {
			return [
				'columns' => self::def('columns'),
				'testimonials' => self::def('testimonials'),
			];
		}

	}


	public static function testimonials_2( $arg = [] ) {
		self::$arg = $arg;

		if ( isset( $arg['no_columns'] ) ) {
			return [
				'swiper' => self::def('swiper'),
				'testimonials_2' => self::def('testimonials'),
			];
		}
		else {
			return [
				'columns' => self::def('columns'),
				'testimonials_2' => self::def('testimonials'),
			];
		}

	}


	public static function faq( $arg = [] ) {
		self::$arg = $arg;

		if ( isset( $arg['no_columns'] ) ) {
			return [
				'faqs' => self::def('faqs'),
			];
		}
		else {
			return [
				'columns' => self::def('columns'),
				'faqs' => self::def('faqs'),
			];
		}

	}


	public static function contact( $arg = [] ) {
		self::$arg = $arg;

		return [
			'contact' => [],
		];

	}


	public static function contact_detail( $arg = [] ) {
		self::$arg = $arg;

		return [
			'contact_detail' => [],
		];

	}


	public static function contact_detail_2( $arg = [] ) {
		self::$arg = $arg;

		return [
			'contact_detail_2' => $arg,
		];

	}


	public static function contact_form( $arg = [] ) {
		self::$arg = $arg;

		return [
			'contact_form' => [],
		];

	}


	public static function rating( $arg = [] ) {
		self::$arg = $arg;

		return [
			'stars'      => self::def('stars'),
			'stars_text' => self::def('stars_text'),
		];

	}


	public static function store_links( $arg = [] ) {
		self::$arg = $arg;

		return [
			'apple_store_link'  => self::def('apple'),
			'google_play_link'  => self::def('google'),
		];

	}



	public static function google_map( $arg = [] ) {
		self::$arg = $arg;

		return [
			'google_map' => $arg,
		];

	}



	/**
	 * Util methods
	 */
	public static function def( $index ) {
		if ( isset( self::$arg[ $index ] ) ) {
			$val = self::$arg[ $index ];
			return [ 'default' => $val ];
		}

		return [];
	}

}

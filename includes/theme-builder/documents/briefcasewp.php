<?php
namespace ElementorPro\Modules\ThemeBuilder\Documents;

use ElementorPro\Modules\ThemeBuilder\Documents\Theme_Document;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Briefcasewp extends Theme_Document {

	public static function get_properties() {
		$properties = parent::get_properties();

		$properties['condition_type'] = 'briefcasewp';
		$properties['location'] = 'briefcasewp';
		$properties['support_kit'] = true;
		$properties['support_site_editor'] = true;


		return $properties;
	}
	
	public static function get_type() {
		return 'briefcasewp';
	}
	
	protected static function get_site_editor_type() {
		return 'briefcasewp';
	}
	
	public static function get_sub_type() {
		return 'page';
	}

	public function get_name() {
		return 'briefcasewp';
	}

	public static function get_title() {
		return __( 'Briefcasewp', 'bew-extras' );
	}
	
	protected static function get_site_editor_icon() {
		return 'eicon-archive';
	}
	
	protected static function get_site_editor_thumbnail_url() {
		return BEW_EXTRAS_ASSETS_URL . 'img/bew.png';
	}

	protected static function get_site_editor_tooltip_data() {
		return [
			'title' => __( 'What is a Briefcasewp?', 'bew-extras' ),
			'content' => __( 'The Briefcasewp allows you to easily design and edit custom shop loop skins for Bew Woo Grid Widget.', 'bew-extras' ),
			'tip' => __( 'You can create multiple loop skins, and assign each to different areas of your site.', 'bew-extra' ),
			'docs' => 'https://briefcasewp.com/docs/how-to-use-briefcasewp-extras/',
			'video_url' => 'https://www.youtube.com/watch?v=ZE7BN2AFXEc&t',
		];
	}

	public static function get_preview_as_default() {
		return '';
	}

	public static function get_preview_as_options() {
		return array_merge(
			[
				'',
			],
			Single::get_preview_as_options()
		);
	}
}

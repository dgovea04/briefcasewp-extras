<?php
namespace BriefcasewpExtras\Modules\MobileMenu;

use BriefcasewpExtras\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Module_Base {

	public function get_name() {
		return 'mobile-menu';
	}

	public function get_widgets() {
		return [
			'Mobile_Menu_Icon',
			
		];
	}
}

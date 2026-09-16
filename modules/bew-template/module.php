<?php
namespace BriefcasewpExtras\Modules\BewTemplate;

use Elementor;
use Elementor\Plugin;
use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use Elementor\Core\Files\CSS\Post as Post_CSS;
use BriefcasewpExtras\Base\Module_Base;
use Elementor\Core\Base\Document;
use Elementor\TemplateLibrary\Source_Local;
use BriefcasewpExtras\Modules\BewTemplate\Classes\Shortcode;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Module_Base {

	public function get_widgets() {
		return [
			'Single_Template',
		];
	}

	public function __construct() {
		parent::__construct();

	}

	public function get_name() {
		return 'bew-template';
	}

}

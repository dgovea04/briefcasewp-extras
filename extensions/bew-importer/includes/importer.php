<?php
namespace BriefcasewpExtras;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Importer
 *
 * Importer handles import of templates
 *
 * @since 1.0.0
 */
class Importer {

	/**
	 * Plugin page.
	 *
	 * Holds slug for plugin page.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @var string
	 */
	private $plugin_page = 'bew-templates';
	
	/**
	 * Plugin action links.
	 *
	 * Adds action links to the plugin list table
	 *
	 * Fired by `plugin_action_links` filter.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $links An array of plugin action links.
	 *
	 * @return array An array of plugin action links.
	 */
	public function plugin_action_links( $links ) {
		$import_link = sprintf( '<a href="%1$s">%2$s</a>', admin_url( 'admin.php?page=' .$this->plugin_page ), __( 'Settings', 'bew-extras' ) );

		array_unshift( $links, $import_link );

		return $links;
	}


	/**
	 * Import process
	 *
	 * Controls import process via AJAX calls.
	 *
	 * Fired by `wp_ajax_bew_import_templates` action.
	 *
	 * @since 1.0
	 * @access public
	 */
	public function import_templates() {
		$level         = isset( $_POST['level'] )? sanitize_text_field( wp_unslash( $_POST['level'] ) ) : '';
		$sublevel      = isset( $_POST['sublevel'] )? sanitize_text_field( wp_unslash( $_POST['sublevel'] ) ) : '';
		$pack          = isset( $_POST['pack'] )? sanitize_text_field( wp_unslash( $_POST['pack'] ) ) : 'free';
		$sublevel_name = '';
		$log           = '';
		$array_index   = 0;
		
		$levels = array(
			'_'                     => '', //empty to avoid bonus logic
			'start'                 => esc_html__( 'Starting import', 'bew-extras' ),
			'download_files'        => esc_html__( 'Downloading files', 'bew-extras' ),
			'get_templates'          => esc_html__( 'Get templates', 'bew-extras' ),
			'install_content'       => esc_html__( 'Importing templates', 'bew-extras' ),
			'clean'                 => esc_html__( 'Cleaning...', 'bew-extras' ),
			'end'                   => esc_html__( 'Everything done!', 'bew-extras' ),
		);

		if($pack === 'free'){
			unset( $levels['start'] );			
			unset( $levels['clean'] );
		}

		//get current level key
		if ( strlen( $level ) === 0 ) {
			//get first level to process
			$level = key( $levels );
		}
		else {
			//move array pointer to current importing level
			while ( key( $levels ) !== $level ) {
				//and ask for next one
				next( $levels );
				$array_index++;
			}
			//save new current level
			$level = key( $levels );
		}

		//Execute current level function
		$method = 'import_' . $level;
		if ( method_exists( $this, $method ) ) {
			//no notices or other "echos", we put it in $log
			ob_start();

			$sublevel = $this->$method( $sublevel, $sublevel_name );

			//collect all produced output to log
			$log = ob_get_contents();
			ob_end_clean();

			//should we move to next level
			if ( $sublevel === true ) {
				$sublevel = ''; //reset
				next( $levels );
				$level = key( $levels );
			}
		}
		//no function - move to next level. Some steps are just information without action
		else {
			next( $levels );
			$array_index ++;
			$level = key( $levels );
		}

		//check if this is last element
		$is_it_end = false;
		end( $levels );
		if ( key( $levels ) === $level ) {
			$is_it_end = true;
		}

		//prepare progress info
		$progress = round( 100 * ( 1 + $array_index ) / count( $levels ) );

		$result = [
			'level'         => $level,
			'level_name'    => $levels[ $level ],
			'sublevel'      => $sublevel,
			'sublevel_name' => $sublevel_name,
			'log'           => $log,
			'progress'      => $progress,
			'is_it_end'     => $is_it_end
		];

		//send AJAX response
		echo json_encode( sizeof( $result ) ? $result : false );

		die(); //this is required to return a proper result
	}
	
	/**
	 * Import process - Download File
	 *
	 * Part of import process responsible for download file templates
	 *
	 * Fired by $this->import_templates()
	 *
	 * @since 1.0
	 * @access public
	 */
	private function import_download_files($sublevel, $sublevel_name){
		
		$file_name_m = 'templates_manually.zip';
		
		$upload = wp_upload_dir();
		$upload_dir = $upload['basedir'];
		$upload_dir = $upload_dir . '/bew-templates';
		if (! is_dir($upload_dir)) {
			mkdir( $upload_dir, 0755 );
		}
			
		$path = $upload_dir .'/' . $file_name_m;
				
		if (file_exists($path)) {
			
		} else {
			
			//Download templates from briefcasewp server			
			// Using file_get_contents method
					
			// Define Filename and Download link
			$file_name = 'templates.zip';
			$download_link = 'https://www.briefcasewp.com/download/3156';		
			
			// Credentials for basic authentication.
			$options = get_site_option('briefcase-elementor-widgets_updater_options');
			$username = isset($options['email']) ? $options['email'] : '';
			$password = base64_decode(isset($options['password']) ? $options['password'] : '');
			
			if(empty ($password)){
			$option_bew_extras = get_site_option('bew_extras_options');
			$password = base64_decode(isset($option_bew_extras['confirm_password']) ? $option_bew_extras['confirm_password'] : '');	
			}
							
			$auth = base64_encode( $username . ':' . $password );
			$context = stream_context_create([
				"http" => [
					"header" => "Authorization: Basic $auth"
				]
			]);
						
			//Download Path			
			$upload = wp_upload_dir();
			$upload_dir = $upload['basedir'];
			$upload_dir = $upload_dir . '/bew-templates';
			if (! is_dir($upload_dir)) {
				mkdir( $upload_dir, 0755 );
			}
				
			$path = $upload_dir .'/' . $file_name;		
					
			// Download File Content
			$file_data = file_get_contents($download_link, false, $context );
		 
			// Create File
			$handle = fopen($file_name, 'w');
			fclose($handle);
		 
			// Save Content to file
			$downloaded = file_put_contents( $path , $file_data);
			
			if( empty($downloaded)){
				
				//Using CURL  method
				
				//FOLDER PATH
				$fp = fopen($path, 'w');
							
				//SETTING UP CURL REQUEST
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $download_link);
				curl_setopt($ch, CURLOPT_FAILONERROR, true);
				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
				curl_setopt($ch, CURLOPT_AUTOREFERER, true);
				curl_setopt($ch, CURLOPT_BINARYTRANSFER,true);
				curl_setopt($ch, CURLOPT_TIMEOUT, 10);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); 
				curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
				curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
				curl_setopt($ch, CURLOPT_FILE, $fp);
				$output = curl_exec($ch);
				$info = curl_getinfo($ch);
				
				//CONNECTION CLOSE
				curl_close($ch);
				fclose($fp);
				
			}
		}	

		// Manage the zip
		$zip = new \ZipArchive();

		$temp_path = $upload_dir .'/' ;

		$res = $zip->open( $path );
		if ($res === TRUE) {
		$zip->extractTo( $temp_path );
		$zip->close();
		}
		
		// Clear zip from local storage:
		unlink($path);
		
		$sublevel = true;
		
		return $sublevel;
		
	}
	
	private function import_get_templates($sublevel, &$sublevel_name){ 
	
		//Imports templates from local
		$upload = wp_upload_dir();
		$upload_dir = $upload['basedir'];
		$upload_dir = $upload_dir . '/bew-templates';		
		$dir = $upload_dir .'/';
		
		$templates = array();
		
		//Get all templates to import
		if ( is_dir( $dir ) ) {
			//The GLOB_BRACE flag is not available on some non GNU systems, like Solaris. So we use merge:-)
			foreach ( (array) glob( $dir . '/*.json' ) as $file ) {
				$templates[] = basename( $file );
			}
		}
		
		// Save templates array
		update_option( 'bew_get_shop_templates', $templates);
		
		$sublevel = true;
		
		return $sublevel;
	}

	/**
	 * Import process - import content
	 *
	 * Part of import process responsible for importing posts
	 *
	 * Fired by $this->import_templates()
	 *
	 * @since 1.0
	 * @access public
	 */
	private function import_install_content($sublevel, &$sublevel_name){
		
		//Imports templates from local
		$upload = wp_upload_dir();
		$upload_dir = $upload['basedir'];
		$upload_dir = $upload_dir . '/bew-templates';		
		$dir = $upload_dir .'/';
		
		//Get templates array
		$bew_get_shop_templates = get_option( 'bew_get_shop_templates');
		
		if ($bew_get_shop_templates) {
			$templates = $bew_get_shop_templates;
		}
		
		if ( strlen( $sublevel ) === 0 ) {//we will import first template on list but in second call of this function
			$sublevel      = key( $templates );
			$sublevel_name = $templates[ $sublevel ];
		}
		else {
			//save last template
			end( $templates );
			$last_template = key( $templates );
			reset( $templates );

			$sublevel = (int) $sublevel;//convert from string type

			// template to import now
			$file = $dir . $templates[ $sublevel ];

			/** @var \Elementor\TemplateLibrary\Source_Local $source */
			$source = \Elementor\Plugin::instance()->templates_manager->get_source( 'local' );
			
			// Check if the template is imported before.
			
			$template_name = $templates[ $sublevel ];
			
			$bewtemplates = $source->get_items( array('type' => 'briefcasewp'));
			
			$dataTemplates = [];
		
			foreach ( $bewtemplates as $bewtemplate ) {
			
			$bewtemplate_name  = sanitize_title($bewtemplate['title']). '.json';
			$bewtemplate_id  	 = $bewtemplate['template_id'];
			
			$dataTemplates[] = array("id"=>$bewtemplate_id ,"name"=>$bewtemplate_name);			
				
			}
			
			sort($dataTemplates);
			
			$check_key = array_search($template_name,array_column($dataTemplates,'name'));
			
			if ( false === $check_key ) {
				//this import templates
				$source->import_template( $templates[ $sublevel ], $file );			
				
				// Get the Id
				$bewtemplates = $source->get_items( array('type' => 'briefcasewp'));
				
				array_multisort( array_column($bewtemplates, "template_id"), SORT_ASC, $bewtemplates );
				$last = end($bewtemplates);
				
				// Get the Bew_type
				$list = BEW_EXTRAS_PATH . 'extensions/bew-importer/data/list.json';			
				$templates_data = json_decode( file_get_contents( $list, FILE_USE_INCLUDE_PATH ), true );
				$templates_data = $templates_data['data'];
							
				$template_slug = sanitize_title($last['title']);
				
				sort($templates_data);
			
				$key = array_search($template_slug,array_column($templates_data,'id'));
				
				$template_data = $templates_data[$key];
				$bew_type = $template_data['bew_type'];				
				
				// Update template with the data
				$my_bew_type = esc_html($bew_type);
				$my_shop_disable = esc_html('off');
				$my_cat_disable = esc_html('off');	
				$my_id = esc_html($last['template_id']);			
				
				if ( ! empty( $my_bew_type && $my_id ) ) {
				update_post_meta( $my_id, 'briefcase_template_layout', $my_bew_type);				
				update_post_meta( $my_id, 'briefcase_template_layout_shop', $my_shop_disable);
				update_post_meta( $my_id, 'briefcase_template_layout_cat', $my_cat_disable);
				}		
			}
			
			//if this was last plugin on list then we end this process
			if ( $last_template === $sublevel ) {
				$sublevel = true;
			}
			else{
				//move to next template to import
				$sublevel++;
				$sublevel_name = $templates[ $sublevel ];
			}
		}

		return $sublevel;
	}

	/**
	 * Importer constructor.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {
		add_filter( 'plugin_action_links_' . BEW_EXTRAS_PLUGIN_BASE, [ $this, 'plugin_action_links' ] );
		add_action( 'wp_ajax_bew_import_templates', [ $this, 'import_templates' ] );		
	}
}

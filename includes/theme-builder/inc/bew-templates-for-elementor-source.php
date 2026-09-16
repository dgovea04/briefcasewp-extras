<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Bew_Elementor_Templates_Source extends Elementor\TemplateLibrary\Source_Base {

	/**
	 * Template prefix
	 */
	protected $template_prefix = 'bew_';

	/**
	 * Return templates prefix
	 */
	public function get_prefix() {
		return $this->template_prefix;
	}

	public function get_id() {
		return 'bew-templates';
	}

	public function get_title() {
		return __( 'Bew Templates', 'bew-extras' );
	}

	public function register_data() {}

	public function get_items( $args = array() ) {

		$url            = BEW_EXTRAS_URL . 'includes/theme-builder/json/templates.json';
		$response       = wp_remote_get( $url, array( 'timeout' => 60 ) );
		$body           = wp_remote_retrieve_body( $response );
		$body           = json_decode( $body, true );
		$templates_data = ! empty( $body['data'] ) ? $body['data'] : false;
		$templates      = array();

		if ( ! empty( $templates_data ) ) {
			foreach ( $templates_data as $template_data ) {
				$templates[] = $this->get_item( $template_data );
			}
		}

		if ( ! empty( $args ) ) {
			$templates = wp_list_filter( $templates, $args );
		}
		
		return $templates;
	}

	public function get_item( $template_data ) {
		return array(
			'template_id'     => $this->get_prefix() . $template_data['template_id'],
			'source'          => 'remote',
			'type'            => $template_data['type'],
			'subtype'         => $template_data['subtype'],
			'title'           => $template_data['title'],
			'thumbnail'       => BEW_EXTRAS_URL  . 'includes/theme-builder/templates/img/' . $template_data['template_id'] .'.png',
			'date'            => date( get_option( 'date_format' ), $template_data['tmpl_created'] ),
			'author'          => $template_data['author'],
			'tags'            => $template_data['tags'],
			'isPro'           => ( 1 == $template_data['isPro'] ),
			'accessLevel'     => ( 1 == $template_data['access_level'] ),
			'popularityIndex' => (int) $template_data['popularityIndex'],
			'trendIndex'      => (int) $template_data['trendIndex'],
			'hasPageSettings' => ( 1 == $template_data['hasPageSettings'] ),
			'url'             => $template_data['url'],
			'favorite'        => ( 1 == $template_data['favorite'] ),
			'download'        => $template_data['download'],
		);
	}

	public function save_item( $template_data ) {
		return false;
	}

	public function update_item( $new_data ) {
		return false;
	}

	public function delete_template( $template_id ) {
		return false;
	}

	public function export_template( $template_id ) {
		return false;
	}

	public function get_data( array $args, $context = 'display' ) {
		
		//Get templates from briefcasewp server
		
		// Define the template link							
		$items = $this->get_items();
		
		foreach ( $items as $item ) { 
			$item_c[ $item[ 'template_id'  ]  ] = [ "download"=> $item[ 'download'  ] ];
		}
		
		$download = $item_c[ $args['template_id'] ]['download'];		
		$item = 'https://www.briefcasewp.com/download/'  . $download;		
		
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
		
		// Get File Content 
		
		//Using file_get_contents method		
		$data = json_decode( file_get_contents( $item, FILE_USE_INCLUDE_PATH, $context ), true );
		
		//Using CURL  method
		if( empty($data)){
			
		//SETTING UP CURL REQUEST
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $item);
			curl_setopt($ch, CURLOPT_FAILONERROR, true);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_AUTOREFERER, true);
			curl_setopt($ch, CURLOPT_BINARYTRANSFER,true);
			curl_setopt($ch, CURLOPT_TIMEOUT, 10);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			$output = curl_exec($ch);
			$info = curl_getinfo($ch);
			curl_close($ch);
			
			$data = json_decode($output, true);	
		}
		
		if ( is_wp_error( $data ) ) {
			return $data;
		}

		// TODO: since 1.5.0 to content container named `content` instead of `data`
		if ( ! empty( $data['data'] ) ) {
			$data['content'] = $data['data'];
			unset( $data['data'] );
		}

		$data['content'] = $this->replace_elements_ids( $data['content'] );
		$data['content'] = $this->process_export_import_content( $data['content'], 'on_import' );

		if ( ! empty( $args['page_settings'] ) && ! empty( $data['page_settings'] ) ) {
			$page = new Page( [
				'settings' => $data['page_settings'],
			] );

			$page_settings_data = $this->process_element_export_import_content( $page, 'on_import' );
			$data['page_settings'] = $page_settings_data['settings'];
		}

		return $data;
	}
}

<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Bew_Builder_Templates_Source_Api extends Bew_Builder_Templates_Source_Base {

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
	
	private $_object_cache = array();

	/**
	 * Return source slug.
	 *
	 * @since 1.0.0
	 * @access public
	*/
	public function get_slug() {
		return 'bew-api';
	}

	/**
	 * Return source version.
	 *
	 * @since 1.0.0
	 * @access public
	*/
	public function get_version() {

		$key     = $this->get_slug() . '_version';
		$version = get_transient( $key );
		$version = false;

		if ( ! $version ) {
			//$version = bew_builder()->api->get_info( 'api_version' );
			$version = '1.0.0';
			set_transient( $key, $version, DAY_IN_SECONDS );
		}

		return $version;
	}

	/**
	 * Return source item list
	 *
	 * @since 1.0.0
	 * @access public
	*/
	public function get_items( $tab = null ) {

		if ( ! $tab ) {
			return array();
		}

		$cached = $this->get_templates_cache();

		if ( ! empty( $cached[ $tab ] ) ) {
			return array_values( $cached[ $tab ] );
		}

		$templates = $this->remote_get_templates( $tab );

		if ( ! $templates ) {
			return array();
		}

		if ( empty( $cached ) ) {
			$cached = array();
		}

		$cached[ $tab ] = $templates;

		$this->set_templates_cache( $cached );

		return $templates;

	}

	/**
	 * Prepare items tab
	 * @return [type] [description]
	 */
	public function prepare_items_tab( $tab = '' ) {

		if ( ! empty( $this->_object_cache[ $tab ] ) ) {
			return $this->_object_cache[ $tab ];
		}

		$result = array(
			'templates'  => array(),
			'categories' => array(),
			'keywords'   => array(),
		);

		$templates_cache  = $this->get_templates_cache();
		$categories_cache = $this->get_categories_cache();
		$keywords_cache   = $this->get_keywords_cache();

		if ( empty( $templates_cache ) ) {
			$templates_cache = array();
		}

		if ( empty( $categories_cache ) ) {
			$categories_cache = array();
		}

		if ( empty( $keywords_cache ) ) {
			$keywords_cache = array();
		}

		$result['templates'] = $this->remote_get_templates( $tab );
		$result['templates'] = $this->remote_get_categories( $tab );
		$result['templates'] = $this->remote_get_keywords( $tab );

		$templates_cache[ $tab ]  = $result['templates'];
		$categories_cache[ $tab ] = $result['categories'];
		$keywords_cache[ $tab ]   = $result['keywords'];

		$this->set_templates_cache( $templates_cache );
		$this->set_categories_cache( $categories_cache );
		$this->set_keywords_cache( $keywords_cache );

		$this->_object_cache[ $tab ] = $result;

		return $result;
	}

	/**
	 * Get templates from remote rserver
	 *
	 * @param  [type] $tab [description]
	 * @return [type]      [description]
	 */
	public function remote_get_templates( $tab ) {
		
		$url         = BEW_EXTRAS_URL .  'includes/bew-builder/assets/templates/items/templates-' . $tab . '.json' ;

		if ( ! $url ) {
			return false;
		}

		$response       = wp_remote_get( $url, array( 'timeout' => 60 ) );
		$body           = wp_remote_retrieve_body( $response );
		
		if ( ! $body ) {
			return false;
		}
		
		$body           = json_decode( $body, true );
		$templates_data = ! empty( $body['templates'] ) ? $body['templates'] : false;
		$templates      = array();

		if ( ! empty( $templates_data ) ) {
			foreach ( $templates_data as $template_data ) {
				$templates[] = $this->remote_get_item( $template_data );
			}
		}

		if ( ! empty( $args ) ) {
			$templates = wp_list_filter( $templates, $args );
		}
	
		return $templates;

	}
	
	public function remote_get_item( $template_data ) {
		return array(
			'template_id'     => $this->get_prefix() . $template_data['template_id'],
			'source'          => 'bew-api',
			'type'            => $template_data['type'],
			'subtype'         => $template_data['subtype'],
			'title'           => $template_data['title'],
			'categories'      => $template_data['categories'],
			'keywords'        => $template_data['keywords'],
			'thumbnail'       => BEW_EXTRAS_URL  . 'includes/bew-builder/assets/templates/img/thumbnail/' . $template_data['template_id'] .'.png',
			'preview'         => 'https://www.briefcasewp.com/wp-content/uploads/2022/01/' . $template_data['template_id'] .'.png',
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

	/**
	 * Get categories from remote server
	 *
	 * @param  [type] $tab [description]
	 * @return [type]      [description]
	 */
	public function remote_get_categories( $tab ) {
		
		$url         = BEW_EXTRAS_URL . 'includes/bew-builder/assets/templates/items/categories-' . $tab . '.json' ;
		
		if ( ! $url ) {
			return false;
		}

		$response    = wp_remote_get( $url, array( 'timeout' => 60 ) );
		$body        = wp_remote_retrieve_body( $response );
		
		if ( ! $body ) {
			return false;
		}
		
		$body   = json_decode( $body, true );
		$terms  = ! empty( $body['terms'] ) ? $body['terms'] : false;
		
		return $terms;
	}

	/**
	 * Get keywords from remote server
	 *
	 * @param  [type] $tab [description]
	 * @return [type]      [description]
	 */
	public function remote_get_keywords( $tab ) {

		$url     = BEW_EXTRAS_URL . 'includes/bew-builder/assets/templates/items/keywords-' . $tab . '.json' ;

		if ( ! $url ) {
			return false;
		}

		$response    = wp_remote_get( $url, array( 'timeout' => 60 ) );
		$body = wp_remote_retrieve_body( $response );

		if ( ! $body ) {
			return false;
		}

		$body = json_decode( $body, true );

		//if ( ! isset( $body['success'] ) || true !== $body['success'] ) {
		//	return false;
		//}

		$terms  = ! empty( $body['terms'] ) ? $body['terms'] : false;

		return $terms;

	}

	/**
	 * Return source item list
	 *
	 * @since 1.0.0
	 * @access public
	*/
	public function get_categories( $tab = null ) {

		if ( ! $tab ) {
			return array();
		}

		$cached = $this->get_categories_cache();

		if ( ! empty( $cached[ $tab ] ) ) {
			return $this->prepare_categories( $cached[ $tab ] );
		}

		$categories = $this->remote_get_categories( $tab );

		if ( ! $categories ) {
			return array();
		}

		if ( empty( $cached ) ) {
			$cached = array();
		}

		$cached[ $tab ] = $categories;

		$this->set_categories_cache( $cached );

		return $this->prepare_categories( $categories );
	}

	/**
	 * Prepare categories for response
	 *
	 * @return [type] [description]
	 */
	public function prepare_categories( $categories ) {

		$result = array();

		foreach ( $categories as $slug => $title ) {
			$result[] = array(
				'slug'  => $slug,
				'title' => $title,
			);
		}

		return $result;
	}

	/**
	 * Return source item list
	 *
	 * @since 1.0.0
	 * @access public
	*/
	public function get_keywords( $tab = null ) {

		if ( ! $tab ) {
			return array();
		}

		$cached = $this->get_keywords_cache();

		if ( ! empty( $cached[ $tab ] ) ) {
			return $cached[ $tab ];
		}

		$keywords = $this->remote_get_keywords( $tab );

		if ( ! $keywords ) {
			return array();
		}

		if ( empty( $cached ) ) {
			$cached = array();
		}

		$cached[ $tab ] = $keywords;

		$this->set_keywords_cache( $cached );

		return $keywords;

	}

	/**
	 * Return single item
	 *
	 * @since 1.0.0
	 * @access public
	*/
	public function get_item( $template_id, $tab = false ) {

		$id  = str_replace( $this->id_prefix(), '', $template_id );

		if ( ! $tab ) {
			$tab = isset( $_REQUEST['tab'] ) ? esc_attr( $_REQUEST['tab'] ) : false;
		}

		if ( ! $tab ) {
			return array();
		}

		//Get templates from briefcasewp server
		
		// Define the template link							
		$items = $this->remote_get_templates( $tab );		
		
		foreach ( $items as $item ) { 
			$item_c[ $item[ 'template_id'  ]  ] = [ "download"=> $item[ 'download'  ] ];
		}
		
		$download = $item_c[ $template_id ]['download'];	
	
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

		$content       = isset( $data['content'] ) ? $data['content'] : '';
		$type          = isset( $data['type'] ) ? $data['type'] : '';
		$page_settings = isset( $data['page_settings'] ) ? $data['page_settings'] : array();

		if ( ! empty( $content ) ) {
			$content = $this->replace_elements_ids( $content );
			$content = $this->process_export_import_content( $content, 'on_import' );
		}

		return array(
			'page_settings' => $page_settings,
			'type'          => $type,
			'content'       => $content,
		);

	}

	/**
	 * Return transien lifetime
	 *
	 * @since 1.0.0
	 * @access public
	*/
	public function transient_lifetime() {
		return WEEK_IN_SECONDS;
	}
}

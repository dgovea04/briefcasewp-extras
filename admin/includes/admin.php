<?php
namespace BriefcasewpExtras;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Admin 
 */
class Admin {

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
	 * Tabs.
	 *
	 * Holds the settings page tabs, sections and fields.
	 *
	 * @access private
	 *
	 * @var array
	 */
	private $tabs;


	/**
	 * Get settings page title.
	 *
	 * Retrieve the title for the settings page.
	 *
	 * @since 1.0.0
	 * @access protected
	 *
	 * @return string Settings page title.
	 */
	protected function get_page_title() {
		/* translators: %s: Plugin name */
		return sprintf( esc_html__( 'Welcome to %s', 'bew-extras' ), esc_html( BEW_EXTRAS_NAME ) );
	}


	/**
	 * Get tabs.
	 *
	 * Retrieve the settings page tabs, sections and fields.
	 *
	 * @since 1.5.0
	 * @access public
	 *
	 * @return array Settings page tabs, sections and fields.
	 */
	public final function get_tabs() {
		$gtabs = $this->bew_show_tabs();
		return $gtabs;
	}

	/**
	 * Enqueue admin scripts.
	 *
	 * Registers all the admin scripts and enqueues them.
	 *
	 * Fired by `admin_enqueue_scripts` action.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function enqueue_scripts() {
		wp_register_script(
			'bew-admin',
			BEW_EXTRAS_IMPORTER_ASSETS_URL . 'js/admin.js',
			[
				'jquery',
			],
			BEW_EXTRAS_VERSION,
			true
		);
	}

	/**
	 * Enqueue admin styles.
	 *
	 * Registers all the admin styles and enqueues them.
	 *
	 * Fired by `admin_enqueue_scripts` action.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function enqueue_styles() {
		wp_register_style(
			'bew-admin',
			BEW_EXTRAS_IMPORTER_ASSETS_URL . 'css/admin.css',
			[],
			BEW_EXTRAS_VERSION
		);
		
		wp_enqueue_style( 'bew-admin' );
		
		wp_enqueue_style( 'wpb-fa', 'https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );
		
	}

	/**
	 * Admin footer text.
	 *
	 * Modifies the "Thank you" text displayed in the admin footer.
	 *
	 * Fired by `admin_footer_text` filter.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $footer_text The content that will be printed.
	 *
	 * @return string The content that will be printed.
	 */
	public function admin_footer_text( $footer_text ) {
		$current_screen = get_current_screen();
		$is_plugin_screen = ( $current_screen && false !== strpos( $current_screen->id, $this->plugin_page ) );

		if ( $is_plugin_screen ) {
			$footer_text = esc_html__( 'Thanks for using Bew Importer Extension!', 'bew-extras' ).
			               ' <a href="https://briefcasewp.com/">'.esc_html__( 'Briefcasewp.com', 'bew-extras' ).'</a>';
		}

		return $footer_text;
	}

	/**
	 * Elementor dashboard widget links
	 *
	 * Adds links in Elementor dashboard widget.
	 *
	 * Fired by `elementor/admin/dashboard_overview_widget/footer_actions` filter.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $additions_actions Elementor dashboard widget footer actions.
	 *
	 * @return array Elementor dashboard widget footer actions.
	 */
	public function dashboard_widget_links( $additions_actions ) {
		$additions_actions['bew-extras'] =
			 [
				'title' => __( 'Import templates', 'bew-extras' ),
				'link' => admin_url( 'admin.php?page=' .$this->plugin_page ),
			];

		return $additions_actions;
	}
	
	/**
	 * Plugin menu link.
	 *
	 * Adds link to admin menu under Wordpress menu
	 *
	 * Fired by `admin_menu` action.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_pages() {
		/* translators: %s: Theme name */
		$temp = 'BriefcaseWp';
		
		add_menu_page(
            $temp,
			$temp,
            'manage_options',
            $this->plugin_page,
			[ $this, 'display_import_page' ],
            BEW_EXTRAS_IMPORTER_ASSETS_URL . 'images/icon.png'
        );
		
		add_submenu_page(
            $this->plugin_page,
			esc_html__( 'Dashboard', 'bew-extras' ),
			esc_html__( 'Dashboard', 'bew-extras' ),
            'manage_options',
			$this->plugin_page, 
			[ $this, 'display_import_page' ],			
        );

	}

	/**
	 * Display import page.
	 *
	 * Output the content for the import page.
	 *
	 * @since 1.0
	 * @access public
	 */
	public function display_import_page() {
		if( !bew_is_elementor_installed() ){
			$this->fail_elementor();
			return;
		}
		if( !bew_is_plugin_active() ){
			$this->fail_plugin_active();
			return;
		}

		wp_enqueue_script( 'bew-admin' );
		wp_enqueue_style( 'bew-admin' );
		$tabs = $this->get_tabs();
		
		?>
		<div class="wrap">
		<div class="notices">
		<h2></h2>
		</div>
		<div class="bew-extras-plugin-page">
		
		<?php		
		?>
			<div id="bew-extras-header">
				<a href="https://briefcasewp.com" target="_blank" class="briefcasewp-logo"><img
						src="<?php echo esc_url( BEW_EXTRAS_IMPORTER_ASSETS_URL . 'images/logo-briefcasewp.png' ); ?>"></a>
				<div class="icons">
					<a href="https://www.briefcasewp.com" target="_blank">Briefcasewp Extras by Briefcasewp</a>
					<a href="https://www.briefcasewp.com" target="_blank">v<?php echo BEW_EXTRAS_VERSION; ?></a>
					<a href="https://facebook.com/briefcasewp" target="_blank"><i class="fa fa-facebook"></i></a>
					<a href="https://twitter.com/briefcasewp" target="_blank"><i class="fa fa-twitter"></i></a>					
					<br/><br/>
					<em>Create beautiful Woocommerce Websites!</em><br/>
					<em><a href="https://www.briefcasewp.com/bew-blocks-manager/" target="_blank">with the New Bew Block Manager</a></em>
				</div>				
			</div>
			
			<nav class="nav-tab-wrapper">
				<?php
				foreach ( $tabs as $tab_id => $tab ) {

					$active_class = '';

					if ( 'import' === $tab_id ) {
						$active_class = ' nav-tab-active';
					}

					echo '<a id="bew-settings-tab-'.esc_attr( $tab_id ).'" class="nav-tab'.esc_attr( $active_class ).'" href="#tab-'.esc_attr( $tab_id ).'">'.esc_html( $tab ).'</a>';
				}
				?>
			</nav>

			<?php

			foreach ( $tabs as $tab_id => $tab ) {

				$active_class = '';

				if ( 'import' === $tab_id ) {
					$active_class = ' active-section';
				}

				echo '<div id="tab-'.esc_attr( $tab_id ).'" class="bew-settings-section'.esc_attr($active_class).'">';

				$section_method = 'tab_section_'.$tab_id;

				if( method_exists( $this, $section_method ) ){
					$this->$section_method();
				}

				echo '</div>';
			}

				?>
		</div>
		</div><!-- /.wrap -->
		<?php
	}

	/**
	 * Importer main tab.
	 *
	 * Output Importer main tab.
	 *
	 * Called in $this->display_import_page()
	 *
	 * @since 1.0
	 * @access public
	 */
	public function tab_section_import() {		
		
		// Get Credentials for update the options.
		$options = get_site_option('briefcase-elementor-widgets_updater_options');
		$email= isset($options['email']) ? $options['email'] : '';
		
		$check_password = !empty(isset($options['password'])) ? "yes" : "no";

		//echo "options: " . var_dump($options);
		//echo "  email: " . $email;
		//echo "  check_password: " . $check_password;
		
		//Check if is password is set.
		$option_bew_extras = get_site_option('bew_extras_options');
		$check_password_confirm = !empty(isset($option_bew_extras['confirm_password'])) ? "yes" : "no";
				
		$passedValue = array( 'check_password' => $check_password , 'check_password_confirm' => $check_password_confirm  );
		wp_localize_script( 'bew-admin', 'passed_object', $passedValue );
		
		?>
		<h2><?php esc_html_e( 'Import templates', 'bew-extras'); ?></h2>
		
		<p><img src="<?php echo BEW_EXTRAS_IMPORTER_ASSETS_URL .'images/import/templates.png' ?>" alt="<?php esc_html_e( 'Image of templates to import.', 'bew-extras'); ?>" /></p>
		<p><?php echo nl2br( esc_html__( 'Please use below button to import BEW Blocks and BEW Templates to your WordPress installation.', 'bew-extras') ); ?></p>
		<div id="popup_confirm" class="overlay">
			<div class="popup">				
				<div class="modal-header">	
				<h4 class="modal-title">Confirm your Account</h4>
				</div>
				<a class="close" id="close_popup" href="#">&times;</a>
				<div class="content">					
					<div id="bew-extras-body" class="modal-body">
					  <span>You are connected to Briefcase Elementor Widgets (login: <?php echo $email;?>)</span>					  
					  <input type="password"  style="width:220px;" placeholder="Enter your password" name="password" value="" required>
					  <button id="bew-extras-connect" type="submit" class="button button-primary bew-extras-connect"><?php _e('Connect', 'bew-extras');?></button>					  
					</div>					
				</div>
			</div>
		</div>
		<div class="import-section">
						
			<div class="import-templates-content">
			<button class="button button-primary button-hero" id="start-import"><?php esc_html_e( 'Import Templates', 'bew-extras'); ?></button>
			<div class="status">
				<strong id="demo_data_import_status"><?php esc_html_e( 'The Importer is ready to start.', 'bew-extras' ); ?></strong>
				<div class="import_progress_bar"><div class="import_progress"></div></div>
			</div>
			</div>

		</div>
		<hr />
		<p><?php echo esc_html__( 'We will be adding more templates. So please stay tuned.', 'bew-extras' ); ?>
		<p><a href="https://www.briefcasewp.com/briefcasewp-extras/"><?php echo esc_html__( 'Visit plugins page', 'bew-extras' ); ?></a></p>
		<?php
			
	}


	/**
	 * Importer instructions tab.
	 *
	 * Output Importer instructions tab.
	 *
	 * Called in $this->display_import_page()
	 *
	 * @since 1.0
	 * @access public
	 */
	public function tab_section_instructions() {
		?>
		<h2><?php esc_html_e( 'Plugin instructions', 'bew-extras'); ?></h2>
		<h3><?php esc_html_e( 'Briefcasewp Templates', 'bew-extras'); ?></h3>
		<p><?php echo nl2br( esc_html__( 'Before Import the templates make sure, you have Elementor Pro installed, Woocommerce installed with at least one product.', 'bew-extras') ); ?></p>
		<p><?php echo nl2br( esc_html__( 'After you are done with importing on "Import Templates" tab, you will be able to add/use these templates in Elementor.', 'bew-extras') ); ?></p>		
		<p><?php echo nl2br( esc_html__( 'To use the templates in Elementor, open the Bew Template Popup, you will find  the Pages and Blocks. Please see below:', 'bew-extras') ); ?></p>
		<p><?php echo nl2br( esc_html__( 'Note: If you have issues importing templates, we recommend use Elementor import template option. check ', 'bew-extras') ); ?> <a href="https://briefcasewp.com/docs/how-to-import-bew-blocks-manually/"><?php echo esc_html__( 'Tutorial Here:', 'bew-extras' ); ?></a></p>		
		<p><a href="https://www.briefcasewp.com/download/5064/"><?php echo esc_html__( 'Download Template Zip Here:', 'bew-extras' ); ?></a></p>
		<p class="bew-templates-button" ><img src="<?php echo esc_url( BEW_EXTRAS_IMPORTER_ASSETS_URL . 'images/new-bew-template-button.png' ); ?>" alt="<?php esc_html_e( 'Import templates in Elementor', 'bew-extras'); ?>" /></p>
		<p class="bew-templates-popup" ><img src="<?php echo esc_url( BEW_EXTRAS_IMPORTER_ASSETS_URL . 'images/new-bew-template-popup.png' ); ?>" alt="<?php esc_html_e( 'Import templates in Elementor', 'bew-extras'); ?>" /></p>
		<hr />
		<p><?php echo esc_html__( 'We will be adding more templates. So please stay tuned.', 'bew-extras' ); ?>
		<p><a href="https://www.briefcasewp.com/briefcasewp-extras/"><?php echo esc_html__( 'Visit plugins page', 'bew-extras' ); ?></a></p>
		<?php
	}


	/**
	 * Importer "more from Briefcasewp" tab.
	 *
	 * Output info about different stuff from Briefcasewp
	 *
	 * Called in $this->display_import_page()
	 *
	 * @since 1.0
	 * @access public
	 */
	public function tab_section_briefcasewp() {
		?>
		<h2><?php esc_html_e( 'More from BriefcaseWp 2.0', 'bew-extras'); ?></h2>
		<h3><?php esc_html_e( 'Briefcase Elementor Widgets 2.0', 'bew-extras'); ?></h3>		
		<div class="more-briefcasewp"><img src="<?php echo BEW_EXTRAS_IMPORTER_ASSETS_URL .'images/more-briefcasewp.png' ?>" alt="<?php esc_html_e( 'Briefcasewp Designs.', 'bew-extras'); ?>" /></div>
		<p><?php echo nl2br( esc_html__( 'Create a unique and modern E-Commerce website for your business with Briefcase Elementor Widgets 2.0. 
		Make your own  homepage, shop page and single product just as you imagined it.', 'bew-extras') ); ?></p>
		<div class="cta-button"><a class="button button-primary button-hero" href="https://www.briefcasewp.com/briefcase-elementor-widgets/"><?php esc_html_e( 'Check the New Features', 'bew-extras'); ?></a></div>
		<?php
	}
	
	/**
	 * Importer "License" tab.
	 *
	 * Called in $this->display_import_page()
	 *
	 * @since 1.0
	 * @access public
	 */
	public function tab_section_license() {
		
		// Get Credentials for update the options.
		$options = get_site_option('briefcase-elementor-widgets_updater_options');
		$email= isset($options['email']) ? $options['email'] : '';		
		$check_password = !empty(isset($options['password'])) ? "yes" : "no";
		
		//echo "options: " . var_dump($options);
		//echo "  email: " . $email;
		//echo "  check_password: " . $check_password;
		
		if ($check_password == "no"){
		//Check if is password is set.
		$option_bew_extras = get_site_option('bew_extras_options');
		$check_password_confirm = !empty(isset($option_bew_extras['confirm_password'])) ? "yes" : "no";
		}
		
		//echo "option_bew_extras " . $option_bew_extras;
		//echo "check_password_confirm " . $check_password_confirm;
		
		?>
		<h2><?php esc_html_e( 'License', 'bew-extras'); ?></h2>
		<div id="box_confirm">
			<?php 
			if ($email != '' && $check_password == "yes" ){ ?>
				<div class="confirm_account">				
					<div class="header">	
					<h4 class="title">Confirm your Account</h4>
					</div>				
					<div class="content">					
						<div id="bew-extras-body-settings" class="modal-body">
						  <span>You are connected to Briefcase Elementor Widgets (login: <?php echo $email;?>)</span>					  
						  <?php 
						  if (($email != '' && $check_password == "yes") || $check_password_confirm == "yes"  ){
							  
						  }else{ 
						  ?>
						  <input type="password"  style="width:220px;" placeholder="Enter your password" name="password" value="" required>
						  <button id="bew-extras-connect-settings" type="submit" class="button button-primary bew-extras-connect"><?php _e('Connect', 'bew-extras');?></button>
						  <?php 
						  }
						  ?>
						  <?php 
						  if (($email != '' && $check_password == "yes") || $check_password_confirm == "yes"  ){ 
						  ?>
						  <span id="right">Your Account is Confirmed</span>
						  <?php 
						  }
						  ?>
						</div>					
					</div>
				</div>
			<?php 
			}
			?>
		</div>				
		<?php
	}
	
	/**
	 * Importer "settings" tab.
	 *
	 * Called in $this->display_import_page()
	 *
	 * @since 1.0
	 * @access public
	 */
	public function tab_section_settings() {		
		?>
		<h2><?php esc_html_e( 'Settings', 'bew-extras'); ?></h2>
		
		<div id="box_woogrid_settings">
			<form method="post" action="options.php">
				<?php settings_fields( 'bew_extras_options_group' ); ?>
				<h4 class="title">BriefcaseWP Extras</h4>
				    <table class="form-table">
						<tr valign="top">
						<th scope="row">Disable Briefcasewp Extras Styles</th>
						<td>
							<label>
							<input type="checkbox" name="briefcasewp_extras_styles" value="1" class="bwp-checkbox" id="briefcasewp_extras_styles" <?php checked(get_option('briefcasewp_extras_styles'), 1); ?>>
							Checking this box will disable Briefcasewp Extras Styles.
							</label>
						</td>						
						</tr>        
					</table>
					<table class="form-table">
						<tr valign="top">
						<th scope="row">Disabled Briefcasewp Extras Scripts</th>
						<td>
							<label>
							<input type="checkbox" name="briefcasewp_extras_scripts" value="1" class="bwp-checkbox" id="briefcasewp_extras_scripts" <?php checked(get_option('briefcasewp_extras_scripts'), 1); ?>>
							Checking this box will disable Briefcasewp Extras Scripts.
							</label>
						</td>						
						</tr>        
					</table>
				<h4 class="title">Woo Grid Widget</h4>
				    <table class="form-table">
						<tr valign="top">
						<th scope="row">Disable Cache</th>
						<td>
							<label>
							<input type="checkbox" name="woo_grid_cache" value="1" class="bwp-checkbox" id="woo_grid_cache" <?php checked(get_option('woo_grid_cache'), 1); ?>>
							Checking this box will disable cache on custom woo grid queries, Use this option only for development purpose.
							</label>
						</td>						
						</tr>        
					</table>
					<table class="form-table">
						<tr valign="top">
						<th scope="row">Jet Smart Filter Compatibility</th>
						<td>
							<label>
							<input type="number" name="jsf_id_template" value="<?php echo get_option('jsf_id_template'); ?>" class="bwp-number" id="jsf_id_template" />
							Enter Template ID for your Shop page.
							</label>
						</td>					
						</tr>        
					</table>				
				<h4 class="title">Woo Bew Mini Cart</h4>
				    <table class="form-table">
						<tr valign="top">
						<th scope="row">Disable Woo Bew Mini Cart Styles</th>
						<td>
							<label>
							<input type="checkbox" name="woo_bew_cart" value="1" class="bwp-checkbox" id="woo_bew_cart" <?php checked(get_option('woo_bew_cart'), 1); ?>>
							Checking this box will disable Woo Bew Mini Cart styles. So you can use your theme cart with default theme styles.
							
							</label>
						</td>						
						</tr>        
					</table>
				<h4 class="title">Fullpage Widget</h4>
				    <table class="form-table">
						<tr valign="top">
						<th scope="row">Enable Parallax Extension</th>
						<td>
							<label>
							<input type="checkbox" name="fullpage_parallax" value="1" class="bwp-checkbox" id="fullpage_parallax" <?php checked(get_option('fullpage_parallax'), 1); ?>>
							Checking this box will enable Parallax Extension on Fullpage Widget.
							</label>
						</td>
						</tr>        
					</table>
					<table class="form-table">
						<tr valign="top">
						<th scope="row">Activation Key Parallax Extension</th>
						<td>
							<label>
							<input type="text" name="fullpage_parallax_key" value="<?php echo get_option('fullpage_parallax_key'); ?>" class="bwp-text" id="fullpage_parallax_key" />
							Enter your Activation Key.
							</label>
						</td>					
						</tr>        
					</table>
				<h4 class="title">Woo Orders</h4>
				    <table class="form-table">
						<tr valign="top">
						<th scope="row">Enable quick minimal checkout fields</th>
						<td>
							<label>
							<input type="checkbox" name="wo_fields" value="1" class="bwp-checkbox" id="wo_fields" <?php checked(get_option('wo_fields'), 1); ?>>
							Checking this box will enable quick minimal checkout fields for one page checkout layout.							
							</label>
						</td>						
						</tr>        
					</table>
				<h4 class="title">Bew Checkout</h4>
				    <table class="form-table">
						<tr valign="top">
						<th scope="row">Regenerate default checkout fields</th>
						<td>
							<button type="button" class="button reset-default-checkout">Regenerate</button>
						</td>						
						</tr>        
					</table>
				<?php  submit_button(); ?>
			</form>
		</div>
		<?php
	}
	
	public function tab_section_autocomplete() {		
		do_action( 'bew_admin_tabs');
	}
	/**
	 * Admin notice for non active Elementor.
	 *
	 * Warning when the site doesn't have active Elementor plugin
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	function fail_elementor() {
		echo '<div class="error">'.
		     wpautop(
			     esc_html__( 'Briefcasewp Extras requires Elementor plugin to be active. Without it import of templates will not work.', 'bew-extras' )
		     ).'</div>';
	}
	
	function fail_plugin_active() {
		echo '<div class="error">'.
		     wpautop(
			     esc_html__( 'Briefcasewp Extras requires Elementor plugin to be active. Without it import of templates will not work.', 'bew-extras' )
		     ).'</div>';
	}
	
	public function bew_confirm_password() {		
		
		//Get confirm password from ajax
		
		if (!get_site_option('bew_extras_options')) {
		add_site_option( 'bew_extras_options', '');
		}
		
		if(! empty( $_POST['confirm_password'] ) ){
			$confirm_password = esc_html($_POST['confirm_password']);
			$options = get_site_option('briefcase-elementor-widgets_updater_options');
				
			//Check if is password is right;	
			$check_link = 'https://briefcasewp.com/wp-content/uploads/private_import/check_link.json';			
			$username = isset($options['email']) ? $options['email'] : '';
			$password = $confirm_password;
			$auth = base64_encode( $username . ':' . $password );
			$context = stream_context_create([
				"http" => [
					"header" => "Authorization: Basic $auth"
				]
			]);			
			
			// get check json
			$check_file = file_get_contents($check_link, false, $context );
			$check_file2 = json_decode($check_file, true);			
			$message = $check_file2['message'];
			
			echo $message; 
			
			if( $message == 'connected' ){
				$option_bew_extras = get_site_option('bew_extras_options');
				if (!is_array($option_bew_extras)) $option_bew_extras = array();
				$option_bew_extras['confirm_password'] = base64_encode($confirm_password);		
				update_site_option('bew_extras_options', $option_bew_extras);
			}
		}
	}
	
	function my_scripts(){
		
	//passing variables to the javascript file
		wp_localize_script('bew-admin', 'frontEndAjax', array(
		'ajaxurl' => admin_url( 'admin-ajax.php' )
		));	
	
	}
	
	function bew_extras_register_settings() {		
	   register_setting( 'bew_extras_options_group', 'briefcasewp_extras_styles');
	   register_setting( 'bew_extras_options_group', 'briefcasewp_extras_scripts');
	   register_setting( 'bew_extras_options_group', 'woo_grid_cache');
	   register_setting( 'bew_extras_options_group', 'jsf_id_template');
	   register_setting( 'bew_extras_options_group', 'woo_bew_cart');
	   register_setting( 'bew_extras_options_group', 'fullpage_parallax');
	   register_setting( 'bew_extras_options_group', 'fullpage_parallax_key');
	   register_setting( 'bew_extras_options_group', 'wo_fields');
	}

	function bew_show_tabs() {

		$tabs = [
			'import'        => esc_html__( 'Import Templates', 'bew-extras' ),
			'instructions'  => esc_html__( 'Importer Instructions', 'bew-extras' ),
			//'license' 		=> esc_html__( 'License', 'bew-extras' ),
			'settings' 		=> esc_html__( 'Settings', 'bew-extras' ),
			'briefcasewp' 	=> esc_html__( 'More from BriefcaseWp', 'bew-extras' )
				
		];
	 
		if(has_filter('bew_add_tabs')) {
			$tabs = apply_filters('bew_add_tabs', $tabs);			
		}
		
		return $tabs;
	}
	
	/**
	 * Admin constructor.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_styles' ] );

		add_filter( 'admin_footer_text', [ $this, 'admin_footer_text' ], 30 );

		//add_filter( 'elementor/admin/dashboard_overview_widget/footer_actions', [ $this, 'dashboard_widget_links' ] );		
		
		add_action( 'admin_menu', [ $this, 'admin_pages' ], 20 ); //as last item
		add_action( 'admin_enqueue_scripts', [ $this, 'my_scripts' ] );
		add_action( 'wp_ajax_bew_confirm_password', [ $this, 'bew_confirm_password' ] );
		add_action( 'admin_init', [ $this,  'bew_extras_register_settings'] );
				
		add_action( 'bew_show_tabs', [ $this, 'bew_show_tabs' ], 10 );
		
		
	}
}

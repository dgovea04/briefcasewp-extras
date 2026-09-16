<?php
namespace BriefcasewpExtras;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Extensions
 *
 * Extensions handles
 *
 * @since 1.0.0
 */
class Extensions {

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
	private $extensions_page = 'bew-extensions';

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
		// Addons submenu
		add_submenu_page( 
			'bew-templates', 
			'Extensions', 
			'Extensions', 
			'manage_options', 
			'briefcasewp_extensions', 
			array( $this, 'bew_addons_extensions' )
		);		

	}
		
	/**
	* Admin Screen for extensions
	*/
	function bew_addons_extensions() {
    // Get all extensions
    $all_extensions    = ext_get_extensions();
    // Get active extensions
    $active_extensions = ext_get_active_extensions();
			
    ?>
    <div class="wrap">
        <h1><?php echo get_admin_page_title(); ?></h1>
		<p><?php _e( 'All Briefcasewp Extensions. Choose which to use and activate them.', 'briefcasewp-extras' ); ?></p>
     
		<div class="wp-list-table widefat plugin-install">
			<div id="the-list">
				<?php					
				if( $all_extensions ) {
					foreach ( $all_extensions as $slug => $class ) {
						if( ! class_exists( $class ) ) {
							continue;
						}
						// Instantiate each extension
						$extension_object = new $class();
						// We will use this object to get the title, description and image of the extension.
						?>
						<div class="plugin-card bew-plugin-card plugin-card-<?php echo $slug; ?>">
							<div class="plugin-card-top bew-plugin-card-top">
								<div class="bew-plugin-icon">									                   
									<img src="<?php echo $extension_object->image; ?>" class="plugin-icon" alt="">									
								</div>
								<div class="bew-plugin-name">
									<div class="name column-name">
										<h3>
											<?php echo $extension_object->title; ?>
										</h3>
									</div>
									<div class="desc column-description">
										<p><?php echo $extension_object->desc; ?></p>
									</div>
								</div>
							</div>
							<div class="plugin-card-bottom bew-plugin-card-bottom">
							<?php 
							// Use the buttons from our Abstract class to create the buttons
							// Can be overwritten by each integration if needed.
								$extension_object->buttons( $active_extensions );
							?>
							</div>
						</div>
					<?php
					}
				}					
			?>
			</div>
		</div>
    </div> 
<?php
}


	/**
	 * Extensions constructor.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {
		add_action( 'admin_menu', [ $this, 'admin_pages' ], 50 ); //as last item
	}
}

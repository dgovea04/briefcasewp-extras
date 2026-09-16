<?php
/**
 *
 * Handles the admin functionality.
 *
 * @package WordPress
 * @subpackage Embed Videos For Product Image Gallery Using WooCommerce
 * @since 1.0
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Check if WooCommerce is active
 */
global $post;

register_activation_hook ( BEW_EXTRAS__FILE__, 'bew_woo_activation_check');
function bew_woo_activation_check()
{
	if ( !in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
		deactivate_plugins( BEW_EXTRAS_PLUGIN_BASE );
		wp_die( _e( '<b>Warning</b> : Install/Activate Woocommerce to activate "Bew Gallery Video" extension.' , 'bew-extras' ) );
	}
}

/**
 * Set up menu under woocommerce
 */
add_action( 'admin_menu', 'bew_embed_videos_setup_menu', 600);

function bew_embed_videos_setup_menu() {
	//add_submenu_page( 'bew-templates', 'Embed Videos To Product Image Gallery', 'Bew Gallery Videos', 'manage_options', 'bew-gallery-videos', 'embed_videos_init', 1);
}

/**
 * Register options of this plugin
 */
//add_action( 'admin_init', 'bew_register_embed_videos_settings' );
function bew_register_embed_videos_settings() {
	register_setting( 'embed-videos-settings', 'embed_videos_autoplay' );
	register_setting( 'embed-videos-settings', 'embed_videos_rel' );
	register_setting( 'embed-videos-settings', 'embed_videos_showinfo' );
	register_setting( 'embed-videos-settings', 'embed_videos_disablekb' );
	register_setting( 'embed-videos-settings', 'embed_videos_fs' );
	register_setting( 'embed-videos-settings', 'embed_videos_controls' );
	register_setting( 'embed-videos-settings', 'embed_videos_hd' );
 }


/**
* Initialize the plugin and display all options at admin side
*/
function embed_videos_init() {
?>
  <h1><?php echo _e( 'Youtube Video Settings', 'bew-extras' ); ?></h1>
  <form method="post" action="options.php">
	<?php settings_fields( 'embed-videos-settings' ); ?>
	<?php do_settings_sections( 'embed-videos-settings' ); ?>
	<table class="form-table">
		<tr valign="top">
			<th scope="row"><?php echo _e( 'Autoplay videos', 'bew-extras' ).':'; ?></th>
			<td><input type="checkbox" name="embed_videos_autoplay" value="1" <?php echo ( get_option( 'embed_videos_autoplay' ) == 1 ) ? 'checked': ''; ?> /></td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php echo _e( 'Show relative videos', 'bew-extras' ).':'; ?></th>
			<td><input type="checkbox" name="embed_videos_rel" value="1" <?php echo ( get_option( 'embed_videos_rel' ) == 1 ) ? 'checked': ''; ?> /></td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php echo _e( 'Show video information', 'bew-extras' ).':'; ?></th>
			<td>
				<input type="checkbox" name="embed_videos_showinfo" value="1" <?php echo ( get_option( 'embed_videos_showinfo' ) == 1 ) ? 'checked': ''; ?> />
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php echo _e( 'Show fullscreen button', 'bew-extras' ).':'; ?></th>
			<td>
				<input type="checkbox" name="embed_videos_fs" value="1" <?php echo ( get_option( 'embed_videos_fs' ) == 1 ) ? 'checked': ''; ?> />
			</td>
		</tr>
		 <tr valign="top">
			<th scope="row"><?php echo _e( 'Show video player controls', 'bew-extras' ).':'; ?></th>
			<td>
				<input type="checkbox" name="embed_videos_controls" value="1" <?php echo ( get_option( 'embed_videos_controls' ) == 1 ) ? 'checked': ''; ?> />
			</td>
		</tr>
	</table>
	<?php submit_button(); ?>
	</div>
  </form>

<?php
}

/**
 * Add form field to get video link id for product image
 */
add_filter( 'attachment_fields_to_edit', 'bew_woo_embed_video', 20, 2);
function bew_woo_embed_video( $form_fields, $attachment ) {
	
	$getpost = $_GET[ 'post' ] ?? null;
	$post_id = (int) $getpost;
	$nonce = wp_create_nonce( 'bdn-attach_' . $attachment->ID );
	$attach_image_action_url = admin_url( "media-upload.php?tab=library&post_id=$post_id" );

	$field_value = get_post_meta( $attachment->ID, 'bewvideolink_id', true );
	$video_site = get_post_meta( $attachment->ID, 'bewvideo_site', true );
	$youtube = ( $video_site == 'youtube' ) ? 'checked' : '';
	$vimeo = ( $video_site == 'vimeo' ) ? 'checked' : '';
	$checked = '';
	if( empty( $youtube ) && empty( $vimeo) )
	{
		$checked = 'checked';
	}
	$form_fields['bewvideolink_id'] = array(
		'value' => $field_value ? $field_value : '',
		'input' => "text",
		'label' => __( 'Video Link ID', 'bew-extras' )
	);
	$form_fields['bewvideo_site'] = array(
		'input' => 'html',
		'label' => __( 'Video Site', 'bew-extras' ),
		'value' => $video_site,
		'html' => "<input type='radio' name='attachments[{$attachment->ID}][bewvideo_site]' value='youtube' $youtube $checked> Youtube
					<input type='radio' name='attachments[{$attachment->ID}][bewvideo_site]' value='vimeo' $vimeo> Vimeo",
		'helps' => __( '<b>Example:</b> <br>"kLM_bm80YKs" for URL - https://www.youtube.com/watch?v=kLM_bm80YKs <br>
					 <br>"98765432" for URL - https://vimeo.com/98765432' )
	);
	return $form_fields;
}

/**
* Save form field of video link to display video on product image
*/
add_action( 'edit_attachment', 'bew_woo_save_embed_video' );
function bew_woo_save_embed_video( $attachment_id ) {
	if ( isset( $_REQUEST['attachments'][$attachment_id]['bewvideolink_id'] ) ) {
		$videolink_id = sanitize_text_field ( $_REQUEST['attachments'][$attachment_id]['bewvideolink_id'] );
		update_post_meta( $attachment_id, 'bewvideolink_id', $videolink_id );
	}
	if ( isset( $_REQUEST['attachments'][$attachment_id]['bewvideo_site'] ) ) {
		$video_site = sanitize_text_field ( $_REQUEST['attachments'][$attachment_id]['bewvideo_site'] );
		update_post_meta( $attachment_id, 'bewvideo_site', $video_site );
	}
}

<?php
namespace BriefcasewpExtras\Widgets;

use Elementor;
use Elementor\Plugin;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Bew_Render {

	public static $id = 0;
	public static $widget = null;


	/*
	|--------------------------------------------------------------------------
	| Section tag
	|--------------------------------------------------------------------------
	*/
	public static function section_tag_html( $arg = [] ) {
		$no_container = isset( $arg['no_container'] ) ? $arg['no_container'] : false;
		$extra_class  = isset( $arg['class'] ) ? $arg['class'] : '';
		$attr_style   = '';
		$attr_id      = '';
		$attr_overlay = '';
		$wide_container = isset( $arg['wide_container'] ) ? $arg['wide_container'] : false;



		if ( ! empty( self::get_v('section_id') ) ) {
			$attr_id = 'id="'. esc_attr( self::get_v('section_id') ) .'"';
		}

		if ( ! empty( self::get_v('bg_image.url') ) ) {
			$attr_style = 'background-image: url('. esc_attr( self::get_v('bg_image.url') ) .');';
		}

		if ( ! empty( self::get_v('gradient_start') ) ) {
			$attr_style = 'background-image: linear-gradient(120deg, '. esc_attr( self::get_v('gradient_start') ) .' 0%, '. esc_attr( self::get_v('gradient_end') ) .' 100%);';
		}

		if ( ! empty( self::get_v('bg_color') ) ) {
			$attr_style .= 'background-color: '. esc_attr( self::get_v('bg_color') ) .';';
		}

		if ( ! empty( $attr_style ) ) {
			$attr_style = 'style="'. $attr_style .'"';
		}


		if ( ! empty( self::get_v('overlay.size') ) ) {
			$attr_overlay = ' data-overlay="'. esc_attr( self::get_v('overlay.size') ) .'"';
		}

		?>
		<section <?php echo $attr_id; ?>
				 class="bew-section <?php echo $extra_class; self::attr_v('bg_gray'); self::attr_v('border_bottom'); self::attr_v('section_inverse'); ?>" <?php echo $attr_style . $attr_overlay; ?>>
			<?php if( false === $no_container ) : ?>
				<?php if( true === $wide_container ) : ?>
					<div class="bew-container-wide">
				<?php else : ?>
					<div class="bew-container<?php self::attr_v('wide_container', false); ?>">
				<?php endif; ?>
			<?php endif; ?>
		<?php
	}


	public static function section_tag_js( $arg = [] ) {
		$no_container = isset( $arg['no_container'] ) ? $arg['no_container'] : false;
		$wide_container = isset( $arg['wide_container'] ) ? $arg['wide_container'] : false;
		$extra_class = isset( $arg['class'] ) ? $arg['class'] : '';
		?>
		<#
		var classes = 'bew-section <?php echo $extra_class; ?>';
		var style = '';
		var overlay = '';


		if ( undefined !== <?php self::s('bg_image'); ?> ) {
			style += 'background-image: url('+ <?php self::s('bg_image.url'); ?> +');';
		}

		if ( undefined !== <?php self::s('gradient_start'); ?> ) {
			style += 'background-image: linear-gradient(120deg, '+ <?php self::s('gradient_start'); ?> +' 0%, '+ <?php self::s('gradient_end'); ?> +' 100%);';
		}

		if ( undefined !== <?php self::s('bg_color'); ?> ) {
			style += 'background-color: '+ <?php self::s('bg_color'); ?> +';';
		}

		if ( '' !== style ) {
			style = 'style="'+ style +'"';
		}


		classes += ' '+ <?php self::s('bg_gray'); ?> +' '+
						<?php self::s('border_bottom'); ?> +' '+
						<?php self::s('section_inverse'); ?>;


		if ( undefined !== <?php self::s('overlay'); ?> ) {
			overlay = ' data-overlay="'+ <?php self::s('overlay.size'); ?> +'"';
		}


		#>
		<section class="<# print(classes) #>" <# print(style); print(overlay) #>>
			<?php if( false === $no_container ) : ?>
				<?php if( true === $wide_container ) : ?>
					<div class="bew-container-wide">
				<?php else : ?>
					<div class="bew-container<?php self::attr_s('wide_container', false); ?>">
				<?php endif; ?>
			<?php endif; ?>
		<?php
	}



	/*
	|--------------------------------------------------------------------------
	| Header tag
	|--------------------------------------------------------------------------
	*/
	public static function header_tag_html( $arg = [] ) {
		$settings = self::$widget->get_settings();
		$id = self::$id;
		$container = isset( $arg['container'] ) ? $arg['container'] : '';
		$overflow = isset( $arg['overflow'] ) ? $arg['overflow'] : 'overflow-hidden';

		$attr_id = '';
		$background = $settings['t'. $id .'_background'];
		$fullscreen = $settings['t'. $id .'_fullscreen'];
		$inverse = esc_attr( $settings['t'. $id .'_header_inverse'] );
		$particle = $settings['t'. $id .'_particle'];
		$overlay = esc_attr( $settings['t'. $id .'_overlay']['size'] );
		$overlay_color = esc_attr( $settings['t'. $id .'_overlay_color'] );
		$padding_top = esc_attr( $settings['t'. $id .'_padding_top']['size'] );
		$padding_bottom = esc_attr( $settings['t'. $id .'_padding_bottom']['size'] );
		$fadeout = esc_attr( $settings['t'. $id .'_fadeout'] );

		$full_header = '';
		$full_row = '';
		if ( 'yes' == $fullscreen ) {
			$full_header = 'h-fullscreen';
			$full_row = 'h-full';
		}

		$bg_color = esc_attr( $settings['t'. $id .'_bg_color'] );
		$bg_color_img = esc_attr( $settings['t'. $id .'_bg_color_img'] );
		$particles_color = esc_attr( $settings['t'. $id .'_particles_color'] );
		$particles_length = esc_attr( $settings['t'. $id .'_particles_length']['size'] );

		$color_1 = esc_attr( $settings['t'. $id .'_color_1'] );
		$color_2 = esc_attr( $settings['t'. $id .'_color_2'] );
		$gradient_dir = esc_attr( $settings['t'. $id .'_gradient_dir'] );

		$bg_image = esc_url( $settings['t'. $id .'_bg_image']['url'] );
		$bg_image_type = $settings['t'. $id .'_bg_image_type'];

		$bg_video_poster = esc_url( $settings['t'. $id .'_bg_video_poster']['url'] );
		
		if ( 'yes' == $settings['t'. $id .'_bg_video_hosted']) {
		$bg_video_mp4 = esc_url( $settings['t'. $id .'_bg_video_mp4']['url'] );
		}else{
		$bg_video_mp4 = esc_attr( $settings['t'. $id .'_bg_video_link']);	
		}
		
		$bg_video_mute = "muted";
		if ( 'yes' !== $settings['t'. $id .'_mute'] ) {
			$bg_video_mute = "";
		}

		if ( ! empty( self::get_v('header_id') ) ) {
			$attr_id = 'id="'. esc_attr( $settings['t'. $id .'_header_id'] ) .'"';
		}
		
		$editmode = '';
		if(Elementor\Plugin::instance()->editor->is_edit_mode()){
		$editmode = 'yes';
		}
								
		?>

		<?php if ( 'color' == $background ) : ?>

			<header class="bew-header <?php echo 't'. $id .'-cover'; ?> <?php echo $fadeout; ?> <?php echo $inverse; ?> <?php echo $full_header; ?> <?php echo $bg_color_img; ?> <?php echo $overflow; ?>" style="background-color: <?php echo $bg_color; ?>; padding-top: <?php echo $padding_top ?>px; padding-bottom: <?php echo $padding_bottom ?>px;" <?php echo $attr_id; ?>>

		<?php elseif ( 'gradient' == $background ) : ?>

			<header class="bew-header <?php echo 't'. $id .'-cover'; ?> <?php echo $fadeout; ?> <?php echo $inverse; ?> <?php echo $full_header; ?> <?php echo $overflow; ?>" style="background-image: linear-gradient(to <?php echo $gradient_dir; ?>, <?php echo $color_1; ?> 0%, <?php echo $color_2; ?> 100%); padding-top: <?php echo $padding_top ?>px; padding-bottom: <?php echo $padding_bottom ?>px;" <?php echo $attr_id; ?>>

		<?php elseif ( 'image' == $background ) : ?>

			<?php if ( 'fixed' == $bg_image_type ) : ?>

				<header class="bew-header <?php echo 't'. $id .'-cover'; ?> <?php echo $fadeout; ?> <?php echo $inverse; ?> <?php echo $full_header; ?> <?php echo $overflow; ?> bg-fixed" style="background-image: url(<?php echo $bg_image; ?>); padding-top: <?php echo $padding_top ?>px; padding-bottom: <?php echo $padding_bottom ?>px;" <?php echo $attr_id; ?>>

			<?php elseif ( 'parallax' == $bg_image_type ) : ?>
				
				<?php if('yes' == $editmode ){?>
				<header class="bew-header <?php echo 't'. $id .'-cover'; ?> <?php echo $fadeout; ?> <?php echo $inverse; ?> <?php echo $full_header; ?> <?php echo $overflow; ?>" style="background-image: url(<?php echo $bg_image; ?>); padding-top: <?php echo $padding_top ?>px; padding-bottom: <?php echo $padding_bottom ?>px;" <?php echo $attr_id; ?>>
				<?php }else{ ?>
				<header class="bew-header <?php echo 't'. $id .'-cover'; ?> <?php echo $fadeout; ?> <?php echo $inverse; ?> <?php echo $full_header; ?> <?php echo $overflow; ?>" style="padding-top: <?php echo $padding_top ?>px; padding-bottom: <?php echo $padding_bottom ?>px;" data-parallax="<?php echo $bg_image; ?>" <?php echo $attr_id; ?>>				
				<?php } ?>
			<?php else : ?>

				<header class="bew-header <?php echo 't'. $id .'-cover'; ?> <?php echo $fadeout; ?> <?php echo $inverse; ?> <?php echo $full_header; ?> <?php echo $overflow; ?>" style="background-image: url(<?php echo $bg_image; ?>); padding-top: <?php echo $padding_top ?>px; padding-bottom: <?php echo $padding_bottom ?>px;" <?php echo $attr_id; ?>>

			<?php endif; ?>


		<?php elseif ( 'video' == $background ) : ?>

			<header class="bew-header <?php echo 't'. $id .'-cover'; ?> <?php echo $fadeout; ?> <?php echo $inverse; ?> <?php echo $full_header; ?> py-0 <?php echo $overflow; ?>" <?php echo $attr_id; ?>>
				<?php if ( 'yes' == $settings['t'. $id .'_bg_video_hosted']) { ?>
				<video class="bg-video" data-object-fit="cover" poster="<?php echo $bg_video_poster; ?>" autoplay loop <?php echo $bg_video_mute; ?>>
					<source src="<?php echo $bg_video_mp4; ?>" type="video/mp4">
				</video>
				<?php }else{ ?>				
				<div id="ytbg" data-youtube="<?php echo $bg_video_mp4; ?>"></div>				
				<?php } ?>

		<?php endif; ?>

		<?php if ( 0 < $overlay ) : ?>
			<div class="header-overlay opacity-<?php echo $overlay; ?>0" style="background-color: <?php echo $overlay_color; ?>"></div>
		<?php endif; ?>

		<?php if ( 'yes' == $particle ) : ?>
			<canvas class="constellation" data-color="<?php echo $particles_color; ?>" data-length="<?php echo $particles_length; ?>"></canvas>
		<?php endif; ?>


		<?php if ( 'video' == $background ) : ?>
			<div class="widget-container<?php echo $container; ?> text-center" style="padding-top: <?php echo $padding_top ?>px; padding-bottom: <?php echo $padding_bottom ?>px;">
		<?php else: ?>
			<div class="widget-container<?php echo $container; ?> text-center">
		<?php endif; ?>


		<?php
	}


	public static function header_tag_js( $arg = [] ) {
		$id = self::$id;
		$container = isset( $arg['container'] ) ? $arg['container'] : '';
		$overflow = isset( $arg['overflow'] ) ? $arg['overflow'] : 'overflow-hidden';
		
		$editmode = '';
		if(Elementor\Plugin::instance()->editor->is_edit_mode()){
		$editmode = 'yes';
		}
		
		?>
			<#
			var background = settings.t<?php echo $id; ?>_background;
			var fullscreen = settings.t<?php echo $id; ?>_fullscreen;
			var inverse = settings.t<?php echo $id; ?>_header_inverse;
			var particle = settings.t<?php echo $id; ?>_particle;
			var overlay = settings.t<?php echo $id; ?>_overlay.size;
			var overlay_color = settings.t<?php echo $id; ?>_overlay_color;
			var padding_top = settings.t<?php echo $id; ?>_padding_top.size;
			var padding_bottom = settings.t<?php echo $id; ?>_padding_bottom.size;
			var fadeout = settings.t<?php echo $id; ?>_fadeout;

			var full_header = '';
			var full_row = '';
			if ( 'yes' == fullscreen ) {
				full_header = 'h-fullscreen';
				full_row = 'h-full';
			}

			var bg_color = settings.t<?php echo $id; ?>_bg_color;
			var bg_color_img = settings.t<?php echo $id; ?>_bg_color_img;
			var particles_color = settings.t<?php echo $id; ?>_particles_color;
			var particles_length = settings.t<?php echo $id; ?>_particles_length.size;

			var color_1 = settings.t<?php echo $id; ?>_color_1;
			var color_2 = settings.t<?php echo $id; ?>_color_2;
			var gradient_dir = settings.t<?php echo $id; ?>_gradient_dir;

			var bg_image = settings.t<?php echo $id; ?>_bg_image.url;
			var bg_image_type = settings.t<?php echo $id; ?>_bg_image_type;

			var bg_video_poster = settings.t<?php echo $id; ?>_bg_video_poster.url;
			
			var bg_video_hosted = settings.t<?php echo $id; ?>_bg_video_hosted;
			
			if ( 'yes' == bg_video_hosted ) {
				var bg_video_mp4 = settings.t<?php echo $id; ?>_bg_video_mp4.url;
			}else{ 
				var bg_video_mp4 = settings.t<?php echo $id; ?>_bg_video_link;	
			}		
			
			var bg_video_mute = 'muted';
			if ( 'yes' !== settings.t<?php echo $id; ?>_mute ) {
				bg_video_mute = '';
			}
			
			var editmode = '<?php echo $editmode; ?>';
			
			#>

			<# if ( 'color' == background ) { #>

				<header class="bew-header <?php echo 't'. $id .'-cover'; ?> {{ fadeout }} {{ inverse }} {{ full_header }} {{ bg_color_img }} <?php echo $overflow; ?> " style="background-color: {{ bg_color }}; padding-top: {{ padding_top }}px; padding-bottom: {{ padding_bottom }}px;">

			<# } else if ( 'gradient' == background ) { #>

				<header class="bew-header <?php echo 't'. $id .'-cover'; ?> {{ fadeout }} {{ inverse }} {{ full_header }} <?php echo $overflow; ?>" style="background-image: linear-gradient(to {{ gradient_dir }}, {{ color_1 }} 0%, {{ color_2 }} 100%); padding-top: {{ padding_top }}px; padding-bottom: {{ padding_bottom }}px;">

			<# } else if ( 'image' == background ) { #>

				<# if ( 'fixed' == bg_image_type ) { #>

					<header class="bew-header <?php echo 't'. $id .'-cover'; ?> {{ fadeout }} {{ inverse }} {{ full_header }} <?php echo $overflow; ?> bg-fixed" style="background-image: url({{ bg_image }}); padding-top: {{ padding_top }}px; padding-bottom: {{ padding_bottom }}px;">

				<# } else if ( 'parallax' == bg_image_type ) { #>
					<# if ( 'yes' == editmode ) { #>
					<header class="bew-header <?php echo 't'. $id .'-cover'; ?> {{ fadeout }} {{ inverse }} {{ full_header }} <?php echo $overflow; ?>" style=" background-image: url({{ bg_image }}); padding-top: {{ padding_top }}px; padding-bottom: {{ padding_bottom }}px;">
					<# } else { #>
					<header class="bew-header <?php echo 't'. $id .'-cover'; ?> {{ fadeout }} {{ inverse }} {{ full_header }} <?php echo $overflow; ?>" style="padding-top: {{ padding_top }}px; padding-bottom: {{ padding_bottom }}px;" data-parallax="{{ bg_image }}">
					<# } #>
				<# } else { #>

					<header class="bew-header <?php echo 't'. $id .'-cover'; ?> {{ fadeout }} {{ inverse }} {{ full_header }} <?php echo $overflow; ?>" style="background-image: url({{ bg_image }}); padding-top: {{ padding_top }}px; padding-bottom: {{ padding_bottom }}px;">

				<# } #>


			<# } else if ( 'video' == background ) { #>

				<header class="bew-header <?php echo 't'. $id .'-cover'; ?> {{ fadeout }} {{ inverse }} {{ full_header }} <?php echo $overflow; ?> py-0">
					<# if ( 'yes' == bg_video_hosted ) {  #>
						<video class="bg-video" data-object-fit="cover" poster="{{ bg_video_poster }}" autoplay loop {{ bg_video_mute }}>
							<source src="{{ bg_video_mp4 }}" type="video/mp4">
						</video>
						<# } else { #>
						<div id="ytbg" data-youtube="{{ bg_video_mp4 }}"></div>				
					<# } #>

			<# } #>

			<# if ( 0 < overlay ) { #>
				<div class="header-overlay opacity-{{ overlay }}0" style="background-color: {{ overlay_color }}"></div>
			<# } #>

			<# if ( 'yes' == particle ) { #>
				<canvas class="constellation" data-color="{{ particles_color }}" data-length="{{ particles_length }}"></canvas>
			<# } #>

			<div class="widget-container<?php echo $container; ?> text-center">

		<?php
	}



	/*
	|--------------------------------------------------------------------------
	| Section header
	|--------------------------------------------------------------------------
	*/
	public static function section_header_html( $arg = [] ) {
		if ( empty( self::get_v('small_text') ) &&
			 empty( self::get_v('header_text') ) &&
			 empty( self::get_v('lead_text') )
			) {
			return;
		}

		$h2_class = '';
		if ( isset( $arg['class'] ) ) {
			$h2_class = 'class="'. $arg['class'] .'"';
		}
		?>
		<header class="section-header">
			<?php if ( self::get_v('small_text') ): ?>
				<small><?php self::v('small_text'); ?></small>
			<?php endif; ?>

			<?php if ( self::get_v('header_text') ): ?>
				<h2 <?php echo $h2_class; ?>><?php self::v('header_text'); ?></h2>
			<?php endif; ?>
			<hr>
			<?php if ( self::get_v('lead_text') ): ?>
				<p class="lead"><?php self::v('lead_text'); ?></p>
			<?php endif; ?>
		</header>
		<?php
	}


	public static function section_header_js( $arg = [] ) {

		$h2_class = '';
		if ( isset( $arg['class'] ) ) {
			$h2_class = 'class="'. $arg['class'] .'"';
		}

		?>
		<# if ( "" !== <?php self::s('small_text') ?> || "" !== <?php self::s('header_text') ?> || "" !== <?php self::s('lead_text') ?> ) { #>
		<header class="section-header">
			<small><?php self::html_s('small_text'); ?></small>
			<h2 <?php echo $h2_class; ?>><?php self::html_s('header_text'); ?></h2>
			<hr>
			<p class="lead"><?php self::html_s('lead_text'); ?></p>
		</header>
		<# } #>
		<?php
	}



	/*
	|--------------------------------------------------------------------------
	| Button
	|--------------------------------------------------------------------------
	*/
	public static function button_html( $arg = [] ) {

		if ( empty( self::get_v('btn_text') ) ) {
			return '';
		}


		$tag = isset( $arg['tag'] ) ? $arg['tag'] : 'a';
		$classes = isset( $arg['class'] ) ? ' '. $arg['class'] : '';
		$id = '';
		$style = '';
		$target = '';
		$scrollto = '';
		$url = self::get_v('btn_link.url');

		if ( strlen( $url ) > 1 && 0 === strpos( $url, '#' ) ) {
			$scrollto = ' data-scrollto="'. esc_attr( substr( $url, 1 ) ) .'"';
			//$url = '#';
		}

		if ( isset( $arg['id'] ) ) {
			$id = ' id="'. esc_attr( $arg['id'] ) .'"';
		}

		if ( self::get_v('btn_link.is_external') ) {
			$target = ' target="_blank"';
		}


		if ( self::get_v('btn_width.size') > 0 ) {
			$style = 'style="min-width: '. self::get_v('btn_width.size') .'px"';
		}
		?>
		<<?php echo esc_attr( $tag ); ?> class="btn components-button <?php self::attr_v('btn_size');
							self::attr_v('btn_block');
							self::attr_v('btn_outline');
							self::attr_v('btn_round');
							self::attr_v('btn_color');
							echo esc_attr( $classes ); ?>"
			<?php if ( 'a' == $tag ): ?>
				href="<?php echo esc_url( $url ); ?>"
			<?php endif; ?>

			<?php echo $id; ?>
			<?php echo $style; ?>
			<?php echo $target; ?>
			<?php echo $scrollto; ?>>
			<?php self::html_v('btn_text'); ?>
		</<?php echo esc_attr( $tag ); ?>>
		<?php
	}


	public static function button_js( $arg = [] ) {
		$tag = isset( $arg['tag'] ) ? $arg['tag'] : 'a';
		$classes = isset( $arg['class'] ) ? ' '. $arg['class'] : '';
		?>
		<#
		if ( '' !== <?php self::s('btn_text'); ?> ) {
			var style = '';

			if ( <?php self::s('btn_width.size'); ?> > 0 ) {
				style = 'style="min-width: '+ <?php self::s('btn_width.size'); ?> +'px;"';
			}

			#>
			<<?php echo esc_attr( $tag ); ?> class="btn components-button <?php self::attr_s('btn_size');
								self::attr_s('btn_outline');
								self::attr_s('btn_block');
								self::attr_s('btn_round');
								self::attr_s('btn_color');
								echo $classes; ?>"
				<# if ( 'a' == '<?php echo esc_attr( $tag ); ?>' ) { #>
					href="<?php self::attr_s('btn_link.url'); ?>"
				<# } #>

				 <# print(style) #>>
				<?php self::html_s('btn_text'); ?>
			</<?php echo esc_attr( $tag ); ?>>
		<# } #>
		
		<?php
	}



	/*
	|--------------------------------------------------------------------------
	| Button 2
	|--------------------------------------------------------------------------
	*/
	public static function button2_html( $arg = [] ) {

		if ( empty( self::get_v('btn2_text') ) ) {
			return '';
		}

		$tag = isset( $arg['tag'] ) ? $arg['tag'] : 'a';
		$classes = isset( $arg['class'] ) ? ' '. $arg['class'] : '';
		$id = '';
		$style = '';
		$target = '';
		$scrollto = '';
		$url = self::get_v('btn2_link.url');

		if ( strlen( $url ) > 1 && 0 === strpos( $url, '#' ) ) {
			$scrollto = ' data-scrollto="'. esc_attr( substr( $url, 1 ) ) .'"';
			//$url = '#';
		}

		if ( isset( $arg['id'] ) ) {
			$id = ' id="'. esc_attr( $arg['id'] ) .'"';
		}

		if ( self::get_v('btn2_link.is_external') ) {
			$target = ' target="_blank"';
		}

		if ( self::get_v('btn2_width.size') > 0 ) {
			$style = 'style="min-width: '. self::get_v('btn2_width.size') .'px"';
		}
		?>
		<<?php echo esc_attr( $tag ); ?> class="btn <?php self::attr_v('btn2_size');
							self::attr_v('btn2_block');
							self::attr_v('btn2_outline');
							self::attr_v('btn2_round');
							self::attr_v('btn2_color');
							echo esc_attr( $classes ); ?>"
			<?php if ( 'a' == $tag ): ?>
				href="<?php echo esc_url( $url ); ?>"
			<?php endif; ?>

			<?php echo $id; ?>
			<?php echo $style; ?>
			<?php echo $target; ?>
			<?php echo $scrollto; ?>>
			<?php self::html_v('btn2_text'); ?>
		</<?php echo esc_attr( $tag ); ?>>
		<?php
	}


	public static function button2_js( $arg = [] ) {
		$tag = isset( $arg['tag'] ) ? $arg['tag'] : 'a';
		$classes = isset( $arg['class'] ) ? ' '. $arg['class'] : '';
		?>
		<#
		if ( '' !== <?php self::s('btn2_text'); ?> ) {
			var style = '';

			if ( <?php self::s('btn2_width.size'); ?> > 0 ) {
				style = 'style="min-width: '+ <?php self::s('btn2_width.size'); ?> +'px;"';
			}

			#>
			<<?php echo esc_attr( $tag ); ?> class="btn <?php self::attr_s('btn2_size');
								self::attr_s('btn2_outline');
								self::attr_s('btn2_block');
								self::attr_s('btn2_round');
								self::attr_s('btn2_color');
								echo esc_attr( $classes ); ?>"
				<# if ( 'a' == '<?php echo esc_attr( $tag ); ?>' ) { #>
					href="<?php self::attr_s('btn2_link.url'); ?>"
				<# } #>

				 <# print(style) #>>
				<?php self::html_s('btn2_text'); ?>
			</<?php echo esc_attr( $tag ); ?>>
		<# } #>

		<?php
	}



	/*
	|--------------------------------------------------------------------------
	| Store badges
	|--------------------------------------------------------------------------
	*/
	public static function store_html( $arg = [] ) {
		$badge_apple = thesaas_get_img_uri( 'badge-apple.png' );
		$badge_google = thesaas_get_img_uri( 'badge-google.png' );
		$target = '';
		$link = '';

		if ( 'apple' == $arg['store'] ) :
			$link = self::get_v('apple_store_link.url');
			if ( empty( $link ) ) {
				return;
			}

			if ( self::get_v('apple_store_link.is_external') ) {
				$target = 'target="_blank"';
			}

		?>
			<a href="<?php echo esc_url( $link ); ?>" <?php echo $target; ?>><img class="img-fadein" src="<?php echo esc_url( $badge_apple ); ?>" alt="download on app store"></a>
		<?php
		else:
			$link = self::get_v('google_play_link.url');
			if ( empty( $link ) ) {
				return;
			}

			if ( self::get_v('google_play_link.is_external') ) {
				$target = 'target="_blank"';
			}

		?>
			<a href="<?php echo esc_url( $link ); ?>" <?php echo $target; ?>><img class="img-fadein" src="<?php echo esc_url( $badge_google ); ?>" alt="download on google play"></a>
		<?php
		endif;
	}


	public static function store_js( $arg = [] ) {
		$badge_apple = thesaas_get_img_uri( 'badge-apple.png' );
		$badge_google = thesaas_get_img_uri( 'badge-google.png' );

		if ( 'apple' == $arg['store'] ) :
		?>
			<# if ( '' !== <?php self::s('apple_store_link.url') ?> ) { #>
			<a href="<?php self::attr_s('apple_store_link.url') ?>" target="_blank"><img class="img-fadein" src="<?php echo $badge_apple; ?>" alt="download on app store"></a>
			<# } #>
		<?php
		else:
		?>
			<# if ( '' !== <?php self::s('google_play_link.url') ?> ) { #>
			<a href="<?php self::attr_s('google_play_link.url') ?>" target="_blank"><img class="img-fadein" src="<?php echo $badge_google; ?>" alt="download on google play"></a>
			<# } #>
		<?php
		endif;
	}



	/*
	|--------------------------------------------------------------------------
	| Info Text
	|--------------------------------------------------------------------------
	*/
	public static function info_html( $arg = [] ) {
		if ( empty( self::get_v('info_link.url') ) ) :
			?>
			<small><?php self::v('info_text') ?></small>
			<?php
		else:
			?>
			<small><a href="<?php self::url_v('info_link.url') ?>"><?php self::v('info_text') ?></a></small>
			<?php
		endif;
	}


	public static function info_js( $arg = [] ) {
		?>
		<#
		if ( '' == <?php self::s('info_link.url'); ?> ) {
		#>
			<small><?php self::html_s('info_text'); ?></small>
		<#
		}
		else {
		#>
			<small><a href="<?php self::attr_s('info_link.url'); ?>"><?php self::html_s('info_text'); ?></a></small>
		<#
		}
		#>
		<?php
	}



	/*
	|--------------------------------------------------------------------------
	| Gallery
	|--------------------------------------------------------------------------
	*/
	public static function gallery_html( $arg = [] ) {
		$prefix  = isset( $arg['prefix'] ) ? $arg['prefix'] : '';
		$postfix = isset( $arg['postfix'] ) ? $arg['postfix'] : '';

		$gallery = self::get_v('gallery');
		foreach ($gallery as $image) {
			echo $prefix .'<img src="' . esc_url( $image[ 'url' ] ) . '" alt="image">'. $postfix;
		}
	}


	public static function gallery_js( $arg = [] ) {
		$prefix  = isset( $arg['prefix'] ) ? $arg['prefix'] : '';
		$postfix = isset( $arg['postfix'] ) ? $arg['postfix'] : '';

		?>
		<# _.each( <?php self::s('gallery') ?>, function( image ) { #>
			<?php echo $prefix; ?><img src="{{ image.url }}" alt="image"><?php echo $postfix; ?>
		<# } ); #>
		<?php
	}



	/*
	|--------------------------------------------------------------------------
	| Social
	|--------------------------------------------------------------------------
	*/
	public static function social_html( $arg = [] ) {
		$member = $arg['member'];
		$medias = [
			'facebook',
			'twitter',
			'instagram',
			'linkedin',
			'dribbble',
			'github',
			'telegram',
			'xing',
		];

		foreach ($medias as $key => $media) {
			$input = $member[ 'social_'. $media ];
			if ( ! empty( $input ) ) {
				echo '<a class="social-'. $media .'" href="'. esc_url( $input ) .'"><i class="fa fa-'. $media .'"></i></a>';
			}
		}
	}

	public static function social_js( $arg = [] ) {
		?>
		<#
			var medias = [
				'facebook',
				'twitter',
				'instagram',
				'linkedin',
				'dribbble',
				'github',
				'telegram',
				'xing',
			];

			_.each( medias, function( media ) {
				var input = member[ 'social_'+ media ];
				if ( '' !== input ) {
					#>
					<a class="social-{{ media }}" href="{{ input }}"><i class="fa fa-{{ media }}"></i></a>
					<#
				}
			} );
		#>
		<?php
	}

	/*
	|--------------------------------------------------------------------------
	| Flash down
	|--------------------------------------------------------------------------
	*/
	public static function flash_down_html( $arg = [] ) {
		if ( 'yes' == self::get_v('flash_down') ) {
			?>
			<a class="<?php self::attr_v('flash_down_style') ?><?php self::attr_v('flash_down_inverse') ?>" href="#" data-scrollto="<?php self::attr_v('flash_down_target', false) ?>" style="margin-top: <?php self::attr_v('flash_down_padding_top.size', false) ?>px;"><span></span></a>
			<?php
		}
	}

	public static function flash_down_js( $arg = [] ) {
		?>
		<# if ( 'yes' == <?php self::s('flash_down'); ?> ) { #>
		<a class="<?php self::attr_s('flash_down_style') ?><?php self::attr_s('flash_down_inverse') ?>" href="#" data-scrollto="<?php self::attr_s('flash_down_target', false) ?>" style="margin-top: <?php self::attr_s('flash_down_padding_top.size', false) ?>px;"><span></span></a>
		<# } #>
		<?php
	}

	/*
	|--------------------------------------------------------------------------
	| Helper functions
	|--------------------------------------------------------------------------
	*/
	public static function s( $name ) {
		echo 'settings.t'. self::$id .'_'. $name;
	}

	public static function attr_s( $name, $space = true ) {
		if ( $space ) {
			echo ' ';
		}
		echo '{{ settings.t'. self::$id .'_'. $name .' }}';
	}

	public static function html_s( $name ) {
		echo '{{{ settings.t'. self::$id .'_'. $name .' }}}';
	}

	public static function v( $name ) {
		echo self::get_v( $name );
	}

	public static function get_v( $name ) {
		$dot = strpos( $name, '.' );
		if ( false === $dot ) {
			return self::$widget->get_settings( 't'. self::$id .'_'. $name );
		}

		$index = substr( $name, $dot + 1 );
		$name  = substr( $name, 0, $dot );
		
		return isset(self::$widget->get_settings( 't'. self::$id .'_'. $name )[ $index ]) ? self::$widget->get_settings( 't'. self::$id .'_'. $name )[ $index ] : '' ;
	}

	public static function attr_v( $name, $space = true ) {
		if ( $space ) {
			echo ' ';
		}
		echo esc_attr( self::get_v( $name ) );
	}

	public static function url_v( $name ) {
		echo esc_url( self::get_v( $name ) );
	}

	public static function html_v( $name ) {
		echo esc_html( self::get_v( $name ) );
	}

}


<?php
namespace TheThemeio\Widgets;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class The_Testimonial_Block_4 {

  const ID = 4;

  public function controls( $widget ) {
    $widget->set_id( self::ID );
    $id = self::ID;

    $widget->panel( 'section', [
      'includes' => [ 'bg_gray' ],
    ] );


    $widget->panel( 'header_content', [
      'header' => esc_html__( 'User Reviews', 'thesaas' ),
      'lead'   => esc_html__( 'Join thousands of satisfied customers using our template globally.', 'thesaas' ),
    ] );


    $widget->panel( 'testimonials_2', [ 'no_columns' => true, 'section_title' => 'Testimonials' ] );

  }



  public function html( $widget ) {
    $widget->set_id( self::ID );
    $settings = $widget->get_settings();

    $autoplay = 'false';
    if ( 'yes' == $settings['t4_swiper_autoplay'] ) {
      $autoplay = $settings['t4_swiper_delay']['size'];
    }
    ?>
    <?php $widget->html('section_tag'); ?>
      <?php $widget->html('section_header'); ?>

      <div class="swiper-container swiper-button-circular" data-slides-per-view="2" data-space-between="50" data-centered-slides="true" data-autoplay="<?php echo $autoplay; ?>">
        <div class="swiper-wrapper pb-0">

          <?php foreach ( $settings['t4_testimonials'] as $item ) : ?>

            <div class="swiper-slide">

              <div class="card card-shadowed">
                <div class="card-block px-30">
                  <div class="rating mb-12">
                    <?php
                      for ( $i=0; $i<5; $i++) {
                        if ( $item['stars']['size'] > $i ) {
                          echo '<label class="fa fa-star active"></label>';
                        }
                        else {
                          echo '<label class="fa fa-star"></label>';
                        }
                      }
                    ?>
                  </div>

                  <p class="text-quoted mb-0"><?php echo $item['content']; ?></p>
                  <div class="media align-items-center pb-0">
                    <img class="avatar avatar-xs" src="<?php echo esc_url( $item['image']['url'] ); ?>" alt="<?php echo esc_attr( $item['name'] ); ?>">
                    <div class="media-body lh-1">
                      <h6 class="mb-0"><?php echo $item['name']; ?></h6>
                      <small><?php echo $item['title']; ?></small>
                    </div>
                  </div>
                </div>
              </div>

            </div>

          <?php endforeach; ?>

        </div>

        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
      </div>

    </div></section>
    <?php
  }



  public function javascript( $widget ) {
    $widget->set_id( self::ID );
    ?>
    <?php $widget->js('section_tag'); ?>
      <?php $widget->js('section_header'); ?>

        <div class="swiper-container swiper-button-circular" data-slides-per-view="2" data-space-between="50" data-centered-slides="true">
          <div class="swiper-wrapper pb-0">

          <# _.each( settings.t4_testimonials, function( item ) { #>
          <div class="swiper-slide">

            <div class="card card-shadowed">
              <div class="card-block px-30">
                <div class="rating mb-12">
                <#
                for ( var i = 0; i < 5; i++ ) {
                  if ( item.stars.size > i ) {
                    print( '<label class="fa fa-star active"></label>' );
                  }
                  else {
                    print( '<label class="fa fa-star"></label>' );
                  }
                }
                #>
                </div>

                <p class="text-quoted mb-0">{{{ item.content }}}</p>
                <div class="media align-items-center pb-0">
                  <img class="avatar avatar-xs" src="{{ item.image.url }}" alt="{{ item.name }}">
                  <div class="media-body lh-1">
                    <h6 class="mb-0">{{{ item.name }}}</h6>
                    <small>{{{ item.title }}}</small>
                  </div>
                </div>
              </div>
            </div>

          </div>
          <# } ); #>

        </div>

        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
      </div>

    </div></section>
    <?php
  }

}

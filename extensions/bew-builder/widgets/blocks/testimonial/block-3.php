<?php
namespace TheThemeio\Widgets;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class The_Testimonial_Block_3 {

  const ID = 3;

  public function controls( $widget ) {
    $widget->set_id( self::ID );
    $id = self::ID;

    $widget->panel( 'section', [
      'includes' => [ 'bg_gray' ],
    ] );

    $widget->panel( 'header_content', [
      'small'  => esc_html__( 'Reviews', 'thesaas' ),
      'header' => esc_html__( 'Happy Customers', 'thesaas' ),
      'lead'   => esc_html__( 'Join thousands of satisfied customers using our template globally.', 'thesaas' ),
    ] );


    $widget->panel( 'testimonials_2', [ 'section_title' => 'Testimonials' ] );

  }



  public function html( $widget ) {
    $widget->set_id( self::ID );
    $settings = $widget->get_settings();

    $cols = $settings['t3_columns']['size'];
    $col_class = 'col-12';
    switch ( $cols ) {
      case 1:
        $col_class = 'col-12';
        break;

      case 2:
        $col_class = 'col-12 col-md-6';
        break;

      case 3:
        $col_class = 'col-12 col-lg-4';
        break;
      
      default:
        $col_class = 'col-12 col-md-6';
        break;
    }
    ?>
    <?php $widget->html('section_tag'); ?>
      <?php $widget->html('section_header'); ?>

        <div class="row gap-y">

          <?php foreach ( $settings['t3_testimonials'] as $item ) : ?>

            <div class="<?php echo $col_class; ?>">
              
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

    </div></section>
    <?php
  }



  public function javascript( $widget ) {
    $widget->set_id( self::ID );
    ?>
    <?php $widget->js('section_tag'); ?>
      <?php $widget->js('section_header'); ?>

        <div class="row gap-y">

        <#
        var cols = settings.t3_columns.size;
        _.each( settings.t3_testimonials, function( item ) {

          if ( 1 == cols ) {
          #>
          <div class="col-12">

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
          <#
          } else if ( 2 == cols ) {
          #>
          <div class="col-12 col-md-6">

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
          <#
          } else if ( 3 == cols ) {
          #>
          <div class="col-12 col-lg-4">

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
          <#
          };
        } ); #>

        </div>

    </div></section>
    <?php
  }

}

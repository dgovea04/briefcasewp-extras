<?php
namespace BriefcasewpExtras\Widgets;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class Bew_Content_Block_3 {

  const ID = 3;

  public function controls( $widget ) {
    $widget->set_id( self::ID );
    $id = self::ID;

    $cats = get_categories( array(
      'hide_empty' => 0,
    ));
    $cat_options = [];

    $cat_options[0] = "All categories";
    foreach ($cats as $cat) {
      $cat_options[ $cat->term_id ] = $cat->name;
    }

    $widget->panel( 'section', [
      'includes' => [ 'bg_gray' ],
      'bg_gray' => true,
    ] );

    $widget->panel( 'header_content', [
      'small'  => esc_html__( 'News', 'briefcasewp-extras' ),
      'header' => esc_html__( 'Latest Blog Posts', 'briefcasewp-extras' ),
      'lead'   => esc_html__( 'We are so excited and proud of our plugin. It is really easy to create a landing page for your awesome product.', 'briefcasewp-extras' ),
    ] );


    $widget->panel( 'button', [
      'text' => esc_html__( 'View all', 'briefcasewp-extras' ),
      'outline' => true,
      'color' => 'btn-primary',
      'link' => bew_get_blog_posts_page_url(),
    ] );

    Bew_Controls::start_section( $widget, 'options', $id );

    $widget->add_control(
      't3_cat',
      Bew_Controls::option_select(
        esc_html__( 'Posts category', 'briefcasewp-extras' ),
        [],
        [
          'options' => $cat_options,
          'default' => '0',
        ]
      )
    );

    Bew_Controls::end_section( $widget );

  }



  public function html( $widget ) {
    $widget->set_id( self::ID );
    $settings = $widget->get_settings();

    $recent_posts = wp_get_recent_posts(array(
        'numberposts' => 3,
        'category' => intval( $settings['t3_cat'] ),
        'post_status' => 'publish'
    ));

    ?>
    <?php $widget->html('section_tag'); ?>
      <?php $widget->html('section_header'); ?>

        <div class="bew-row gap-y">
          
          <?php foreach( $recent_posts as $post ) : ?>
          <?php
            $post_id = $post['ID'];
            $url = get_permalink( $post_id );
            $content = '';
            if ( has_excerpt( $post_id ) ) {
              $content = get_the_excerpt( $post_id );
            }
            else {
              $extended = get_extended( get_post_field( 'post_content', $post_id ) );
              $content = $extended['main'];
            }
          ?>
            <div class="bew-col-12 bew-col-md-6 bew-col-lg-4">
              <div class="card card-bordered card-hover-shadow">
                <a href="<?php echo esc_url( $url ); ?>">
                <?php echo get_the_post_thumbnail( $post_id, 'bew-featured-image', [ 'class' => 'card-img-top' ] ); ?>
                </a>
                <div class="card-block">
                  <h4 class="card-title"><a href="<?php echo esc_url( $url ); ?>"><?php echo $post['post_title'] ?></a></h4>
                  <p class="card-text"><?php echo $content; ?></p>
                  <a class="fw-600 fs-12" href="<?php echo esc_url( $url ); ?>"><?php esc_html_e( 'Read more', 'briefcasewp-extras' ); ?> <i class="fa fa-chevron-right fs-9 pl-8"></i></a>
                </div>
              </div>
            </div>
        <?php endforeach; ?>

        </div>

        <br><br>
        <p class="text-center">
          <?php $widget->html('button'); ?>
        </p>

    </div></section>
    <?php
  }



  public function javascript( $widget ) {
    $widget->set_id( self::ID );


    $recent_posts = wp_get_recent_posts(array(
        'numberposts' => 3,
        'post_status' => 'publish'
    ));

    ?>
    <?php $widget->js('section_tag'); ?>
      <?php $widget->js('section_header'); ?>

        <div class="bew-row gap-y">
          
          <?php foreach( $recent_posts as $post ) : ?>
          <?php
            $post_id = $post['ID'];
            $url = get_permalink( $post_id );
            $content = '';
            if ( has_excerpt( $post_id ) ) {
              $content = get_the_excerpt( $post_id );
            }
            else {
              $extended = get_extended( get_post_field( 'post_content', $post_id ) );
              $content = $extended['main'];
            }
          ?>
            <div class="bew-col-12 bew-col-md-6 bew-col-lg-4">
              <div class="card card-bordered card-hover-shadow">
                <a href="<?php echo esc_url( $url ); ?>">
                <?php echo get_the_post_thumbnail( $post_id, 'bew-featured-image', [ 'class' => 'card-img-top' ] ); ?>
                </a>
                <div class="card-block">
                  <h4 class="card-title"><a href="<?php echo esc_url( $url ); ?>"><?php echo $post['post_title'] ?></a></h4>
                  <p class="card-text"><?php echo $content; ?></p>
                  <a class="fw-600 fs-12" href="<?php echo esc_url( $url ); ?>"><?php esc_html_e( 'Read more', 'briefcasewp-extras' ); ?> <i class="fa fa-chevron-right fs-9 pl-8"></i></a>
                </div>
              </div>
            </div>
        <?php endforeach; ?>

        </div>

        <br><br>
        <p class="text-center">
          <?php $widget->js('button'); ?>
        </p>

    </div></section>
    <?php
  }

}

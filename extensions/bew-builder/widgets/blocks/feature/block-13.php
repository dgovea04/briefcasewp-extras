<?php
namespace BriefcasewpExtras\Widgets;

use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class Bew_Feature_Block_13 {

  const ID = 13;

  public function controls( $widget ) {
    $widget->set_id( self::ID );
    $id = self::ID;

    $widget->panel( 'section', [
      'includes' => [ 'bg_gray' ],
    ] );

    $widget->panel( 'header_content', [
      'small'  => esc_html__( 'Features', 'bew-extras' ),
      'header' => esc_html__( 'Header Varieties', 'bew-extras' ),
      'lead'   => esc_html__( 'We waited until we could do it right. Then we did! Instead of creating a carbon copy.', 'bew-extras' ),
    ] );

    Bew_Controls::start_section( $widget, 'tabs', $id );
	
	$repeater = new Repeater();

	$repeater->add_control(
		'title', [
            'label' => esc_html__( 'Title', 'bew-extras' ),
            'type' => Controls_Manager::TEXT,
            'default' => esc_html__( 'Tab Title', 'bew-extras' ),
            'placeholder' => esc_html__( 'Tab Title', 'bew-extras' ),
            'label_block' => true,
		]
	);	
	$repeater->add_control(
		'subtitle', [
            'label' => esc_html__( 'Subtitle', 'bew-extras' ),
            'type' => Controls_Manager::TEXT,
            'placeholder' => esc_html__( 'Some description about the tab', 'bew-extras' ),
            'label_block' => true,
		]
	);	
	$repeater->add_control(
		'content', [
            'label' => esc_html__( 'Content', 'bew-extras' ),
            'default' => esc_html__( 'Tab Content', 'bew-extras' ),
            'placeholder' => esc_html__( 'Tab Content', 'bew-extras' ),
            'type' => Controls_Manager::WYSIWYG,
            'show_label' => false,
		]
	);	

    $widget->add_control(
      't'. $id .'_tabs',
      [
        'label' => esc_html__( 'Tabs Items', 'bew-extras' ),
        'type' => Controls_Manager::REPEATER,
        'default' => [
          [
            'title' => esc_html__( 'Color', 'bew-extras' ),
            'subtitle' => esc_html__( 'Some description about the current tab.', 'bew-extras' ),
            'content' => '<img src="'. esc_url( bew_get_img_uri( 'header-bew-1.png' ) ) .'" alt="header bew 1">',
          ],
          [
            'title' => esc_html__( 'Gradient', 'bew-extras' ),
            'subtitle' => esc_html__( 'Some description about the current tab.', 'bew-extras' ),
            'content' => '<img src="'. esc_url( bew_get_img_uri( 'header-bew-3.png' ) ) .'" alt="header bew 3">',
          ],
          [
            'title' => esc_html__( 'Typing', 'bew-extras' ),
            'subtitle' => esc_html__( 'Some description about the current tab.', 'bew-extras' ),
            'content' => '<img src="'. esc_url( bew_get_img_uri( 'header-bew-4.png' ) ) .'" alt="header bew 4">',
          ],
          [
            'title' => esc_html__( 'Slider', 'bew-extras' ),
            'subtitle' => esc_html__( 'Some description about the current tab.', 'bew-extras' ),
            'content' => '<img src="'. esc_url( bew_get_img_uri( 'header-bew-5.png' ) ) .'" alt="header bew 5">',
          ],
        ],
		'fields' => $repeater->get_controls(),
        'title_field' => '{{{ title }}}',
        'separator' => 'before',
      ]
    );

    Bew_Controls::add_uniqid( $widget, $id );

    Bew_Controls::end_section( $widget );

  }

  public function html( $widget ) {
    $widget->set_id( self::ID );
    $settings = $widget->get_settings();
    $id = $settings['t13_uniqid'] . rand();
    $counter = 0;
    ?>
    <?php $widget->html('section_tag', [ 'class', 'hidden-sm-down' ]); ?>
      <?php $widget->html('section_header'); ?>

            <div class="bew-row gap-5">

              <div class="bew-col-12 bew-col-md-4">
                <ul class="bew-nav bew-nav-vertical">

                <?php foreach ( $settings['t13_tabs'] as $item ) : $counter++; ?>

                  <?php if ( 1 == $counter ) : ?>

                    <li class="bew-nav-item">
                      <a class="bew-nav-link active" data-toggle="tab" href="#tab-<?php echo $id; ?>-<?php echo $counter; ?>">
                        <h6><?php echo $item['title']; ?></h6>
                        <p><?php echo $item['subtitle']; ?></p>
                      </a>
                    </li>

                  <?php else : ?>

                    <li class="bew-nav-item">
                      <a class="bew-nav-link" data-toggle="tab" href="#tab-<?php echo $id; ?>-<?php echo $counter; ?>">
                        <h6><?php echo $item['title']; ?></h6>
                        <p><?php echo $item['subtitle']; ?></p>
                      </a>
                    </li>

                  <?php endif; ?>

                <?php endforeach; $counter = 0; ?>

                </ul>
              </div>


              <div class="bew-col-12 bew-col-md-8 align-self-center">
                <div class="tab-content">
                <?php foreach ( $settings['t13_tabs'] as $item ) : $counter++; ?>

                  <?php if ( 1 == $counter ) : ?>

                    <div class="tab-pane fade show active" id="tab-<?php echo $id; ?>-<?php echo $counter; ?>">
                      <?php echo $item['content']; ?>
                    </div>

                  <?php else : ?>

                    <div class="tab-pane fade" id="tab-<?php echo $id; ?>-<?php echo $counter; ?>">
                      <?php echo $item['content']; ?>
                    </div>

                  <?php endif; ?>

                <?php endforeach; ?>
                </div>
              </div>

            </div>

    </div></section>
    <?php
  }



  public function javascript( $widget ) {
    $widget->set_id( self::ID );
    ?>
    <?php $widget->js('section_tag', [ 'class' => 'hidden-sm-down' ]); ?>
      <?php $widget->js('section_header'); ?>
          <#
            var counter = 0;
            var id = settings.t13_uniqid;
          #>
            <div class="bew-row gap-5">

              <div class="bew-col-12 bew-col-md-4">
                <div class="bew-nav bew-nav-vertical">
                <# _.each( settings.t13_tabs, function( item ) { counter++; #>

                  <# if ( 1 == counter ) { #>
					<li class="bew-nav-item">
						<a class="bew-nav-link active" data-toggle="tab" href="#tab-{{ id }}-{{ counter }}">
						  <h6>{{{ item.title }}}</h6>
						  <p>{{{ item.subtitle }}}</p>
						</a>
					</li>

                  <# } else { #>
					<li class="bew-nav-item">
						<a class="bew-nav-link" data-toggle="tab" href="#tab-{{ id }}-{{ counter }}">
						  <h6>{{{ item.title }}}</h6>
						  <p>{{{ item.subtitle }}}</p>
						</a>
					</li>

                  <# } #>

                <# } ); counter = 0; #>
                </div>
              </div>


              <div class="bew-col-12 bew-col-md-8 align-self-center">
                <div class="tab-content">
                  <# _.each( settings.t13_tabs, function( item ) { counter++; #>

                    <# if ( 1 == counter ) { #>

                      <div class="tab-pane fade show active" id="tab-{{ id }}-{{ counter }}">
                        {{{ item.content }}}
                      </div>

                    <# } else { #>

                      <div class="tab-pane fade" id="tab-{{ id }}-{{ counter }}">
                        {{{ item.content }}}
                      </div>

                    <# } #>

                  <# } ); #>
                </div>
              </div>

            </div>

    </div></section>
    <?php
  }

}

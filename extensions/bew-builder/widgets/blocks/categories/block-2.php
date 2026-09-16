<?php
namespace BriefcasewpExtras\Widgets;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class Bew_Categories_Block_2 {

  const ID = 2;

  public function controls( $widget ) {
    $widget->set_id( self::ID );
    $id = self::ID;

    $widget->panel( 'section', [
      'includes' => [ 'bg_gray' ],
    ] );

    $widget->panel( 'header_content', [
      'small'  => esc_html__( 'Products', 'briefcasewp-extras' ),
      'header' => esc_html__( 'Categories', 'briefcasewp-extras' ),
      'lead'   => esc_html__( 'Ready to find our collections.', 'briefcasewp-extras' ),
    ] );

    Bew_Controls::start_section( $widget, 'categories', $id );
	Bew_Controls::add_font_size( $widget, $id, [ 'default' => 24, 'default_unit' => 'px' , 'selectors' => 'h5' ] );

    $widget->add_control(
      't'. $id . '_columns',
      Bew_Controls::option_slider( esc_html__( 'Columns', 'briefcasewp-extras' ), [], [
        'min'  => 2,
        'max'  => 4,
        'default' => 3,
      ] )
    );
	
	$widget->add_control(
		't'. $id .'cat_hover_bg_color',
		[
			'label' => esc_html__( 'Background color', 'briefcasewp-extras' ),
			'type' => Controls_Manager::COLOR,
			'default' => '#ffffff',
			'selectors' => [
					'{{WRAPPER}} .categories-'. $id .'::before ' => 'background-color: {{VALUE}};',					
				],
		]
	);

	Bew_Controls::add_categories( $widget, $id, [ 'button' => "yes" , 'button_text' => "Shop Now" ]);

    Bew_Controls::end_section( $widget );
  }



  public function html( $widget ) {
    $widget->set_id( self::ID );
    $settings = $widget->get_settings();

    $cols = $settings['t2_columns']['size'];
    $col_class = 'bew-col-12';
    switch ( $cols ) {
      case 1:
        $col_class = 'bew-col-12';
        break;

      case 2:
        $col_class = 'bew-col-12 bew-col-md-6';
        break;

      case 3:
        $col_class = 'bew-col-12 bew-col-lg-4';
        break;

      case 4:
        $col_class = 'bew-col-6 bew-col-lg-3';
        break;

      default:
        $col_class = 'bew-col-12 bew-col-md-6';
        break;
    }

    ?>
    <?php $widget->html('section_tag'); ?>
      <?php $widget->html('section_header'); ?>
     
        <div class="bew-row gap-y gap-2">
    
          <?php
          foreach ( $settings['t2_items'] as $item ) :

            $title = $item['title'];            
            $image = !empty( $item['image']['url'] ) ? $item['image']['url'] : bew_get_img_uri( 'cat-placeholder.jpg' );
            $link  = $item['link'];			
			$button = $item['button']; 
            if ( '0' !== $item['cat'] ) {
              $id = intval( $item['cat'] );              
			  $category = get_term_by( 'id', $id, 'product_cat' );			  
              
              $title = $category->name;
			  $thumbnail_id = get_woocommerce_term_meta( $id, 'thumbnail_id', true );
			  if(!empty($thumbnail_id)){
				$image = wp_get_attachment_url( $thumbnail_id );  
			  }else{
				$image = wc_placeholder_img_src();  
			  }
			  
              $link = get_category_link( $category->term_id );			 
                            
            }
          ?>
            
             <div class="cat-block <?php echo $col_class; ?>">
                      
              <a class="categories-2" href="<?php echo esc_url( $link ); ?>">
                <img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $title ); ?>">
                <div class="categories-details">
                  <h5><?php echo $title;?></h5> 
				  <span class="categories-button"><?php echo $button ?></span> 	
                </div>
              </a>
            </div>

          <?php endforeach; ?>

    </div></section>
    <?php
  }



  public function javascript( $widget ) {
    $widget->set_id( self::ID );

    ?>

    <?php $widget->js('section_tag'); ?>
      <?php $widget->js('section_header'); ?>

        <p class="text-center">
          <?php esc_html_e( 'You\'ll see categories items after saving and reloading the page.', 'briefcasewp-extras' ); ?>
        </p>

    </div></section>
    <?php
  }

}

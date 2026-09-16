<?php
namespace BriefcasewpExtras\Widgets;

use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class Bew_Categories_Block_3 {

  const ID = 3;

  public function controls( $widget ) {
    $widget->set_id( self::ID );
    $id = self::ID;

    $widget->panel( 'section', [
      'includes' => [ 'bg_gray' ],
    ] );

    $widget->panel( 'header_content', [
      'small'  => esc_html__( 'Products', 'bew-extras' ),
      'header' => esc_html__( 'Categories', 'bew-extras' ),
      'lead'   => esc_html__( 'Ready to find our collections.', 'bew-extras' ),
    ] );

    Bew_Controls::start_section( $widget, 'categories', $id );	
	Bew_Controls::add_categories_height( $widget, $id, [ 'default' => 880, 'mobile_default' => 320, 'default_unit' => 'px' , 'selectors' => '.cat-block-2-columns' ] );	
	Bew_Controls::add_font_size( $widget, $id, [ 'default' => 24, 'default_unit' => 'px' , 'selectors' => 'h5' ] );
	
	$widget->add_control(
		't'. $id .'cat_hover_bg_color',
		[
			'label' => esc_html__( 'Background color', 'bew-extras' ),
			'type' => Controls_Manager::COLOR,
			'default' => '#ffffff',
			'selectors' => [
					'{{WRAPPER}} .categories-'. $id .'::before ' => 'background-color: {{VALUE}};',					
				],
		]
	);

	Bew_Controls::add_categories( $widget, $id, [
		'default' => [
			[
				'title' => esc_html__( 'Category 1', 'bew-extras' ),
				'image' => [ 'url' => bew_get_img_uri( 'category-1.jpg' ) ],
				'count' => '',
				'link' => '#',
				'height' => '',
			],
			[
				'title' => esc_html__( 'Category 2', 'bew-extras' ),
				'image' => [ 'url' => bew_get_img_uri( 'category-2.jpg' ) ],
				'count' => '',
				'link' => '#',
				'height' => '',
			],
			[
				'title' => esc_html__( 'Category 3', 'bew-extras' ),
				'image' => [ 'url' => bew_get_img_uri( 'category-3.jpg' ) ],
				'count' => '',
				'link' => '#',
				'height' => '',
			],
			[
				'title' => esc_html__( 'Category 4', 'bew-extras' ),
				'image' => [ 'url' => bew_get_img_uri( 'category-4a.jpg' ) ],
				'count' => '',
				'link' => '#',
				'height' => '',
			],
			[
				'title' => esc_html__( 'Category 5', 'bew-extras' ),
				'image' => [ 'url' => bew_get_img_uri( 'category-5.jpg' ) ],
				'count' => '',
				'link' => '#',
				'height' => '',
			],      
		],
		'count' => "yes",
		'height' => "yes"
	] );
    Bew_Controls::end_section( $widget );
  }



  public function html( $widget ) {
    $widget->set_id( self::ID );
    $settings = $widget->get_settings();

    ?>
    <?php $widget->html('section_tag'); ?>
      <?php $widget->html('section_header'); ?>
	  
	  
     
        <div class="bew-row gap-y gap-2 categories-block-3 cat-block-2-columns">
    
          <?php
		  $count = 1;
		  
          foreach ( $settings['t3_items'] as $item ) :

            $title = $item['title'];			
            $image = !empty( $item['image']['url'] ) ? $item['image']['url'] : bew_get_img_uri( 'cat-placeholder.jpg' );			
            $link  = $item['link'];
			$height  = $item['height'];
			$product_count = '<p class="product-count">' .$item['count'] .'</p>';
			$cat_number = 'cat-block' . $count; 	
									
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

			  $product_count = sprintf( // WPCS: XSS OK.
				/* translators: 1: number of products */
				_nx( '<p class="product-count">%1$s Product</p>', '<p class="product-count">%1$s Products</p>', $category->count, 'product categories', 'bew-extras' ),
				number_format_i18n( $category->count )
				);
                            
            }
          
			if ( 1 == $count ) {
			?>
			<div class="block-column-1 bew-col-12 bew-col-md-6">
			<?php
			}
			if ( 4 == $count ) {
			?>
			</div>
			<div class="block-column-2 bew-col-12 bew-col-md-6">
			<?php
			}
            ?>
             <div class="elementor-repeater-item-<?php echo $item['_id']; ?> cat-block <?php echo $cat_number; ?>">
                    
              <a class="categories-3" href="<?php echo esc_url( $link ); ?>">                
                <div class="bew-categories-bg bew-bg" style="background-image: url(<?php echo $image; ?>);"></div> 				
				<div class="categories-details">
                  <h5><?php echo $title;?></h5> 
				  <?php echo $product_count ?> 	
                </div>
              </a>
            </div>
			<?php
			if ( 5 == $count ) {
			?>
			</div>			
			<?php
			}
			$count++;
		  endforeach; ?>

    </div></section>
    <?php
  }

  public function javascript( $widget ) {
    $widget->set_id( self::ID );	
    ?>

    <?php $widget->js('section_tag'); ?>
    <?php $widget->js('section_header'); ?>
 
	<div class="bew-row gap-y gap-2 categories-block-3 cat-block-2-columns">
	
	<#
		var count = 1;
		  
		_.each( settings.t3_items, function( item ) {

            var title = item.title;            
            var image = item.image.url;
            var link  = item.link;			
			var product_count = item.count;
			var cat_number = 'cat-block' + count; 
			var cat_item = item._id;	 
	#>
		
		
		<#	if ( '0' !== item.cat) { 
            var id = item.cat; 
	  	#>
			<?php
			$id = 1;
						
			$category = get_term_by( 'id', $id, 'product_cat' ); ?>
			
			<#  var title = '<?php echo $category->name; ?>' #>	
			
			<?php $thumbnail_id = get_woocommerce_term_meta( $id, 'thumbnail_id', true );
			
			if(!empty($thumbnail_id)){	
					$image = wp_get_attachment_url( $thumbnail_id );  
				  }else{
					$image = wc_placeholder_img_src();  
				  }
			?>
			<#  var image = '<?php echo $image; ?>' #>
			
			<?php $link = get_category_link( $category->term_id );

				  $product_count = sprintf( // WPCS: XSS OK.
					/* translators: 1: number of products */
					_nx( '<p class="product-count">%1$s Product</p>', '<p class="product-count">%1$s Products</p>', $category->count, 'product categories', 'bew-extras' ),
					number_format_i18n( $category->count )
					);
			?> 
			<# 	var link = '<?php echo $link; ?>' #>
			<#  var product_count = '<?php echo $product_count; ?>' #>			
			
        <# } #>	
	
			<# if ( 1 == count ) { #>
				<div class="block-column-1  bew-col-12 bew-col-md-6">			
			<# } #>
			
			<# if ( 4 == count ) { #>
				</div>
				<div class="block-column-2  bew-col-12 bew-col-md-6">
			<# } #>

				<div class="elementor-repeater-item-{{cat_item}} cat-block {{cat_number}}">			
				
				<a class="categories-3" href="{{link }}">					
					<div class="bew-categories-bg bew-bg" style="background-image: url({{image}});"></div>
					<div class="categories-details">
					  <h5>{{{title}}}</h5> 
					  <p class="product-count">{{{product_count}}}</p>	
					</div>
				</a>
				</div>
			<# if ( 5 == count ) { #>
				</div>
			<# } #>
		
		<#	count++ #>
		<#
		} );
		#>

    </div></section>
    <?php
  }

}

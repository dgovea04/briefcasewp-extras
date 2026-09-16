<?php
namespace BriefcasewpExtras\Widgets;

use Elementor\Controls_Manager;
use Elementor\Repeater;	

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class Bew_Categories_Block_4 {

  const ID = 4;

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
	Bew_Controls::add_categories_height( $widget, $id, [ 'default' => 580, 'default_unit' => 'px' , 'selectors' => '.cat-block-2-columns' ] );	
	Bew_Controls::add_font_size( $widget, $id, [ 'default' => 24, 'default_unit' => 'px' , 'selectors' => 'h5' ] );
	
	$widget->add_control(
		't'. $id .'cat_hover_bg_color',
		[
			'label' => esc_html__( 'Background color', 'bew-extras' ),
			'type' => Controls_Manager::COLOR,
			'default' => 'rgba(255,153,0,0)',
			'selectors' => [
					'{{WRAPPER}} .categories-'. $id .'::before ' => 'background-color: {{VALUE}};',					
				],
		]
	);

    $categories = get_terms( 'product_cat' );

	$options = [];
	$options[0] = esc_html__( 'None', 'bew-extras' );
	foreach ( $categories as $category ) {
		$options[ $category->term_id ] = $category->name;
	}
	    
    $items = [
      [
        'title' => __( 'New <br> Arrivals', 'bew-extras' ),
        'image' => [ 'url' => bew_get_img_uri( 'category-1a.jpg' ) ],
        'button' => 'Explore Now',
        'link' => '#',
      ],
      [
        'title' => esc_html__( 'Category 1', 'bew-extras' ),
        'image' => [ 'url' => bew_get_img_uri( 'category-2.jpg' ) ],
        'button' => 'Shop Now',
        'link' => '#',
      ],
      [
        'title' => esc_html__( 'Category 2', 'bew-extras' ),
        'image' => [ 'url' => bew_get_img_uri( 'category-3.jpg' ) ],
        'button' => 'Shop Now',
        'link' => '#',
      ],
      [
        'title' => esc_html__( 'Category 3', 'bew-extras' ),
        'image' => [ 'url' => bew_get_img_uri( 'category-4.jpg' ) ],
        'button' => 'Shop Now',
        'link' => '#',
      ],
      [
        'title' => esc_html__( 'Category 4', 'bew-extras' ),
        'image' => [ 'url' => bew_get_img_uri( 'category-5.jpg' ) ],
        'button' => 'Shop Now',
        'link' => '#',
      ],
      [
        'title' => esc_html__( 'Category 5', 'bew-extras' ),
        'image' => [ 'url' => bew_get_img_uri( 'category-6.jpg' ) ],
        'button' => 'Shop Now',
        'link' => '#',
      ],      
    ];
	
	$repeater = new Repeater();
	
	$repeater->add_control(
		'cat', 
			[
			'name' => 'cat',
            'label' => esc_html__( 'Select a category', 'bew-extras' ),
            'type' => Controls_Manager::SELECT,
            'label_block' => true,
            'default' => '0',
            'label' => esc_html__( 'Select None to insert manually', 'bew-extras' ),
            'description' => '<a href="'. admin_url() .'edit-tags.php?taxonomy=product_cat&post_type=product" target="_blank">'. esc_html__( 'Add new category', 'bew-extras' ) .'</a>',
            'options' => $options,
			]
	);
		
	$repeater->add_control(
		'title',
			[
				'label' => esc_html__( 'Title', 'bew-extras' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'condition' => [
				  'cat' => '0',
				],
			]
	);
		
	$repeater->add_control(
		'link',
			[
				'label' => esc_html__( 'Link', 'bew-extras' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'condition' => [
				  'cat' => '0',
				],
			]
	);
		
	$repeater->add_control(
		'image',
			[
				'label' => esc_html__( 'Image', 'bew-extras' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
				  'url' => bew_get_img_uri( 'cat-placeholder.jpg' ),
				],
				'condition' => [
				  'cat' => '0',
				],
			]
	);
		
	$repeater->add_control(
		'count',
			[
				'label' => esc_html__( 'Count', 'bew-extras' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'condition' => [
				  'cat' => '0',
				],
			]
	);

	$repeater->add_responsive_control(
		'height',
			[
				'label' => esc_html__( 'Height', 'bew-extras' ),
				'type' => Controls_Manager::SLIDER,
				'label_block' => true, 
				'default'     => [
					'unit' => '%',
				],	
				'tablet_default' => [
					'unit' => '%',
				],
				'mobile_default' => [
					'unit' => '%',
				],
				'size_units' => [ '%', 'px' ],
				'range' => [
					'%' => [
						'min'  => 1,
						'max'  => 100,
						'step' => 1,
					],
					'px' => [
						'min' => 1,
						'max' => 1000,
					],
					'vw' => [
						'min' => 1,
						'max' => 100,
					],
				],			
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}.cat-block, {{WRAPPER}} {{CURRENT_ITEM}}.swiper-slide' => 'min-height: calc({{SIZE}}{{UNIT}} - 10px);',
				],				
			]
	);

    $widget->add_control(
      't'. $id .'_items',
      [
        'label' => esc_html__( 'Items', 'bew-extras' ),
        'type' => Controls_Manager::REPEATER,
        'default' => $items,
        'fields' => $repeater->get_controls(),
        'title_field' => '{{{ title }}}',
        'separator' => isset( $arg['separator'] ) ? 'before' : 'default',
      ]
    );

    Bew_Controls::end_section( $widget );
  }



  public function html( $widget ) {
    $widget->set_id( self::ID );
    $settings = $widget->get_settings();

    ?>
    <?php $widget->html('section_tag'); ?>
      <?php $widget->html('section_header'); ?>
     
        <div class="bew-row gap-y gap-2 categories-block-4 cat-block-2-columns">
    
          <?php
		  $count = 1;
		  
          foreach ( $settings['t4_items'] as $item ) :

            $title = $item['title'];            
            $image = !empty( $item['image']['url'] ) ? $item['image']['url'] : bew_get_img_uri( 'cat-placeholder.jpg' );
            $link  = $item['link'];
			$button = $item['button']; 
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
			  			             
            }
			
			if ( 1 == $count ) {
			?>
			<div class="block-column-1 col-12 col-lg-4">
			<?php
			}
			if ( 2 == $count ) {
			?>
			</div>
			<div class="block-column-2 col-12 col-lg-8">
			<?php
			}
			?>
                         
             <div class="elementor-repeater-item-<?php echo $item['_id']; ?> cat-block <?php echo $cat_number; ?>">
			 
              <a class="categories-4" href="<?php echo esc_url( $link ); ?>">
                <div class="bew-categories-bg bew-bg" style="background-image: url(<?php echo $image; ?>);"></div> 
                <div class="categories-details">
                  <h5><?php echo $title;?></h5> 
				  <span class="categories-button"><?php echo $button ?></span> 	
                </div>
              </a>
            </div>
			<?php
			if ( 6 == $count ) {
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

        <p class="text-center">
          <?php esc_html_e( 'You\'ll see categories items after saving and reloading the page.', 'bew-extras' ); ?>
        </p>

    </div></section>
    <?php
  }

}

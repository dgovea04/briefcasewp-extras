<?php
namespace BriefcasewpExtras\Widgets;

use Elementor;
use Elementor\Element_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class Bew_Product_Tabs_Block_4 {

  const ID = 4;

  public function controls( $widget ) {
    $widget->set_id( self::ID );
    $id = self::ID;
	
	$categories = get_terms( 'product_cat' );

	$cat_options = [];
	foreach ( $categories as $category ) {
		$cat_options[ $category->term_id ] = $category->name;
	}

    $widget->panel( 'section', [
      'includes' => [ 'bg_gray' ],
      'bg_gray' => true,
    ] );

    $widget->panel( 'header_content', [
      'small'  => esc_html__( 'News', 'briefcasewp-extras' ),
      'header' => esc_html__( 'Product Tabs', 'briefcasewp-extras' ),
      'lead'   => esc_html__( 'Show product categories on a Ajax tab filter.', 'briefcasewp-extras' ),
    ] );

    Bew_Controls::start_section( $widget, 'options', $id );
	
	$widget->add_responsive_control(
      't'. $id .'_count',
      Bew_Controls::option_number_responsive(
        esc_html__( 'Elements Per Page', 'briefcasewp-extras' ),
        [],
        [
			'min' => 1,
			'max' => 12,
			'default' => 8,
		]	
		
      )
    );
	
	$widget->add_responsive_control(
      't'. $id .'_columns',
      Bew_Controls::option_number_responsive(
        esc_html__( 'Grid Columns', 'briefcasewp-extras' ),
        [],
		[
			'min' => 1,
			'max' => 12,
			'default' => 4,
		]
      )
    );	
    
	$widget->add_control(
      't'. $id .'_group_by',
      Bew_Controls::option_select(
        esc_html__( 'Group by', 'briefcasewp-extras' ),
        [],
        [
          'options' => [
				'categories' => __( 'Categories', 'briefcasewp-extras' ),
				'features' => __( 'Features', 'briefcasewp-extras' ),
		  ],
          'default' => 'categories',
        ]
      )
    );
	
	$widget->add_control(
      't'. $id .'_hide_empty',
      Bew_Controls::option_switch( esc_html__( 'Hide Empty', 'briefcasewp-extras' ), [], [
        'return' => 'yes',
      ] )
    );
	
	$widget->add_control(
      't'. $id .'_only_top_level',
      Bew_Controls::option_switch( esc_html__( 'Only Top Level', 'briefcasewp-extras' ), [], [
        'return' => 'yes',
      ] )
    );
			
    $widget->add_control(
      't'. $id .'_order',
      Bew_Controls::option_select(
        esc_html__( 'Order', 'briefcasewp-extras' ),
        [],
        [
          'options' 		=> [
				'' 			=> __( 'Default', 'briefcasewp-extras' ),
				'DESC' 		=> __( 'DESC', 'briefcasewp-extras' ),
				'ASC' 		=> __( 'ASC', 'briefcasewp-extras' ),
		  ],
          'default' => '',
        ]
      )
    );
	
    $widget->add_control(
      't'. $id .'_order_by',
      Bew_Controls::option_select(
        esc_html__( 'Order by', 'briefcasewp-extras' ),
        [],
        [
          'options' => [
				'name' => __( 'Name', 'briefcasewp-extras' ),
				'slug' => __( 'Slug', 'briefcasewp-extras' ),
				'description' => __( 'Description', 'briefcasewp-extras' ),
				'count' => __( 'Count', 'briefcasewp-extras' ),
		  ],
          'default' => 'name',
        ]
      )
    );
	
	$widget->add_control(
      't'. $id .'_include',
      Bew_Controls::option_select2(
        esc_html__( 'Include Categories', 'briefcasewp-extras' ),
        [],
        [
          'description' => __( 'You can select multiples categories', 'briefcasewp-extras' ),
		  'options' => $cat_options,
		  'default' => [],
		  'label_block' => true,
		  'multiple' => true,
        ]
      )
    );

	$widget->add_control(
      't'. $id .'_exclude',
      Bew_Controls::option_select2(
        esc_html__( 'Exclude Categories', 'briefcasewp-extras' ),
        [],
        [
          'description' => __( 'You can select multiples categories', 'briefcasewp-extras' ),
		  'options' => $cat_options,
		  'default' => [],
		  'label_block' => true,
		  'multiple' => true,
        ]
      )
    );
		
	$widget->add_control(
      't'. $id .'_load_more',
      Bew_Controls::option_switch( esc_html__( 'Load More', 'briefcasewp-extras' ), [], [
        'return' => 'yes',
      ] )
    );


    Bew_Controls::end_section( $widget );

  }
  
  function get_shortcode($settings) {
		
	
	$per_page    = $settings['t4_count'];
	$columns     = $settings['t4_columns'];
	$include     = !empty($settings['t4_include'] ) ? implode( ',', $settings['t4_include'] ) : '';
	$exclude     = !empty($settings['t4_exclude'] ) ? implode( ',', $settings['t4_exclude'] ) : '';
	$hide_empty  = ( $settings['t4_hide_empty'] == 'yes' ) ? 1 : 0;	
	$parent      = $settings['t4_only_top_level'];
	$order       = $settings['t4_order'];
	$orderby     = $settings['t4_order_by'];
	
	$load_more   = ( $settings['t4_load_more'] == 'yes' ) ? true : false;	
	
	$render_attributes = [		
		'per_page'      => $per_page,
		'columns'       => $columns,
		'filter'        => 'category',
		'filter_type'   => 'ajax',
		'category'      => '',
		'hide_empty'    => $hide_empty,
		'parent'      	=> $parent,
		'order'      	=> $order,
		'orderby'       => $orderby,
		'exclude'       => $exclude,
		'exclude_tree'  => $exclude, 
		'include'       => $include,
		'load_more'     => $load_more,
	];
	
	$attributes = [];

		foreach ( $render_attributes as $attribute_key => $attribute_values ) {
			$attributes[] = sprintf( '%1$s="%2$s"', $attribute_key, esc_attr( $attribute_values ) );
		}		
		
		$attributes = implode( ' ', $attributes );
			
	$shortcode = sprintf( '[bew_product_tabs %s]', $attributes );
		
	return $shortcode;
  }
  
  function woocommerce_template_loop_bew_add_to_cart() {
		
		// Extra step to remove add to cart button for Astra theme
		add_filter( 'astra_woo_shop_product_structure', function($shop_structure) {
			$shop_structure = '';
			return $shop_structure;
		});
		
		
        // Add Our block 		
		?> <div class="bew-add-to-cart-block-4">  <?php
		woocommerce_template_loop_product_title();
		woocommerce_template_loop_price();
		woocommerce_template_loop_add_to_cart();
		?> </div> <?php
	
  }

  public function html( $widget ) {
    $widget->set_id( self::ID );
    $settings = $widget->get_settings();
	
    /** woocommerce: change position of add-to-cart on loop **/
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
	remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );	
	remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
	remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
	remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
	remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
	
	add_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
	//add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_add_to_cart', 10 );
	add_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_thumbnail', 10 );
	add_action( 'woocommerce_before_shop_loop_item',[ $this, 'woocommerce_template_loop_bew_add_to_cart'] , 10 );	
	add_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_close', 10);
		
    ?>
    <?php $widget->html('section_tag'); ?>
      <?php $widget->html('section_header'); ?>

        <div class="bew-row gap-y text-center bew-shop-block bew-shop-block-4" data-block="4">          
		<?php echo do_shortcode( $this->get_shortcode($settings) ); ?>	

        </div>

    </div></section>
    <?php
  }

  public function javascript( $widget ) {
    $widget->set_id( self::ID );
	 
	?>	
    <?php $widget->js('section_tag'); ?>
      <?php $widget->js('section_header'); ?>

        <div class="bew-row gap-y text-center bew-shop-block bew-shop-block-4" data-block="4">   
         <?php echo do_shortcode( $this->get_shortcode($settings) ); ?>

        </div>

    </div></section>
    <?php
  }

}

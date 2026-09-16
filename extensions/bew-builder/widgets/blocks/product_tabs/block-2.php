<?php
namespace BriefcasewpExtras\Widgets;

use Elementor;
use Elementor\Element_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class Bew_Product_Tabs_Block_2 {

  const ID = 2;

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
	
	Bew_Controls::start_section_style( $widget, 'Header', $id );
	Bew_Controls::add_heading_typo_header( $widget, $id, ['selectors' => 'h2' ] );
	Bew_Controls::add_color( $widget, $id, ['name' => 'heading', 'selectors' => 'h2' ] );
	Bew_Controls::end_section( $widget );
		
	Bew_Controls::start_section_style( $widget, 'Content', $id );
	Bew_Controls::add_heading( $widget, 'Title', $id );
	Bew_Controls::add_heading_typo_content( $widget, $id, ['name' => 'title', 'selectors' => ' .bew-shop-block-2 .woocommerce ul.products li.product .woocommerce-loop-product__title' ] );
	Bew_Controls::add_color( $widget, $id, ['name' => 'title', 'selectors' => ' .bew-shop-block-2 .woocommerce ul.products li.product .woocommerce-loop-product__title' ] );
	Bew_Controls::add_heading( $widget, 'Price', $id );
	Bew_Controls::add_heading_typo_content( $widget, $id, ['name' => 'price', 'selectors' => ' .bew-shop-block-2 .woocommerce ul.products li.product .price' ] );
	Bew_Controls::add_color( $widget, $id, ['name' => 'price', 'selectors' => ' .bew-shop-block-2 .woocommerce ul.products li.product .price' ] );
	Bew_Controls::add_heading( $widget, 'Add To Cart', $id );
	Bew_Controls::add_heading_typo_content( $widget, $id, ['name' => 'add_to_cart', 'selectors' => ' .bew-shop-block-2 .woocommerce ul.products li.product a.button' ] );
	Bew_Controls::add_color_and_bg_btn( $widget, $id, ['selectors' => ' .bew-shop-block-2 .woocommerce ul.products li.product a.button' ] );
	Bew_Controls::add_border( $widget, $id, ['selectors' => ' .bew-shop-block-2 .woocommerce ul.products li.product a.button' ] );
	Bew_Controls::add_border_radius( $widget, $id, ['selectors' => ' .bew-shop-block-2 .woocommerce ul.products li.product a.button' ] );
	Bew_Controls::add_padding( $widget, $id, ['selectors' => ' .bew-shop-block-2 .woocommerce ul.products li.product a.button' ] );
	Bew_Controls::add_margin( $widget, $id, ['selectors' => ' .bew-shop-block-2 .woocommerce ul.products li.product a.button' ] );	
	Bew_Controls::end_section( $widget );

  }
  
  function get_shortcode($settings) {
		
	
	$per_page    = $settings['t2_count'];
	$columns     = $settings['t2_columns'];
	$include     = !empty($settings['t2_include'] ) ? implode( ',', $settings['t2_include'] ) : '';
	$exclude     = !empty($settings['t2_exclude'] ) ? implode( ',', $settings['t2_exclude'] ) : '';
	$hide_empty  = ( $settings['t2_hide_empty'] == 'yes' ) ? 1 : 0;	
	$parent      = $settings['t2_only_top_level'];
	$order       = $settings['t2_order'];
	$orderby     = $settings['t2_order_by'];
	
	$load_more   = ( $settings['t2_load_more'] == 'yes' ) ? true : false;	
	
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

  public function html( $widget ) {
    $widget->set_id( self::ID );
    $settings = $widget->get_settings();
		
    ?>
    <?php $widget->html('section_tag'); ?>
      <?php $widget->html('section_header'); ?>

        <div class="bew-row gap-y text-center bew-shop-block-2">          
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

        <div class="bew-row gap-y text-center bew-shop-block-2">  
         <?php echo do_shortcode( $this->get_shortcode($settings) ); ?>

        </div>

    </div></section>
    <?php
  }

}

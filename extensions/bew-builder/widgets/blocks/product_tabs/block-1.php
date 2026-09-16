<?php
namespace BriefcasewpExtras\Widgets;

use Elementor;
use Elementor\Element_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class Bew_Product_Tabs_Block_1 {

  const ID = 1;

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
      'small'  => esc_html__( 'News', 'bew-extras' ),
      'header' => esc_html__( 'Product Tabs', 'bew-extras' ),
      'lead'   => esc_html__( 'Show product categories on a Ajax tab filter.', 'bew-extras' ),
    ] );

    Bew_Controls::start_section( $widget, 'options', $id );
	
	$widget->add_responsive_control(
      't'. $id .'_count',
      Bew_Controls::option_number_responsive(
        esc_html__( 'Elements Per Page', 'bew-extras' ),
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
        esc_html__( 'Grid Columns', 'bew-extras' ),
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
        esc_html__( 'Group by', 'bew-extras' ),
        [],
        [
          'options' => [
				'categories' => __( 'Categories', 'bew-extras' ),
				'features' => __( 'Features', 'bew-extras' ),
		  ],
          'default' => 'categories',
        ]
      )
    );
	
	$widget->add_control(
      't'. $id .'_hide_empty',
      Bew_Controls::option_switch( esc_html__( 'Hide Empty', 'bew-extras' ), [], [
        'return' => 'yes',
      ] )
    );
	
	$widget->add_control(
      't'. $id .'_only_top_level',
      Bew_Controls::option_switch( esc_html__( 'Only Top Level', 'bew-extras' ), [], [
        'return' => 'yes',
      ] )
    );
			
    $widget->add_control(
      't'. $id .'_order',
      Bew_Controls::option_select(
        esc_html__( 'Order', 'bew-extras' ),
        [],
        [
          'options' 		=> [
				'' 			=> __( 'Default', 'bew-extras' ),
				'DESC' 		=> __( 'DESC', 'bew-extras' ),
				'ASC' 		=> __( 'ASC', 'bew-extras' ),
		  ],
          'default' => '',
        ]
      )
    );
	
    $widget->add_control(
      't'. $id .'_order_by',
      Bew_Controls::option_select(
        esc_html__( 'Order by', 'bew-extras' ),
        [],
        [
          'options' => [
				'name' => __( 'Name', 'bew-extras' ),
				'slug' => __( 'Slug', 'bew-extras' ),
				'description' => __( 'Description', 'bew-extras' ),
				'count' => __( 'Count', 'bew-extras' ),
		  ],
          'default' => 'name',
        ]
      )
    );
	
	$widget->add_control(
      't'. $id .'_include',
      Bew_Controls::option_select2(
        esc_html__( 'Include Categories', 'bew-extras' ),
        [],
        [
          'description' => __( 'You can select multiples categories', 'bew-extras' ),
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
        esc_html__( 'Exclude Categories', 'bew-extras' ),
        [],
        [
          'description' => __( 'You can select multiples categories', 'bew-extras' ),
		  'options' => $cat_options,
		  'default' => [],
		  'label_block' => true,
		  'multiple' => true,
        ]
      )
    );
		
	$widget->add_control(
      't'. $id .'_load_more',
      Bew_Controls::option_switch( esc_html__( 'Load More', 'bew-extras' ), [], [
        'return' => 'yes',
      ] )
    );


    Bew_Controls::end_section( $widget );

  }
  
  function get_shortcode($settings) {
		
	
	$per_page    = $settings['t1_count'];
	$columns     = $settings['t1_columns'];
	$include     = !empty($settings['t1_include'] ) ? implode( ',', $settings['t1_include'] ) : '';
	$exclude     = !empty($settings['t1_exclude'] ) ? implode( ',', $settings['t1_exclude'] ) : '';
	$hide_empty  = ( $settings['t1_hide_empty'] == 'yes' ) ? 1 : 0;	
	$parent      = $settings['t1_only_top_level'];
	$order       = $settings['t1_order'];
	$orderby     = $settings['t1_order_by'];
	
	$load_more   = ( $settings['t1_load_more'] == 'yes' ) ? true : false;	
	
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

        <div class="bew-row gap-y text-center bew-shop-block-1">          
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

        <div class="bew-row gap-y text-center bew-shop-block-1">  
         <?php echo do_shortcode( $this->get_shortcode($settings) ); ?>

        </div>

    </div></section>
    <?php
  }

}

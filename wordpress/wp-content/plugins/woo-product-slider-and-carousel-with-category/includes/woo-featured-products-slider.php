<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function wcpscwc_featured_products_slider($atts){

	global $woocommerce_loop;

	extract(shortcode_atts(array(
		'cats' 				=> '',
		'design' 			=> '',
		'tax' 				=> 'product_cat',
		'limit' 			=> '-1',
		'slide_to_show' 	=> '3',
		'slide_to_scroll' 	=> '3',
		'autoplay' 			=> 'true',
		'autoplay_speed' 	=> '3000',
		'speed' 			=> '300',
		'arrows' 			=> 'true',
		'dots' 				=> 'true',
		'rtl'  				=> '',
		'slider_cls'		=> 'products',
		'loop'				=> 'true',
		'order'				=> 'DESC',
		'orderby'			=> 'date',
	), $atts));

	$unique = wcpscwc_get_unique();
	$cat 		= (!empty($cats)) 						? explode(',',$cats) 			: '';
	$slider_cls = !empty($slider_cls)					? $slider_cls 					: 'products';
	$design 	= !empty($design) 						? $design 						: '';
	$order 		= ( strtolower( $order ) == 'asc' ) 	? 'ASC' 						: 'DESC';
	$orderby 	= !empty( $orderby )					? $orderby 						: 'date';

	// For RTL
	if( empty($rtl) && is_rtl() ) {
		$rtl = 'true';
	} elseif ( $rtl == 'true' ) {
		$rtl = 'true';
	} else {
		$rtl = 'false';
	}

	// js added
	wp_enqueue_script( 'wpos-slick-jquery' );
	wp_enqueue_script( 'wcpscwc-public-jquery' );

	// Slider configuration
	$slider_conf = compact('slide_to_show', 'slide_to_scroll', 'autoplay', 'autoplay_speed', 'speed', 'arrows','dots','rtl', 'slider_cls', 'loop');

	ob_start();

	// setup query
	if(wcpscwc_wc_version()){
		$tax_query = array();
		$tax_query[] = array('relation' => 'AND');
		$tax_query[] =array(
					'taxonomy' => 'product_visibility',
					'field'    => 'name',
					'terms'    => 'featured',
					'operator' => 'IN',
				);
		// Category Parameter 
		if($cat != "") {
			$tax_query[] =array( 
											'taxonomy' 	=> $tax,
											'field' 	=> 'id',
											'terms' 	=> $cat
								);
		}

		$args = array(
			'post_type'				=> 'product',
			'post_status' 			=> 'publish',
			'ignore_sticky_posts'	=> 1,
			'posts_per_page' 		=> $limit,
			'tax_query' 			=> $tax_query,
			'order'					=> $order,
			'orderby'				=> $orderby,
		);
	}
	else{
		$args = array(
			'post_type'				=> 'product',
			'post_status' 			=> 'publish',
			'ignore_sticky_posts'	=> 1,
			'posts_per_page' 		=> $limit,
			'order'					=> $order,
			'orderby'				=> $orderby,
			'meta_query' 			=> array(
				// get only products marked as featured
				array(
					'key' 		=> '_featured',
					'value' 	=> 'yes',
					'compare' 	=> '=',
				)
			)
		);
		// Category Parameter
		if($cat != "") {
		$args['tax_query'] = array(
									array( 
											'taxonomy' 	=> $tax,
											'field' 	=> 'id',
											'terms' 	=> $cat
								));

		}
	}

	// query database
	$products = new WP_Query( $args );

	if ( $products->have_posts() ) : ?>
		<div class="wcpscwc-product-slider-wrap wcps-<?php echo $design; ?>">
			<div class="woocommerce wcpscwc-product-slider" id="wcpscwc-product-slider-<?php echo $unique; ?>">
			<?php 
			woocommerce_product_loop_start();  
			while ( $products->have_posts() ) : $products->the_post(); 
				if(wcpscwc_wc_version()){
					wc_get_template_part( 'content', 'product' ); 
				} else{
					woocommerce_get_template_part( 'content', 'product' ); 
				}
			endwhile; // end of the loop.  
			woocommerce_product_loop_end(); ?>
			</div>
			<div class="wcpscwc-slider-conf" data-conf="<?php echo htmlspecialchars(json_encode($slider_conf)); ?>"></div>
		</div>
	<?php endif;
	wp_reset_postdata();
	return ob_get_clean(); 
}

add_shortcode( 'featured_products_slider', 'wcpscwc_featured_products_slider' );
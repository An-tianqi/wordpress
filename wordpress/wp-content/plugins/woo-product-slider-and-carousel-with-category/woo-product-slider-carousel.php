<?php
/**
 * Plugin Name: Product Slider and Carousel with Category for WooCommerce
 * Plugin URI: https://www.wponlinesupport.com/plugins/
 * Description: Display Woocommerce Product Slider/Carousel, Woocommerce Best Selling Product Slider/Carousel, Woocommerce Featured Product Slider/Carousel with category. Also work with Gutenberg shortcode block.
 * Author: WP OnlineSupport 
 * Text Domain: woo-product-slider-and-carousel-with-category
 * Domain Path: /languages/
 * WC tested up to: 4.8.0
 * Version: 2.4
 * Author URI: https://www.wponlinesupport.com/
 *
 * @package Product Slider and Carousel with Category for WooCommerce
 * @author WP OnlineSupport
 */

if( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if( ! defined( 'WCPSCWC_VERSION' ) ) {
	define( 'WCPSCWC_VERSION', '2.4' ); // Version of plugin
}
if( ! defined( 'WCPSCWC_DIR' ) ) {
    define( 'WCPSCWC_DIR', dirname( __FILE__ ) ); // Plugin dir
}
if( ! defined( 'WCPSCWC_URL' ) ) {
    define( 'WCPSCWC_URL', plugin_dir_url( __FILE__ ) ); // Plugin url
}
if( ! defined( 'WCPSCW_POST_TYPE' ) ) {
    define( 'WCPSCW_POST_TYPE', 'product' ); // Plugin post type
}

/**
 * Check WooCommerce plugin is active
 *
 * @since 1.0.0
 */
function wcpscwc_check_activation() {

	if ( ! class_exists('WooCommerce') ) {
		// is this plugin active?
		if ( is_plugin_active( plugin_basename( __FILE__ ) ) ) {
			// deactivate the plugin
	 		deactivate_plugins( plugin_basename( __FILE__ ) );
	 		// unset activation notice
	 		unset( $_GET[ 'activate' ] );
	 		// display notice
	 		add_action( 'admin_notices', 'wcpscwc_admin_notices' );
		}
	}
}

// Check required plugin is activated or not
add_action( 'admin_init', 'wcpscwc_check_activation' );

/**
 * Admin notices
 * 
 * @since 1.0.0
 */
function wcpscwc_admin_notices() {

	if ( ! class_exists('WooCommerce') ) {
		echo '<div class="error notice is-dismissible">';
		echo sprintf( __('<p><strong>%s</strong> recommends the following plugin to use.</p>', 'woo-product-slider-and-carousel-with-category'), 'Product Slider and Carousel with Category for WooCommerce' );
		echo sprintf( __('<p><strong><a href="%s" target="_blank">%s</a> </strong></p>', 'woo-product-slider-and-carousel-with-category'), 'https://wordpress.org/plugins/woocommerce/', 'WooCommerce' );
		echo '</div>';
	}
}

/**
 * Load the plugin after the main plugin is loaded.
 * 
 * @since 1.0.0
 */
function wcpscwc_load_plugin() {

	// Check main plugin is active or not
	if( class_exists('WooCommerce') ) {

		/**
		 * Load Text Domain
		 * This gets the plugin ready for translation
		 * 
		 * @since 1.0.0
		 */
		function wcpscwc_load_textdomain() {
			load_plugin_textdomain( 'woo-product-slider-and-carousel-with-category', false, dirname( plugin_basename(__FILE__) ) . '/languages/' );
		}

		// Action to load plugin text domain
		add_action('plugins_loaded', 'wcpscwc_load_textdomain');

		/**
		 * Function add some script and style
		 * 
		 * @since 1.2.5
		 */
		function wcpscwc_style_css() {

			// Slick CSS
			if( ! wp_style_is( 'wpos-slick-style', 'registered' ) ) {
				wp_enqueue_style( 'wpos-slick-style',  plugin_dir_url( __FILE__ ) . 'assets/css/slick.css', array(), WCPSCWC_VERSION);
			}

			wp_enqueue_style( 'wcpscwc_public_style',  plugin_dir_url( __FILE__ ) . 'assets/css/wcpscwc-public.css', array(), WCPSCWC_VERSION);

			// Registring slick slider script
			if( ! wp_script_is( 'wpos-slick-jquery', 'registered' ) ) {
				wp_register_script( 'wpos-slick-jquery', WCPSCWC_URL.'assets/js/slick.min.js', array('jquery'), WCPSCWC_VERSION, true );
			}

			// Public script
			wp_register_script( 'wcpscwc-public-jquery', WCPSCWC_URL.'assets/js/public.js', array('jquery'), WCPSCWC_VERSION, true );			
		}

		// Action to add some style and script
		add_action( 'wp_enqueue_scripts', 'wcpscwc_style_css' );

		// Action to add admin style and script
		add_action( 'admin_enqueue_scripts', 'wcpscwc_admin_style_script' );

		/*
		* Fucntion to add admin style and script
		*/
		function wcpscwc_admin_style_script( $hook ) {

			if( $hook == 'toplevel_page_wcpscwc-about' ) {
				wp_register_script( 'wcpscwc-admin-script', WCPSCWC_URL.'assets/js/wcpscwc-admin.js', array('jquery'), WCPSCWC_VERSION );
				wp_enqueue_script( 'wcpscwc-admin-script' );
			}
		}

		// Including some files
		require_once( 'includes/woo-products-slider.php' );
		require_once( 'includes/woo-best-selling-products-slider.php' );
		require_once( 'includes/woo-featured-products-slider.php' );

		// Admin class
		require_once( WCPSCWC_DIR . '/includes/admin/class-wcpscwc-admin.php' );

		/* Recommended Plugins Starts */
		if ( is_admin() ) {
			require_once( WCPSCWC_DIR . '/wpos-plugins/wpos-recommendation.php' );

			wpos_espbw_init_module( array(
									'prefix'	=> 'wcpscwc',
									'menu'		=> 'wcpscwc-about',
									'position'	=> 1,
								));
		}
		/* Recommended Plugins Ends */
	}
}

// Action to load plugin after the main plugin is loaded
add_action('plugins_loaded', 'wcpscwc_load_plugin', 5);

/**
 * Function to unique number value
 * 
 * @since 1.2.5
 */
function wcpscwc_get_unique() {
    static $unique = 0;
    $unique++;

    return $unique;
}

/**
 * Function to check woocommerce compatibility
 * 
 * @since 1.1.2
 */
function wcpscwc_wc_version( $version = '3.0' ){
    global $woocommerce;

    if( version_compare( $woocommerce->version, $version, ">=" ) ) {
      return true;
    }
    return false;
}
<?php
/**
 * Whippet Functions
 *
 * @category Whippet
 * @package  Whippet
 * @author   Malinois
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     http://www.hashbangcode.com/
 */

namespace Whippet;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Whippet Functions Class
 *
 * @category Whippet
 * @package  Whippet
 * @author   Malinois
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     http://www.hashbangcode.com/
 */
class Functions {

	public function __construct() {
		$whippet_options = get_option( 'whippet_options' );

		/**
		 * Options Actions + Filters
		 *
		 * @var [type]
		 */
		if ( ! empty( $whippet_options['disable_emojis'] ) && $whippet_options['disable_emojis'] === '1' ) {
			add_action( 'init', array( $this, 'whippet_disable_emojis' ) );
		}

		if ( ! empty( $whippet_options['disable_embeds'] ) && $whippet_options['disable_embeds'] === '1' ) {
			add_action( 'init', array( $this, 'whippet_disable_embeds' ), 9999 );
		}

		if ( ! empty( $whippet_options['remove_query_strings'] ) && $whippet_options['remove_query_strings'] === '1' ) {
			add_action( 'init', array( $this, 'whippet_remove_query_strings' ) );
		}

		/**
		 * Disable XML-RPC
		 *
		 * @var [type]
		 */
		if ( ! empty( $whippet_options['disable_xmlrpc'] ) && $whippet_options['disable_xmlrpc'] === '1' ) {
			add_filter( 'xmlrpc_enabled', '__return_false' );
			add_filter( 'wp_headers', array( $this, 'whippet_remove_x_pingback' ) );
			add_filter( 'pings_open', '__return_false', 9999 );
		}

		if ( ! empty( $whippet_options['remove_jquery_migrate'] ) && $whippet_options['remove_jquery_migrate'] === '1' ) {
			add_filter( 'wp_default_scripts', array( $this, 'whippet_remove_jquery_migrate' ) );
		}

		if ( ! empty( $whippet_options['hide_wp_version'] ) && $whippet_options['hide_wp_version'] === '1' ) {
			remove_action( 'wp_head', array( $this, 'wp_generator' ) );
			add_filter( 'the_generator', array( $this, 'whippet_hide_wp_version' ) );
		}

		if ( ! empty( $whippet_options['remove_wlwmanifest_link'] ) && $whippet_options['remove_wlwmanifest_link'] === '1' ) {
			remove_action( 'wp_head', array( $this, 'wlwmanifest_link' ) );
		}

		if ( ! empty( $whippet_options['remove_rsd_link'] ) && $whippet_options['remove_rsd_link'] === '1' ) {
			remove_action( 'wp_head', array( $this, 'rsd_link' ) );
		}

		/**
		 * Remove Shortlink
		 *
		 * @var [type]
		 */
		if ( ! empty( $whippet_options['remove_shortlink'] ) && $whippet_options['remove_shortlink'] === '1' ) {
			remove_action( 'wp_head', array( $this, 'wp_shortlink_wp_head' ) );
			remove_action( 'template_redirect', array( $this, 'wp_shortlink_header' ), 11, 0 );
		}

		if ( ! empty( $whippet_options['disable_rss_feeds'] ) && $whippet_options['disable_rss_feeds'] === '1' ) {
			add_action( 'do_feed', array( $this, 'whippet_disable_rss_feeds' ), 1 );
			add_action( 'do_feed_rdf', array( $this, 'whippet_disable_rss_feeds' ), 1 );
			add_action( 'do_feed_rss', array( $this, 'whippet_disable_rss_feeds' ), 1 );
			add_action( 'do_feed_rss2', array( $this, 'whippet_disable_rss_feeds' ), 1 );
			add_action( 'do_feed_atom', array( $this, 'whippet_disable_rss_feeds' ), 1 );
			add_action( 'do_feed_rss2_comments', array( $this, 'whippet_disable_rss_feeds' ), 1 );
			add_action( 'do_feed_atom_comments', array( $this, 'whippet_disable_rss_feeds' ), 1 );
		}

		if ( ! empty( $whippet_options['remove_feed_links'] ) && $whippet_options['remove_feed_links'] === '1' ) {
			remove_action( 'wp_head', array( $this, 'feed_links' ), 2 );
			remove_action( 'wp_head', array( $this, 'feed_links_extra' ), 3 );
		}

		if ( ! empty( $whippet_options['disable_self_pingbacks'] ) && $whippet_options['disable_self_pingbacks'] === '1' ) {
			add_action( 'pre_ping', array( $this, 'whippet_disable_self_pingbacks' ) );
		}

		/**
		 * Remove REST API Links
		 *
		 * @var [type]
		 */
		if ( ! empty( $whippet_options['remove_rest_api_links'] ) && $whippet_options['remove_rest_api_links'] === '1' ) {
			remove_action( 'wp_head', array( $this, 'rest_output_link_wp_head' ) );
			remove_action( 'template_redirect', array( $this, 'rest_output_link_header' ), 11, 0 );
		}

		/**
		 * Disable Google Maps
		 *
		 * @var [type]
		 */
		if ( ! empty( $whippet_options['disable_google_maps'] ) && $whippet_options['disable_google_maps'] === '1' ) {
			add_action( 'wp_loaded', array( $this, 'whippet_disable_google_maps' ) );
		}

		/**
		 * Disable WooCommerce Scripts
		 *
		 * @var [type]
		 */
		if ( ! empty( $whippet_options['disable_woocommerce_scripts'] ) && $whippet_options['disable_woocommerce_scripts'] === '1' ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'whippet_disable_woocommerce_scripts' ), 99 );
		}

		/**
		 * Disable WooCommerce Cart Fragmentation
		 *
		 * @var [type]
		 */
		if ( ! empty( $whippet_options['disable_woocommerce_cart_fragmentation'] ) && $whippet_options['disable_woocommerce_cart_fragmentation'] === '1' ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'whippet_disable_woocommerce_cart_fragmentation' ), 99 );
		}

		/**
		 * Disable WooCommerce Status Meta Box
		 *
		 * @var [type]
		 */
		if ( ! empty( $whippet_options['disable_woocommerce_status'] ) && $whippet_options['disable_woocommerce_status'] === '1' ) {
			add_action( 'wp_dashboard_setup', array( $this, 'whippet_disable_woocommerce_status' ) );
		}

		/**
		 * Disable WooCommerce Widgets
		 *
		 * @var [type]
		 */
		if ( ! empty( $whippet_options['disable_woocommerce_widgets'] ) && $whippet_options['disable_woocommerce_widgets'] === '1' ) {
			add_action( 'widgets_init', array( $this, 'whippet_disable_woocommerce_widgets' ), 99 );
		}

		if ( ! empty( $whippet_options['disable_heartbeat'] ) ) {
			add_action( 'init', array( $this, 'whippet_disable_heartbeat' ), 1 );
		}

		if ( ! empty( $whippet_options['heartbeat_frequency'] ) ) {
			add_filter( 'heartbeat_settings', array( $this, 'whippet_heartbeat_frequency' ) );
		}

		if ( ! empty( $whippet_options['limit_post_revisions'] ) ) {
			define( 'WP_POST_REVISIONS', $whippet_options['limit_post_revisions'] );
		}

		if ( ! empty( $whippet_options['autosave_interval'] ) ) {
			define( 'AUTOSAVE_INTERVAL', $whippet_options['autosave_interval'] );
		}

		if ( ! empty( $whippet_options['disable_admin_bar'] ) && $whippet_options['disable_admin_bar'] === '1' ) {
			add_filter( 'show_admin_bar', '__return_false' );
			add_action( 'admin_print_scripts-profile.php', array( $this, 'whippet_disable_admin_bar' ) );
		}
	}

	/**
	 * Disable Emojis
	 *
	 * @return boolean [description]
	 */
	public static function whippet_disable_emojis() {
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
		add_filter( 'tiny_mce_plugins', array( $this, 'whippet_disable_emojis_tinymce' ) );
	}

	public static function whippet_disable_emojis_tinymce( $plugins ) {
		if ( is_array( $plugins ) ) {
			return array_diff( $plugins, array( 'wpemoji' ) );
		} else {
			return array();
		}
	}

	/**
	 * Disable Embeds
	 *
	 * @return [type] [description]
	 */
	public static function whippet_disable_embeds() {
		global $wp;
		$wp->public_query_vars = array_diff( $wp->public_query_vars, array( 'embed' ) );
		remove_action( 'rest_api_init', 'wp_oembed_register_route' );
		add_filter( 'embed_oembed_discover', '__return_false' );
		remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );
		remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
		remove_action( 'wp_head', 'wp_oembed_add_host_js' );
		add_filter( 'tiny_mce_plugins', 'whippet_disable_embeds_tiny_mce_plugin' );
		add_filter( 'rewrite_rules_array', 'whippet_disable_embeds_rewrites' );
		remove_filter( 'pre_oembed_result', 'wp_filter_pre_oembed_result', 10 );
	}

	/**
	 * Disable Embeds Tiny MCE
	 *
	 * @param  [type] $plugins [description]
	 * @return [type]          [description]
	 */
	public static function whippet_disable_embeds_tiny_mce_plugin( $plugins ) {
		return array_diff( $plugins, array( 'wpembed' ) );
	}

	/**
	 * Disable Embeds Rewrites
	 *
	 * @param  [type] $rules [description]
	 * @return [type]        [description]
	 */
	public static function whippet_disable_embeds_rewrites( $rules ) {
		foreach ( $rules as $rule => $rewrite ) {
			if ( false !== strpos( $rewrite, 'embed=true' ) ) {
				unset( $rules[$rule] );
			}
		}
		return $rules;
	}

	/**
	 * Disable XML-RPC
	 *
	 * @param  [type] $headers [description]
	 * @return [type]          [description]
	 */
	public static function whippet_remove_x_pingback( $headers ) {
		unset( $headers['X-Pingback'] );
		return $headers;
	}

	/**
	 * Disable Google Maps
	 *
	 * @return [type] [description]
	 */
	public static function whippet_disable_google_maps() {
		ob_start ( array( $this, 'whippet_disable_google_maps_regex' ) );
	}

	public static function whippet_disable_google_maps_regex( $html ) {
		$html = preg_replace( '/<script[^<>]*\/\/maps.(googleapis|google|gstatic).com\/[^<>]*><\/script>/i', '', $html );
		return $html;
	}

	/**
	 * Hide Nag Notices
	 *
	 * @return [type] [description]
	 */
	public static function hide_update_noticee_to_all_but_admin_users() {
		remove_all_actions( 'admin_notices' );
	}

	/**
	 * Disable WooCommerce Scripts
	 *
	 * @return [type] [description]
	 */
	public static function whippet_disable_woocommerce_scripts() {
		if ( function_exists( 'is_woocommerce' ) ) {
			if ( ! is_woocommerce() && ! is_cart() && ! is_checkout() ) {
				global $whippet_options;

				// Dequeue WooCommerce Styles.
				wp_dequeue_style( 'woocommerce-general' );
				wp_dequeue_style( 'woocommerce-layout' );
				wp_dequeue_style( 'woocommerce-smallscreen' );
				wp_dequeue_style( 'woocommerce_frontend_styles' );
				wp_dequeue_style( 'woocommerce_fancybox_styles' );
				wp_dequeue_style( 'woocommerce_chosen_styles' );
				wp_dequeue_style( 'woocommerce_prettyPhoto_css' );
				// Dequeue WooCommerce Scripts.
				wp_dequeue_script( 'wc_price_slider' );
				wp_dequeue_script( 'wc-single-product' );
				wp_dequeue_script( 'wc-add-to-cart' );
				wp_dequeue_script( 'wc-checkout' );
				wp_dequeue_script( 'wc-add-to-cart-variation' );
				wp_dequeue_script( 'wc-single-product' );
				wp_dequeue_script( 'wc-cart' );
				wp_dequeue_script( 'wc-chosen' );
				wp_dequeue_script( 'woocommerce' );
				wp_dequeue_script( 'prettyPhoto' );
				wp_dequeue_script( 'prettyPhoto-init' );
				wp_dequeue_script( 'jquery-blockui' );
				wp_dequeue_script( 'jquery-placeholder' );
				wp_dequeue_script( 'fancybox' );
				wp_dequeue_script( 'jqueryui' );

				if ( empty( $whippet_options['disable_woocommerce_cart_fragmentation']) || $whippet_options['disable_woocommerce_cart_fragmentation'] === '0' ) {
					wp_dequeue_script( 'wc-cart-fragments' );
				}
			}
		}
	}

	/**
	 * Disable WooCommerce Cart Fragmentation
	 *
	 * @return [type] [description]
	 */
	public static function whippet_disable_woocommerce_cart_fragmentation() {
		if ( function_exists( 'is_woocommerce' ) ) {
			wp_dequeue_script( 'wc-cart-fragments' );
		}
	}

	/**
	 * Disable WooCommerce Status
	 *
	 * @return [type] [description]
	 */
	public static function whippet_disable_woocommerce_status() {
		remove_meta_box( 'woocommerce_dashboard_status', 'dashboard', 'normal' );
	}

	/**
	 * Disable WooCommerce Widgets
	 *
	 * @return [type] [description]
	 */
	public static function whippet_disable_woocommerce_widgets() {
		global $whippet_options;

		unregister_widget( 'WC_Widget_Products' );
		unregister_widget( 'WC_Widget_Product_Categories' );
		unregister_widget( 'WC_Widget_Product_Tag_Cloud' );
		unregister_widget( 'WC_Widget_Cart' );
		unregister_widget( 'WC_Widget_Layered_Nav' );
		unregister_widget( 'WC_Widget_Layered_Nav_Filters' );
		unregister_widget( 'WC_Widget_Price_Filter' );
		unregister_widget( 'WC_Widget_Product_Search' );
		unregister_widget( 'WC_Widget_Recently_Viewed' );

		if ( empty( $whippet_options['disable_woocommerce_reviews']) || $whippet_options['disable_woocommerce_reviews'] === '0' ) {
			unregister_widget( 'WC_Widget_Recent_Reviews' );
			unregister_widget( 'WC_Widget_Top_Rated_Products' );
			unregister_widget( 'WC_Widget_Rating_Filter' );
		}
	}

	/**
	 * Remove Query Strings
	 *
	 * @return [type] [description]
	 */
	public static function whippet_remove_query_strings() {
		if ( ! is_admin() ) {
			add_filter( 'script_loader_src', array( $this, 'whippet_remove_query_strings_split' ), 15 );
			add_filter( 'style_loader_src', array( $this, 'whippet_remove_query_strings_split' ), 15 );
		}
	}

	public static function whippet_remove_query_strings_split( $src ) {
		$output = preg_split( '/(&ver|\?ver)/', $src );
		return $output[0];
	}

	/**
	 * Remove jQuery Migrate
	 *
	 * @param  [type] $scripts [description]
	 * @return [type]          [description]
	 */
	public static function whippet_remove_jquery_migrate( $scripts ) {
		if ( ! is_admin() ) {
			$scripts->remove( 'jquery' );
			$scripts->add( 'jquery', false, array( 'jquery-core' ), '1.12.4' );
		}
	}

	/**
	 * Hide WordPress Version
	 *
	 * @return [type] [description]
	 */
	public static function whippet_hide_wp_version() {
		return '';
	}

	/**
	 * Disable RSS Feeds
	 *
	 * @return [type] [description]
	 */
	public static function whippet_disable_rss_feeds() {
		wp_die ( __( 'No feed available, please visit the <a href="' . esc_url( home_url('/') ) . '">homepage</a>!' ) );
	}

	/**
	 * Disable Self Pingbacks
	 *
	 * @param  [type] $links [description]
	 * @return [type]        [description]
	 */
	public static function whippet_disable_self_pingbacks( &$links ) {
		$home = get_option( 'home' );
		foreach ( $links as $l => $link ) {
			if ( strpos( $link, $home ) === 0 ) {
				unset( $links[ $l ] );
			}
		}
	}

	/**
	 * Disable Heartbeat
	 *
	 * @return [type] [description]
	 */
	public static function whippet_disable_heartbeat() {
		global $whippet_options;
		if ( ! empty( $whippet_options['disable_heartbeat'] ) ) {
			if ( $whippet_options['disable_heartbeat'] === 'disable_everywhere' ) {
				wp_deregister_script( 'heartbeat' );
			} elseif ( $whippet_options['disable_heartbeat'] === 'allow_posts' ) {
				global $pagenow;
				if ( $pagenow != 'post.php' && $pagenow != 'post-new.php' ) {
					wp_deregister_script( 'heartbeat' );
				}
			}
		}
	}

	/**
	 * Heartbeat Frequency
	 *
	 * @param  [type] $settings [description]
	 * @return [type]           [description]
	 */
	public static function whippet_heartbeat_frequency( $settings ) {
		global $whippet_options;
		if ( ! empty( $whippet_options['heartbeat_frequency'] ) ) {
			$settings['interval'] = $whippet_options['heartbeat_frequency'];
		}
		return $settings;
	}
}

new Functions();

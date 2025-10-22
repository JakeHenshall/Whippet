<?php
/**
 * Admin Class
 *
 * @package Whippet
 */

namespace Whippet;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Admin functionality
 */
class Admin {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'register_menu' ) );
		add_filter( 'plugin_action_links_' . plugin_basename( WHIPPET_PATH . 'whippet.php' ), array( $this, 'add_settings_link' ) );
	}

	/**
	 * Register admin menu
	 */
	public function register_menu() {
		// Main Dashboard - single page with tabs
		add_submenu_page(
			'tools.php',
			__( 'Whippet - Dashboard', 'whippet' ),
			__( 'Whippet', 'whippet' ),
			'manage_options',
			'whippet',
			array( $this, 'render_admin_page' )
		);
	}

	/**
	 * Add settings link to plugins page
	 *
	 * @param array $links Existing plugin action links.
	 * @return array Modified plugin action links.
	 */
	public function add_settings_link( $links ) {
		$settings_link = '<a href="' . esc_url( admin_url( 'tools.php?page=whippet' ) ) . '">' . __( 'Settings', 'whippet' ) . '</a>';
		array_unshift( $links, $settings_link );
		return $links;
	}

	/**
	 * Render admin page
	 */
	public function render_admin_page() {
		include WHIPPET_PATH . 'inc/admin.php';
	}

	/**
	 * Initialize WP_Filesystem
	 */
	public static function init_filesystem() {
		global $wp_filesystem;
		
		if ( null === $wp_filesystem ) {
			if ( ! function_exists( 'WP_Filesystem' ) ) {
				require_once ABSPATH . 'wp-admin/includes/file.php';
			}
			WP_Filesystem();
		}
		
		return $wp_filesystem;
	}
}


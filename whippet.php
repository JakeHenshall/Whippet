<?php
/**
 * Plugin Name: Whippet - Disable Scripts/Styles Conditionally
 * Plugin URI: https://hashbangcode.com/
 * Description: Disable scripts and styles on a per-page basis to improve performance
 * Version: 1.0.2
 * Author: Jake Henshall
 * Author URI: https://hashbangcode.com/
 * License: GPL2
 * Text Domain: whippet
 * Domain Path: /languages
 *
 * @package Whippet
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Autoloader for plugin classes
 *
 * @param string $class Class name to load.
 */
function whippet_autoloader( $class ) {
	// Project-specific namespace prefix
	$prefix = 'Whippet\\';

	// Base directory for the namespace prefix
	$base_dir = plugin_dir_path( __FILE__ ) . 'inc/classes/';

	// Does the class use the namespace prefix?
	$len = strlen( $prefix );
	if ( strncmp( $prefix, $class, $len ) !== 0 ) {
		return;
	}

	// Get the relative class name
	$relative_class = substr( $class, $len );

	// Replace namespace separators with directory separators
	$file = $base_dir . str_replace( '\\', '/', $relative_class ) . '.php';

	// If the file exists, require it
	if ( file_exists( $file ) ) {
		require $file;
	}
}
spl_autoload_register( 'whippet_autoloader' );

/**
 * Initialize the plugin
 *
 * @return Whippet\Plugin
 */
function whippet() {
	return Whippet\Plugin::instance();
}

// Start the plugin
whippet();

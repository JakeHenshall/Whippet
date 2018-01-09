<?php
/**
 * Whippet Import & Export
 *
 * @category Whippet
 * @package  Whippet
 * @author   Malinois
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     http://www.hashbangcode.com/
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Render the settings page
 */
function whippet_settings_page() {

	?>
	<div class="wrap">

		<div class="metabox-holder">
			<div class="postbox">
				<h3><span><?php esc_html_e( 'Export Settings' ); ?></span></h3>
				<div class="inside">
					<p><?php esc_html_e( 'Export the plugin settings for this site as a .json file. This allows you to easily import the configuration into another site.' ); ?></p>
					<form method="post">
						<p><input type="hidden" name="whippet_action" value="export_settings" /></p>
						<p>
							<?php wp_nonce_field( 'whippet_export_nonce', 'whippet_export_nonce' ); ?>
							<?php submit_button( __( 'Export' ), 'secondary', 'submit', false ); ?>
						</p>
					</form>
				</div><!-- .inside -->
			</div><!-- .postbox -->

			<div class="postbox">
				<h3><span><?php esc_html_e( 'Import Settings' ); ?></span></h3>
				<div class="inside">
					<p><?php esc_html_e( 'Import the plugin settings from a .json file. This file can be obtained by exporting the settings on another site using the form above.' ); ?></p>
					<form method="post" enctype="multipart/form-data">
						<p>
							<input type="file" name="import_file"/>
						</p>
						<p>
							<input type="hidden" name="whippet_action" value="import_settings" />
							<?php wp_nonce_field( 'whippet_import_nonce', 'whippet_import_nonce' ); ?>
							<?php submit_button( __( 'Import' ), 'secondary', 'submit', false ); ?>
						</p>
					</form>
				</div><!-- .inside -->
			</div><!-- .postbox -->
		</div><!-- .metabox-holder -->

	</div><!--end .wrap-->
	<?php
}

/**
 * Process a settings export that generates a .json file of the shop settings
 */
function whippet_process_settings_export() {

	if ( isset( $_POST['submit'] ) ) { // Input var okay.

		if ( ! wp_verify_nonce( wp_unslash( $_POST['whippet_export_nonce'] ), 'whippet_export_nonce' ) ) { // Input var okay.
			return;
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		// Get all Options used in core plugin.
		$whippet_core = get_option( 'whippet_options' );

		// Get all Options used in analytic.
		$whippet_analytics = array(
			'sgal_tracking_id'              => esc_attr( get_option( 'sgal_tracking_id' ) ),
			'sgal_adjusted_bounce_rate'     => esc_attr( get_option( 'sgal_adjusted_bounce_rate' ) ),
			'sgal_script_position'          => esc_attr( get_option( 'sgal_script_position' ) ),
			'sgal_enqueue_order'            => esc_attr( get_option( 'sgal_enqueue_order' ) ),
			'sgal_anonymize_ip'             => esc_attr( get_option( 'sgal_anonymize_ip' ) ),
			'sgal_track_admin'              => esc_attr( get_option( 'sgal_track_admin' ) ),
			'caos_remove_wp_cron'           => esc_attr( get_option( 'caos_remove_wp_cron' ) ),
			'caos_disable_display_features' => esc_attr( get_option( 'caos_disable_display_features' ) ),
		);

		// Make all Options a main array to encode into json.
		$whippet_options = array(
			'whippet_core'      => $whippet_core,
			'whippet_analytics' => $whippet_analytics,
		);
		$data            = wp_json_encode( $whippet_options );

		ignore_user_abort( true );

		nocache_headers();
		header( 'Content-Type: application/json; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename=whippet-settings-export-' . date( 'm-d-Y' ) . '.json' );
		header( 'Expires: 0' );

		echo wp_json_encode( $data, JSON_UNESCAPED_SLASHES );
		exit;
	}
}
add_action( 'admin_init', 'whippet_process_settings_export' );

/**
 * Process a settings import from a json file
 */
function whippet_process_settings_import() {

	if ( isset( $_POST['submit'] ) ) { // Input var okay.

		if ( ! wp_verify_nonce( wp_unslash( $_POST['whippet_import_nonce'] ), 'whippet_import_nonce' ) ) { // Input var okay.
			return;
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$extension = end( explode( '.', wp_unslash( $_FILES['import_file']['name'] ) ) ); // Input var okay.

		if ( 'json' !== $extension ) {
			wp_die( esc_html_e( 'Please upload a valid .json file' ) );
		}

		$import_file = wp_unslash( $_FILES['import_file']['tmp_name'] ); // Input var okay.

		if ( empty( $import_file ) ) {
			wp_die( esc_html_e( 'Please upload a file to import' ) );
		}
		$whippet_options = get_option( 'whippet_options' );

		delete_option( 'whippet_options' );

		// Retrieve the settings from the file and convert the json object to an array.
		$content  = file_get_contents( $import_file );
		$obj      = json_decode( $content );
		$settings = json_decode( $obj, true );

		foreach ( $settings as $key => $value ) {
			if ( 'whippet_core' === $key ) {
				add_option( 'whippet_options', $value );
			} elseif ( 'whippet_analytics' === $key ) {
				foreach ( $value as $setting => $setting_value ) {
					update_option( $setting, $setting_value );
				}
			}
		}

		wp_safe_redirect( admin_url( 'tools.php?page=whippet&tab=importExport' ) );
		exit;

	}
}
add_action( 'admin_init', 'whippet_process_settings_import' );

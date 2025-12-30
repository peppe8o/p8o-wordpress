<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Export handler via admin-post.php.
 */
add_action( 'admin_post_p8o_optimize_export_json', function () {
	if ( ! current_user_can( 'manage_options' ) ) wp_die( 'Forbidden' );
	check_admin_referer( 'p8o_optimize_export_json' );
	p8o_optimize_export_json_and_exit();
});

function p8o_optimize_get_all_option_names() {
	return array(
		'p8o_optimize_enabled',
		'p8o_optimize_cleanup_on_delete',

		'p8o_css_resources',

		'p8o_js_resources',
		'p8o_external_js_hints',

		'p8o_image_paths',
		'p8o_images_cls',
		'p8o_disable_wp_srcset_sizes',
		'p8o_disable_wp_width_height',
		'p8o_disable_wp_lazy_loading',

		'p8o_ad_cls_rules',
	);
}

function p8o_optimize_export_json_and_exit() {
	if ( headers_sent() ) {
		wp_die( 'Cannot export: headers already sent.' );
	}

	while ( ob_get_level() ) {
		ob_end_clean();
	}

	$keys = p8o_optimize_get_all_option_names();
	$data = array(
		'format'      => 'p8o-optimize-settings',
		'version'     => '1',
		'exported_at' => gmdate( 'c' ),
		'options'     => array(),
	);

	foreach ( $keys as $k ) {
		$data['options'][ $k ] = get_option( $k, null );
	}

	$json = wp_json_encode( $data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES );

	nocache_headers();
	header( 'Content-Type: application/json; charset=utf-8' );
	header( 'Content-Disposition: attachment; filename="p8o-optimize-settings.json"' );
	header( 'Content-Transfer-Encoding: binary' );

	echo $json;
	exit;
}

function p8o_optimize_handle_import_only() {
	if ( ! current_user_can( 'manage_options' ) ) return;
	if ( empty( $_POST['p8o_optimize_action'] ) ) return;

	$action = sanitize_key( (string) $_POST['p8o_optimize_action'] );
	if ( $action !== 'import_json' ) return;

	check_admin_referer( 'p8o_optimize_import_json', 'p8o_optimize_import_nonce' );
	p8o_optimize_import_json();
}

function p8o_optimize_import_json() {
	if ( empty( $_FILES['p8o_optimize_json']['tmp_name'] ) ) {
		add_settings_error( 'p8o-optimize', 'p8o-import-missing', 'JSON file missing.', 'error' );
		return;
	}

	$tmp = $_FILES['p8o_optimize_json']['tmp_name'];
	$raw = file_get_contents( $tmp );
	if ( ! is_string( $raw ) || $raw === '' ) {
		add_settings_error( 'p8o-optimize', 'p8o-import-open', 'Unable to read uploaded JSON.', 'error' );
		return;
	}

	$payload = json_decode( $raw, true );
	if ( json_last_error() !== JSON_ERROR_NONE || ! is_array( $payload ) ) {
		add_settings_error( 'p8o-optimize', 'p8o-import-json', 'Invalid JSON file.', 'error' );
		return;
	}

	if ( empty( $payload['options'] ) || ! is_array( $payload['options'] ) ) {
		add_settings_error( 'p8o-optimize', 'p8o-import-structure', 'JSON format not recognized (missing options).', 'error' );
		return;
	}

	$allowed = array_flip( p8o_optimize_get_all_option_names() );

	$updated = 0;
	foreach ( $payload['options'] as $name => $value ) {
		$name = (string) $name;
		if ( ! isset( $allowed[ $name ] ) ) continue;

		update_option( $name, $value );
		$updated++;
	}

	add_settings_error( 'p8o-optimize', 'p8o-import-ok', 'Imported settings. Updated: ' . (int) $updated, 'updated' );
}

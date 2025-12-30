<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// Export handler via admin-post.php.
add_action( 'admin_post_p8ooptimizeexportjson', function () {
	if ( ! current_user_can( 'manage_options' ) ) wp_die( 'Forbidden' );
	check_admin_referer( 'p8ooptimizeexportjson' );
	p8ooptimizeexportjsonandexit();
} );

function p8ooptimizegetalloptionnames() {
	return array(
		'p8ooptimizeenabled',
		'p8ooptimizecleanupondelete',
		'p8ocssresources',
		'p8ojsresources',
		'p8oexternaljshints',
		'p8oimagepaths',
		'p8oimagescls',
		'p8odisablewpsrcsetsizes',
		'p8odisablewpwidthheight',
		'p8odisablewplazyloading',
		'p8oadclsrules',
	);
}

function p8ooptimizeexportjsonandexit() {
	if ( headers_sent() ) wp_die( 'Cannot export: headers already sent.' );
	while ( ob_get_level() ) { ob_end_clean(); }

	$keys = p8ooptimizegetalloptionnames();

	$data = array(
		'format' => 'p8o-optimize-settings',
		'version' => '1',
		'exported_at' => gmdate( 'c' ),
		'options' => array(),
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

function p8ooptimizehandleimportonly() {
	if ( ! current_user_can( 'manage_options' ) ) return;
	if ( empty( $_POST['p8ooptimizeaction'] ) ) return;

	$action = sanitize_key( (string) $_POST['p8ooptimizeaction'] );
	if ( $action !== 'importjson' ) return;

	check_admin_referer( 'p8ooptimizeimportjson', 'p8ooptimizeimportnonce' );
	p8ooptimizeimportjson();
}

function p8ooptimizeimportjson() {
	if ( empty( $_FILES['p8ooptimizejson']['tmp_name'] ) ) {
		add_settings_error( 'p8o-optimize', 'p8o-import-missing', 'JSON file missing.', 'error' );
		return;
	}

	$tmp = $_FILES['p8ooptimizejson']['tmp_name'];
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

	$allowed = array_flip( p8ooptimizegetalloptionnames() );

	$updated = 0;
	foreach ( $payload['options'] as $name => $value ) {
		$name = (string) $name;
		if ( ! isset( $allowed[ $name ] ) ) continue;
		update_option( $name, $value );
		$updated++;
	}

	add_settings_error( 'p8o-optimize', 'p8o-import-ok', 'Imported settings. Updated: ' . (int) $updated, 'updated' );
}

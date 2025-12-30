<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/* JS: inline + hard-block src */
add_action( 'wp_enqueue_scripts', function () {
	$resources = get_option( 'p8o_js_resources', array() );
	if ( ! is_array( $resources ) || empty( $resources ) ) return;

	$compiled_js = '';

	foreach ( $resources as $resource ) {
		$strategy = isset($resource['strategy']) ? $resource['strategy'] : 'none';
		if ( $strategy !== 'inline' ) continue;

		$path = isset($resource['path']) ? p8o_optimize_norm($resource['path']) : '';
		if ( $path === '' ) continue;

		$abs = p8o_optimize_url_or_path_to_abs( $path );
		if ( $abs && is_file( $abs ) && is_readable( $abs ) ) {
			$js = file_get_contents( $abs );
			if ( is_string( $js ) && $js !== '' ) $compiled_js .= "\n" . $js;
		}

		$h = isset($resource['handle']) ? p8o_optimize_norm($resource['handle']) : '';
		if ( $h !== '' ) {
			wp_dequeue_script( $h );
			wp_deregister_script( $h );
		}
	}

	if ( $compiled_js === '' ) return;

	// Dummy handle: no network request, purely inline.
	$container_handle = 'p8o-inline-js';
	wp_register_script( $container_handle, null, array(), '0.2.0', true );
	wp_enqueue_script( $container_handle );
	wp_add_inline_script( $container_handle, $compiled_js, 'after' );
}, PHP_INT_MAX );

add_filter( 'script_loader_src', function( $src, $handle ) {
	$rules = p8o_optimize_collect_inlined_js_rules();

	if ( isset( $rules['handles'][ $handle ] ) ) return '';

	foreach ( $rules['paths'] as $needle ) {
		if ( $needle !== '' && strpos( (string) $src, $needle ) !== false ) return '';
	}

	return $src;
}, PHP_INT_MAX, 2 );

/* JS: defer/async */
function p8o_optimize_js_modify_tag( $tag, $handle, $src ) {
	$resources = get_option( 'p8o_js_resources', array() );
	if ( ! is_array( $resources ) || empty( $resources ) ) return $tag;

	foreach ( $resources as $resource ) {
		if ( ! p8o_optimize_js_match_resource( $resource, $handle, $src ) ) continue;

		$strategy = isset($resource['strategy']) ? $resource['strategy'] : 'none';
		if ( $strategy === 'none' || $strategy === 'inline' ) return $tag;

		if ( $strategy === 'defer' ) {
			if ( stripos( $tag, ' defer' ) === false ) $tag = str_replace( '<script ', '<script defer ', $tag );
			return $tag;
		}

		if ( $strategy === 'async' ) {
			if ( stripos( $tag, ' async' ) === false ) $tag = str_replace( '<script ', '<script async ', $tag );
			return $tag;
		}

		return $tag;
	}

	return $tag;
}
add_filter( 'script_loader_tag', 'p8o_optimize_js_modify_tag', 10, 3 );

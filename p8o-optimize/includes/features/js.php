<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Inline JS: reads p8ojsresources rows where strategy=inline,
 * loads local file contents, dequeues the original handle/path match,
 * outputs a single dummy handle with wp_add_inline_script().
 */
add_action( 'wp_enqueue_scripts', function () {
	if ( (string) get_option( 'p8ooptimizeenabled', '1' ) !== '1' ) return;

	$resources = get_option( 'p8ojsresources', array() );
	if ( ! is_array( $resources ) || empty( $resources ) ) return;

	$compiled = '';

	foreach ( $resources as $resource ) {
		$strategy = isset( $resource['strategy'] ) ? (string) $resource['strategy'] : 'none';
		if ( $strategy !== 'inline' ) continue;

		$path = isset( $resource['path'] ) ? trim( (string) $resource['path'] ) : '';
		if ( $path === '' ) continue;

		$abs = p8ooptimizeurlorpathtoabs( $path );
		if ( $abs === '' || ! is_file( $abs ) || ! is_readable( $abs ) ) continue;

		$js = file_get_contents( $abs );
		if ( ! is_string( $js ) || $js === '' ) continue;

		$compiled .= "\n/* p8o optimize inline: {$path} */\n" . $js . "\n";

		$h = isset( $resource['handle'] ) ? trim( (string) $resource['handle'] ) : '';
		if ( $h !== '' ) {
			wp_dequeue_script( $h );
			wp_deregister_script( $h );
		}
	}

	if ( $compiled === '' ) return;

	$container_handle = 'p8o-inline-js';
	wp_register_script( $container_handle, null, array(), '0.2.0', true );
	wp_enqueue_script( $container_handle );
	wp_add_inline_script( $container_handle, $compiled, 'after' );
}, PHP_INT_MAX );

/**
 * Prevent loading sources for scripts that were inlined.
 */
add_filter( 'script_loader_src', function ( $src, $handle ) {
	if ( (string) get_option( 'p8ooptimizeenabled', '1' ) !== '1' ) return $src;

	$rules = p8ooptimizecollectinlinedjsrules();

	if ( isset( $rules['handles'][ $handle ] ) ) return '';
	if ( ! empty( $rules['paths'] ) ) {
		foreach ( $rules['paths'] as $needle ) {
			if ( $needle !== '' && strpos( (string) $src, (string) $needle ) !== false ) return '';
		}
	}

	return $src;
}, PHP_INT_MAX, 2 );

/**
 * Add defer/async attributes based on p8ojsresources.
 */
function p8ooptimizejsmodifytag( $tag, $handle, $src ) {
	if ( (string) get_option( 'p8ooptimizeenabled', '1' ) !== '1' ) return $tag;

	$resources = get_option( 'p8ojsresources', array() );
	if ( ! is_array( $resources ) || empty( $resources ) ) return $tag;

	foreach ( $resources as $resource ) {
		if ( ! p8ooptimizejsmatchresource( $resource, $handle, $src ) ) continue;

		$strategy = isset( $resource['strategy'] ) ? (string) $resource['strategy'] : 'none';
		if ( $strategy === 'none' || $strategy === 'inline' ) return $tag;

		if ( $strategy === 'defer' && stripos( $tag, ' defer' ) === false ) {
			return str_replace( '<script ', '<script defer ', $tag );
		}

		if ( $strategy === 'async' && stripos( $tag, ' async' ) === false ) {
			return str_replace( '<script ', '<script async ', $tag );
		}

		return $tag;
	}

	return $tag;
}
add_filter( 'script_loader_tag', 'p8ooptimizejsmodifytag', 10, 3 );

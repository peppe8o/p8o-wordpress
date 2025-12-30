<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Inline CSS: reads p8ocssresources rows where strategy=inline,
 * loads local file contents, dequeues the original handle/path match,
 * and outputs a single inline stylesheet.
 */
add_action( 'wp_enqueue_scripts', function () {
	if ( (string) get_option( 'p8ooptimizeenabled', '1' ) !== '1' ) return;

	$resources = get_option( 'p8ocssresources', array() );
	if ( ! is_array( $resources ) || empty( $resources ) ) return;

	$compiled = '';

	foreach ( $resources as $resource ) {
		$strategy = isset( $resource['strategy'] ) ? (string) $resource['strategy'] : 'none';
		if ( $strategy !== 'inline' ) continue;

		$path = isset( $resource['path'] ) ? trim( (string) $resource['path'] ) : '';
		if ( $path === '' ) continue;

		$abs = p8ooptimizeurlorpathtoabs( $path );
		if ( $abs === '' || ! is_file( $abs ) || ! is_readable( $abs ) ) continue;

		$css = file_get_contents( $abs );
		if ( ! is_string( $css ) || $css === '' ) continue;

		$compiled .= "\n/* p8o optimize inline: {$path} */\n" . $css . "\n";

		$h = isset( $resource['handle'] ) ? trim( (string) $resource['handle'] ) : '';
		if ( $h !== '' ) {
			wp_dequeue_style( $h );
			wp_deregister_style( $h );
		}
	}

	if ( $compiled === '' ) return;

	wp_register_style( 'p8o-inline-css', false );
	wp_enqueue_style( 'p8o-inline-css' );
	wp_add_inline_style( 'p8o-inline-css', $compiled );
}, 20 );

/**
 * Modify CSS tags for preload/defer strategies.
 * Uses p8ocssresources matching by handle first; otherwise by href substring path.
 */
function p8ooptimizecssmodifytag( $tag, $handle, $href, $media ) {
	if ( (string) get_option( 'p8ooptimizeenabled', '1' ) !== '1' ) return $tag;

	$resources = get_option( 'p8ocssresources', array() );
	if ( ! is_array( $resources ) || empty( $resources ) ) return $tag;

	foreach ( $resources as $resource ) {
		if ( ! p8ooptimizecssmatchresource( $resource, $handle, $href ) ) continue;

		$strategy = isset( $resource['strategy'] ) ? (string) $resource['strategy'] : 'none';
		if ( $strategy === 'none' || $strategy === 'inline' ) return $tag;

		$href_esc  = esc_url( $href );
		$media_val = $media ? $media : 'all';
		$media_esc = esc_attr( $media_val );

		$noscript = '<noscript>' . $tag . '</noscript>';

		if ( $strategy === 'preload' ) {
			return '<link rel="preload" as="style" href="' . $href_esc . '" media="' . $media_esc . '" onload="this.onload=null;this.rel=\'stylesheet\'">'
				. $noscript;
		}

		if ( $strategy === 'defer' ) {
			return '<link rel="stylesheet" href="' . $href_esc . '" media="print" onload="this.onload=null;this.media=\'' . $media_esc . '\'">'
				. $noscript;
		}

		return $tag;
	}

	return $tag;
}
add_filter( 'style_loader_tag', 'p8ooptimizecssmodifytag', 20, 4 );
error_log('p8o css feature running');


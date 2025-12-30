<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/* CSS: inline */
add_action( 'wp_enqueue_scripts', function () {
	$resources = get_option( 'p8o_css_resources', array() );
	if ( ! is_array( $resources ) || empty( $resources ) ) return;

	$compiled_css = '';

	foreach ( $resources as $resource ) {
		$strategy = isset($resource['strategy']) ? $resource['strategy'] : 'none';
		if ( $strategy !== 'inline' ) continue;

		$path = isset($resource['path']) ? trim((string)$resource['path']) : '';
		if ( $path === '' ) continue;

		$abs = p8o_optimize_url_or_path_to_abs( $path );
		if ( $abs && is_file( $abs ) && is_readable( $abs ) ) {
			$css = file_get_contents( $abs );
			if ( is_string( $css ) && $css !== '' ) $compiled_css .= "\n" . $css;
		}

		$h = isset($resource['handle']) ? trim((string)$resource['handle']) : '';
		if ( $h !== '' ) {
			wp_dequeue_style( $h );
			wp_deregister_style( $h );
		}
	}

	if ( $compiled_css !== '' ) {
		wp_register_style( 'p8o-inline-css', false );
		wp_enqueue_style( 'p8o-inline-css' );
		wp_add_inline_style( 'p8o-inline-css', $compiled_css );
	}
}, 20 );

/* CSS: preload/defer */
function p8o_optimize_css_modify_tag( $tag, $handle, $href, $media ) {
	$resources = get_option( 'p8o_css_resources', array() );
	if ( ! is_array( $resources ) || empty( $resources ) ) return $tag;

	foreach ( $resources as $resource ) {
		if ( ! p8o_optimize_css_match_resource( $resource, $handle, $href ) ) continue;

		$strategy = isset($resource['strategy']) ? $resource['strategy'] : 'none';
		if ( $strategy === 'async' ) $strategy = 'preload';

		if ( $strategy === 'none' || $strategy === 'inline' ) return $tag;

		$noscript = '<noscript>' . $tag . '</noscript>';
		$href_esc  = esc_url( $href );
		$media_esc = $media ? esc_attr( $media ) : 'all';

		if ( $strategy === 'preload' ) {
			$preload = '<link rel="preload" as="style" href="' . $href_esc . '" media="' . $media_esc . '" onload="this.onload=null;this.rel=\'stylesheet\'">';
			return $preload . $noscript;
		}

		if ( $strategy === 'defer' ) {
			$defer = '<link rel="stylesheet" href="' . $href_esc . '" media="print" onload="this.onload=null;this.media=\'all\'">';
			return $defer . $noscript;
		}

		return $tag;
	}

	return $tag;
}
add_filter( 'style_loader_tag', 'p8o_optimize_css_modify_tag', 20, 4 );

<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * External JS:
 * - Add resource hints (preconnect/dns-prefetch/preload) in <head>
 * - Add async/defer attributes to matching enqueued script tags
 */

function p8o_optimize_is_enabled() {
	return get_option( 'p8o_optimize_enabled', '1' ) === '1';
}

/**
 * Resource hints in <head>.
 */
add_action( 'wp_head', function () {
	if ( ! p8o_optimize_is_enabled() ) return;

	$rules = get_option( 'p8o_external_js_hints', array() );
	if ( ! is_array( $rules ) ) return;

	foreach ( $rules as $rule ) {
		$url      = isset( $rule['url'] ) ? trim( (string) $rule['url'] ) : '';
		$strategy = isset( $rule['strategy'] ) ? (string) $rule['strategy'] : 'none';

		if ( $url === '' || $strategy === 'none' ) continue;

		// preconnect + dns-prefetch fallback
		if ( $strategy === 'preconnect' ) {
			echo '<link rel="preconnect" href="' . esc_url( $url ) . '" crossorigin>' . "\n";
			echo '<link rel="dns-prefetch" href="' . esc_url( $url ) . '">' . "\n";
			continue;
		}

		// preload script (best effort)
		if ( $strategy === 'preload' ) {
			echo '<link rel="preload" href="' . esc_url( $url ) . '" as="script" crossorigin>' . "\n";
			continue;
		}
	}
}, 1 );

/**
 * Add async/defer attributes to matching script tags.
 * script_loader_tag filter receives ($tag, $handle, $src). [web:374]
 */
add_filter( 'script_loader_tag', 'p8o_optimize_external_js_attr_filter', 10, 3 );

function p8o_optimize_external_js_attr_filter( $tag, $handle, $src ) {

	if ( ! p8o_optimize_is_enabled() ) return $tag;

	$rules = get_option( 'p8o_external_js_hints', array() );
	if ( ! is_array( $rules ) ) return $tag;

	foreach ( $rules as $rule ) {
		$url  = isset( $rule['url'] ) ? (string) $rule['url'] : '';
		$attr = isset( $rule['attr'] ) ? (string) $rule['attr'] : 'none'; // defer|async|none

		if ( $url === '' ) continue;

		// Match by URL substring
		if ( strpos( $src, $url ) !== false ) {

			// "None" means do not add any attribute.
			if ( $attr !== 'defer' && $attr !== 'async' ) return $tag;

			// Don’t override existing attributes.
			if ( stripos( $tag, ' defer' ) !== false || stripos( $tag, ' async' ) !== false ) return $tag;

			return str_replace( '<script ', '<script ' . $attr . ' ', $tag );
		}
	}

	return $tag;
}

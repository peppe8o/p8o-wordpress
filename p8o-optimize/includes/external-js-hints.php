<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Add async/defer attribute to matching external scripts via script_loader_tag. [web:374]
 * Match is done by URL substring against the generated $src.
 */

add_filter( 'script_loader_tag', 'p8o_optimize_external_js_attr_filter', 10, 3 );

function p8o_optimize_external_js_attr_filter( $tag, $handle, $src ) {

	$rules = get_option( 'p8o_external_js_hints', array() );
	if ( ! is_array( $rules ) ) return $tag;

	foreach ( $rules as $rule ) {
		if ( empty( $rule['url'] ) ) continue;

		$url  = (string) $rule['url'];
		$attr = isset( $rule['attr'] ) ? (string) $rule['attr'] : 'none'; // defer|async|none

		if ( $url !== '' && strpos( $src, $url ) !== false ) {

			// "None" means do not add any attribute.
			if ( $attr !== 'defer' && $attr !== 'async' ) return $tag;

			// Don’t override existing attributes.
			if ( stripos( $tag, ' defer' ) !== false || stripos( $tag, ' async' ) !== false ) return $tag;

			return str_replace( '<script ', '<script ' . $attr . ' ', $tag );
		}
	}

	return $tag;
}

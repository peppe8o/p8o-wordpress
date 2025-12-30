<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Outputs resource hints for external scripts from p8oexternaljshints.
 *
 * Row shape:
 * - url  (string)
 * - hint (preconnect|preload|none)
 * - attr (defer|async|none)  // attribute handled in JS tag filter below
 */
add_action( 'wp_head', function () {
	if ( (string) get_option( 'p8ooptimizeenabled', '1' ) !== '1' ) return;

	$hints = get_option( 'p8oexternaljshints', array() );
	if ( ! is_array( $hints ) || empty( $hints ) ) return;

	$out = array();

	foreach ( $hints as $row ) {
		$url  = isset( $row['url'] ) ? trim( (string) $row['url'] ) : '';
		$hint = isset( $row['hint'] ) ? (string) $row['hint'] : 'preconnect';
		if ( $url === '' || $hint === 'none' ) continue;

		$origin = p8ooptimizeexternaloriginfromurl( $url );
		if ( $origin === '' ) continue;

		if ( $hint === 'preconnect' ) {
			$out[] = '<link rel="preconnect" href="' . esc_url( $origin ) . '" crossorigin>';
			$out[] = '<link rel="dns-prefetch" href="' . esc_url( $origin ) . '">';
		} elseif ( $hint === 'preload' ) {
			$out[] = '<link rel="preload" as="script" href="' . esc_url( $url ) . '" crossorigin>';
		}
	}

	if ( empty( $out ) ) return;

	echo "\n<!-- p8o optimize external JS hints -->\n" . implode( "\n", array_unique( $out ) ) . "\n";
}, 1 );

/**
 * Add defer/async to external script tags (same rows).
 * Matching is done by substring match on src URL.
 */
add_filter( 'script_loader_tag', function( $tag, $handle, $src ) {
	if ( (string) get_option( 'p8ooptimizeenabled', '1' ) !== '1' ) return $tag;

	$hints = get_option( 'p8oexternaljshints', array() );
	if ( ! is_array( $hints ) || empty( $hints ) ) return $tag;

	foreach ( $hints as $row ) {
		$url  = isset( $row['url'] ) ? trim( (string) $row['url'] ) : '';
		$attr = isset( $row['attr'] ) ? (string) $row['attr'] : 'none';

		if ( $url === '' || $attr === 'none' ) continue;
		if ( strpos( (string) $src, $url ) === false ) continue;

		if ( $attr === 'defer' && stripos( $tag, ' defer' ) === false ) {
			return str_replace( '<script ', '<script defer ', $tag );
		}
		if ( $attr === 'async' && stripos( $tag, ' async' ) === false ) {
			return str_replace( '<script ', '<script async ', $tag );
		}

		return $tag;
	}

	return $tag;
}, 20, 3 );

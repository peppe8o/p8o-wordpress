<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/* Images options: disable WP auto attributes */
add_action( 'init', function () {

	// Disable srcset/sizes. [web:61]
	if ( get_option( 'p8o_disable_wp_srcset_sizes', '0' ) === '1' ) {
		add_filter( 'wp_calculate_image_srcset', '__return_false', 9999 );
		add_filter( 'wp_calculate_image_sizes', '__return_false', 9999 );
		add_filter( 'wp_img_tag_add_srcset_and_sizes_attr', '__return_false', 9999 );

		add_filter( 'wp_get_attachment_image_attributes', function( $attr ) {
			if ( isset( $attr['srcset'] ) ) unset( $attr['srcset'] );
			if ( isset( $attr['sizes'] ) ) unset( $attr['sizes'] );
			return $attr;
		}, 9999, 1 );
	}

	// Disable WP adding width/height. [web:99]
	if ( get_option( 'p8o_disable_wp_width_height', '0' ) === '1' ) {
		add_filter( 'wp_img_tag_add_width_and_height_attr', '__return_false', 9999 );
	}

	// Disable WP native lazy-loading. [web:84]
	if ( get_option( 'p8o_disable_wp_lazy_loading', '0' ) === '1' ) {
		add_filter( 'wp_lazy_loading_enabled', '__return_false', 9999 );
	}

}, 1 );

/* IMAGES: fetchpriority="high" */
function p8o_optimize_image_fetchpriority( $attr, $attachment, $size ) {
	$rules = get_option( 'p8o_image_paths', array() );
	if ( ! is_array( $rules ) || empty( $rules ) ) return $attr;
	if ( empty( $attr['src'] ) ) return $attr;

	$src = (string) $attr['src'];

	foreach ( $rules as $rule ) {
		$pattern  = isset($rule['path']) ? p8o_optimize_norm($rule['path']) : '';
		$strategy = isset($rule['strategy']) ? $rule['strategy'] : 'none';

		if ( $strategy !== 'high' || $pattern === '' ) continue;

		if ( strpos( $src, $pattern ) !== false ) {
			$attr['fetchpriority'] = 'high';
			break;
		}
	}

	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'p8o_optimize_image_fetchpriority', 10, 3 );

/* IMAGES: prevent CLS (reserve space) */
function p8o_optimize_images_prevent_cls( $attr, $attachment, $size ) {
	$enabled = get_option( 'p8o_images_cls', '0' );
	if ( $enabled !== '1' ) return $attr;

	if ( empty( $attachment ) || empty( $attachment->ID ) ) return $attr;

	$has_w = isset( $attr['width'] ) && is_numeric( $attr['width'] ) && (int) $attr['width'] > 0;
	$has_h = isset( $attr['height'] ) && is_numeric( $attr['height'] ) && (int) $attr['height'] > 0;

	$add_aspect_ratio = function( $attr, $w, $h ) {
		$w = (int) $w; $h = (int) $h;
		if ( $w <= 0 || $h <= 0 ) return $attr;

		$style = isset( $attr['style'] ) ? (string) $attr['style'] : '';
		if ( stripos( $style, 'aspect-ratio' ) !== false ) return $attr;

		$style = rtrim( $style );
		if ( $style !== '' && substr( $style, -1 ) !== ';' ) $style .= ';';
		$style .= 'aspect-ratio:' . $w . '/' . $h . ';height:auto;';
		$attr['style'] = $style;

		return $attr;
	};

	if ( $has_w && $has_h ) return $add_aspect_ratio( $attr, $attr['width'], $attr['height'] );

	$meta = wp_get_attachment_metadata( $attachment->ID );
	if ( empty( $meta ) || empty( $meta['width'] ) || empty( $meta['height'] ) ) return $attr;

	$w = 0; $h = 0;

	if ( is_array( $size ) && count( $size ) >= 2 ) {
		$w = (int) $size[0];
		$h = (int) $size[1];
	} else {
		if ( is_string( $size ) && $size !== 'full' && ! empty( $meta['sizes'][ $size ]['width'] ) && ! empty( $meta['sizes'][ $size ]['height'] ) ) {
			$w = (int) $meta['sizes'][ $size ]['width'];
			$h = (int) $meta['sizes'][ $size ]['height'];
		} else {
			$w = (int) $meta['width'];
			$h = (int) $meta['height'];
		}
	}

	if ( $w > 0 && $h > 0 ) {
		if ( ! $has_w ) $attr['width']  = (string) $w;
		if ( ! $has_h ) $attr['height'] = (string) $h;
		$attr = $add_aspect_ratio( $attr, $w, $h );
	}

	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'p8o_optimize_images_prevent_cls', 20, 3 );

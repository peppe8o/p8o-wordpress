<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function p8o_optimize_images_enabled() {
	return get_option( 'p8o_optimize_enabled', '1' ) === '1';
}

/**
 * Placeholder for your image optimizations:
 * - fetchpriority=high for matching attachment images (p8o_image_paths)
 * - CLS: add missing width/height + aspect-ratio for attachment images (p8o_images_cls)
 * - disable WP srcset/sizes, width/height, lazy loading (options)
 */
add_filter( 'wp_get_attachment_image_attributes', function( $attr, $attachment, $size ) {
	if ( ! p8o_optimize_images_enabled() ) return $attr;

	// TODO: Move your existing image attribute logic here.

	return $attr;
}, 10, 3 );

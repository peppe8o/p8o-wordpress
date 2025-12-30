<?php
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'wp_enqueue_scripts', function () {
	$rules = p8o_optimize_get_ad_cls_rules();
	if ( empty( $rules ) ) return;

	$css = "/* p8o optimize: ad CLS placeholders */\n";
	foreach ( $rules as $r ) {
		$css .= "#". $r['id'] . "{min-height:" . (int) $r['max_height'] . "px;}\n";
	}

	wp_register_style( 'p8o-ad-cls', false );
	wp_enqueue_style( 'p8o-ad-cls' );
	wp_add_inline_style( 'p8o-ad-cls', $css );
}, 1 );

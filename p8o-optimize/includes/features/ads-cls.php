<?php
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'wp_enqueue_scripts', function () {
	if ( (string) get_option( 'p8ooptimizeenabled', '1' ) !== '1' ) return;

	$rules = function_exists( 'p8ooptimizegetadclsrules' ) ? p8ooptimizegetadclsrules() : array();
	if ( empty( $rules ) ) return;

	$css = "/* p8o optimize: ad CLS placeholders */\n";
	foreach ( $rules as $r ) {
		$id = isset( $r['id'] ) ? (string) $r['id'] : '';
		$mh = isset( $r['max_height'] ) ? (int) $r['max_height'] : 0;

		if ( $id === '' || $mh <= 0 ) continue;
		$css .= '#' . $id . '{min-height:' . $mh . "px;}\n";
	}

	if ( $css === "/* p8o optimize: ad CLS placeholders */\n" ) return;

	wp_register_style( 'p8o-ad-cls', false );
	wp_enqueue_style( 'p8o-ad-cls' );
	wp_add_inline_style( 'p8o-ad-cls', $css );
}, 1 );

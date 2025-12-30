<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function p8o_optimize_norm( $s ) { return trim( (string) $s ); }

function p8o_optimize_url_or_path_to_abs( $path_or_url ) {
	$p = trim( (string) $path_or_url );
	if ( $p === '' ) return '';
	$p = strtok( $p, '?' );

	if ( preg_match( '~^https?://~i', $p ) ) {
		$site = site_url( '/' );
		if ( strpos( $p, $site ) === 0 ) {
			$p = ltrim( substr( $p, strlen( $site ) ), '/' );
		} else {
			return '';
		}
	}
	return ABSPATH . ltrim( $p, '/' );
}

function p8o_optimize_css_match_resource( $resource, $handle, $href ) {
	$r_handle = isset($resource['handle']) ? p8o_optimize_norm($resource['handle']) : '';
	$r_path   = isset($resource['path']) ? p8o_optimize_norm($resource['path']) : '';
	if ( $r_handle !== '' ) return ( $handle === $r_handle );
	return ( $r_path !== '' && strpos( (string) $href, $r_path ) !== false );
}

function p8o_optimize_js_match_resource( $resource, $handle, $src ) {
	$r_handle = isset($resource['handle']) ? p8o_optimize_norm($resource['handle']) : '';
	$r_path   = isset($resource['path']) ? p8o_optimize_norm($resource['path']) : '';
	if ( $r_handle !== '' ) return ( $handle === $r_handle );
	return ( $r_path !== '' && strpos( (string) $src, $r_path ) !== false );
}

function p8o_optimize_collect_inlined_js_rules() {
	$resources = get_option( 'p8o_js_resources', array() );
	if ( ! is_array( $resources ) ) $resources = array();

	$out = array( 'handles' => array(), 'paths' => array() );

	foreach ( $resources as $r ) {
		$strategy = isset($r['strategy']) ? (string) $r['strategy'] : 'none';
		if ( $strategy !== 'inline' ) continue;

		$h = isset($r['handle']) ? trim((string)$r['handle']) : '';
		$p = isset($r['path']) ? trim((string)$r['path']) : '';

		if ( $h !== '' ) $out['handles'][$h] = true;
		if ( $p !== '' ) $out['paths'][] = $p;
	}

	return $out;
}

function p8o_optimize_get_ad_cls_rules() {
	$rules = get_option( 'p8o_ad_cls_rules', array() );
	if ( ! is_array( $rules ) ) $rules = array();

	$out = array();

	foreach ( $rules as $r ) {
		$enabled = isset($r['enabled']) ? (string) $r['enabled'] : '0';
		if ( $enabled !== '1' ) continue;

		$id = isset($r['id']) ? preg_replace( '~[^A-Za-z0-9\-\_\:\.]~', '', (string)$r['id'] ) : '';
		if ( $id === '' ) continue;

		$mh = isset($r['max_height']) ? (int) $r['max_height'] : 0;
		if ( $mh <= 0 ) continue;

		$out[] = array( 'id' => $id, 'max_height' => $mh );
	}

	return $out;
}

function p8o_optimize_external_origin_from_url( $url ) {
	$url = trim( (string) $url );
	if ( $url === '' ) return '';
	$parts = wp_parse_url( $url );
	if ( empty( $parts['scheme'] ) || empty( $parts['host'] ) ) return '';
	$scheme = strtolower( $parts['scheme'] );
	if ( $scheme !== 'http' && $scheme !== 'https' ) return '';
	return $scheme . '://' . strtolower( $parts['host'] );
}

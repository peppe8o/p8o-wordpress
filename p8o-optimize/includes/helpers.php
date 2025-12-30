<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function p8ooptimizenorm( $s ) {
	return trim( (string) $s );
}

function p8ooptimizeurlorpathtoabs( $pathorurl ) {
	$p = trim( (string) $pathorurl );
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

function p8ooptimizecssmatchresource( $resource, $handle, $href ) {
	$rhandle = isset( $resource['handle'] ) ? p8ooptimizenorm( $resource['handle'] ) : '';
	$rpath   = isset( $resource['path'] ) ? p8ooptimizenorm( $resource['path'] ) : '';

	if ( $rhandle !== '' ) return ( (string) $handle === $rhandle );
	return ( $rpath !== '' && strpos( (string) $href, $rpath ) !== false );
}

function p8ooptimizejsmatchresource( $resource, $handle, $src ) {
	$rhandle = isset( $resource['handle'] ) ? p8ooptimizenorm( $resource['handle'] ) : '';
	$rpath   = isset( $resource['path'] ) ? p8ooptimizenorm( $resource['path'] ) : '';

	if ( $rhandle !== '' ) return ( (string) $handle === $rhandle );
	return ( $rpath !== '' && strpos( (string) $src, $rpath ) !== false );
}

function p8ooptimizecollectinlinedjsrules() {
	$resources = get_option( 'p8ojsresources', array() );
	if ( ! is_array( $resources ) ) $resources = array();

	$out = array( 'handles' => array(), 'paths' => array() );

	foreach ( $resources as $r ) {
		$strategy = isset( $r['strategy'] ) ? (string) $r['strategy'] : 'none';
		if ( $strategy !== 'inline' ) continue;

		$h = isset( $r['handle'] ) ? trim( (string) $r['handle'] ) : '';
		$p = isset( $r['path'] ) ? trim( (string) $r['path'] ) : '';

		if ( $h !== '' ) $out['handles'][ $h ] = true;
		if ( $p !== '' ) $out['paths'][] = $p;
	}

	return $out;
}

function p8ooptimizegetadclsrules() {
	$rules = get_option( 'p8oadclsrules', array() );
	if ( ! is_array( $rules ) ) $rules = array();

	$out = array();

	foreach ( $rules as $r ) {
		$enabled = isset( $r['enabled'] ) ? (string) $r['enabled'] : '0';
		if ( $enabled !== '1' ) continue;

		$id = isset( $r['id'] ) ? preg_replace( '~[^A-Za-z0-9\-\_\:\.]~', '', (string) $r['id'] ) : '';
		if ( $id === '' ) continue;

		$mh = isset( $r['max_height'] ) ? (int) $r['max_height'] : 0;
		if ( $mh <= 0 ) continue;

		$out[] = array( 'id' => $id, 'max_height' => $mh );
	}

	return $out;
}

function p8ooptimizeexternaloriginfromurl( $url ) {
	$url = trim( (string) $url );
	if ( $url === '' ) return '';

	$parts = wp_parse_url( $url );
	if ( empty( $parts['scheme'] ) || empty( $parts['host'] ) ) return '';

	$scheme = strtolower( $parts['scheme'] );
	if ( $scheme !== 'http' && $scheme !== 'https' ) return '';

	return $scheme . '://' . strtolower( $parts['host'] );
}

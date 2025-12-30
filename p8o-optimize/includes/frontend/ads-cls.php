<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function p8o_optimize_ads_enabled() {
	return get_option( 'p8o_optimize_enabled', '1' ) === '1';
}

/**
 * Placeholder for your ad CLS fix logic based on p8o_ad_cls_rules.
 * Typically outputs inline CSS or JS to set min-height placeholders.
 */
add_action( 'wp_head', function () {
	if ( ! p8o_optimize_ads_enabled() ) return;

	// TODO: Move your existing ad CLS placeholder output here.
}, 20 );

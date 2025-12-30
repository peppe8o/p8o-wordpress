<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function p8o_optimize_css_enabled() {
	return get_option( 'p8o_optimize_enabled', '1' ) === '1';
}

/**
 * Placeholder for your CSS logic (inline/preload/defer/none) driven by p8o_css_resources.
 * Keep your existing implementation here; add this early-return at the top of each hook.
 */
add_action( 'wp_enqueue_scripts', function () {
	if ( ! p8o_optimize_css_enabled() ) return;

	// TODO: Move your existing CSS optimization logic here.
	// Example: read get_option('p8o_css_resources', []) and act accordingly.
}, 999 );

<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function p8o_optimize_js_enabled() {
	return get_option( 'p8o_optimize_enabled', '1' ) === '1';
}

/**
 * Placeholder for your JS logic (inline/defer/async/none) driven by p8o_js_resources.
 * Keep your existing implementation here; add this early-return at the top of each hook.
 */
add_action( 'wp_enqueue_scripts', function () {
	if ( ! p8o_optimize_js_enabled() ) return;

	// TODO: Move your existing JS optimization logic here.
}, 999 );

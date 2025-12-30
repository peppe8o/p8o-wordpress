<?php
/**
 * Plugin Name:       p8o optimize
 * Description:       Performance optimizations (CSS/JS/Image/Resource hints).
 * Version:           1.0.0
 * Author:            p8o
 * License:           GPLv2 or later
 * Text Domain:       p8o-optimize
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Use plugin_dir_path(__FILE__) to resolve includes from the plugin base directory. [web:222]
 */
define( 'P8O_OPTIMIZE_PATH', plugin_dir_path( __FILE__ ) );
define( 'P8O_OPTIMIZE_FILE', __FILE__ );

/**
 * Admin vs Frontend loading (conditional loading is a recommended practice). [web:456][web:222]
 */
if ( is_admin() ) {
	require_once P8O_OPTIMIZE_PATH . 'includes/admin/admin.php';
} else {
	require_once P8O_OPTIMIZE_PATH . 'includes/frontend/frontend.php';
}

/**
 * Optional: ensure options exist (non-destructive defaults).
 * This runs once on activation.
 */
register_activation_hook( __FILE__, 'p8o_optimize_activate' );

function p8o_optimize_activate() {
	// Global
	add_option( 'p8o_optimize_enabled', '1' );
	add_option( 'p8o_optimize_cleanup_on_delete', '0' );

	// CSS / JS
	add_option( 'p8o_css_resources', array() );
	add_option( 'p8o_js_resources', array() );
	add_option( 'p8o_external_js_hints', array() );

	// Images
	add_option( 'p8o_image_paths', array() );
	add_option( 'p8o_images_cls', '0' );
	add_option( 'p8o_disable_wp_srcset_sizes', '0' );
	add_option( 'p8o_disable_wp_width_height', '0' );
	add_option( 'p8o_disable_wp_lazy_loading', '0' );

	// Ads CLS
	add_option( 'p8o_ad_cls_rules', array() );
}

<?php
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) exit;

$cleanup = get_option( 'p8o_optimize_cleanup_on_delete', '0' );
if ( $cleanup !== '1' ) return;

$option_names = array(
	'p8o_optimize_enabled',
	'p8o_optimize_cleanup_on_delete',

	'p8o_css_resources',

	'p8o_js_resources',
	'p8o_external_js_hints',

	'p8o_image_paths',
	'p8o_images_cls',
	'p8o_disable_wp_srcset_sizes',
	'p8o_disable_wp_width_height',
	'p8o_disable_wp_lazy_loading',

	'p8o_ad_cls_rules',
);

foreach ( $option_names as $name ) {
	delete_option( $name );
}

// If you add custom tables later, drop them here (wpdb->query("DROP TABLE ...")).

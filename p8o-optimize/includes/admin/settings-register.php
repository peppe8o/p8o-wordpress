<?php
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'admin_init', function () {

	// General
	register_setting( 'p8o_optimize_settings_general', 'p8o_optimize_enabled' );
	register_setting( 'p8o_optimize_settings_general', 'p8o_optimize_cleanup_on_delete' );

	// CSS
	register_setting( 'p8o_optimize_settings_css', 'p8o_css_resources' );

	// JS
	register_setting( 'p8o_optimize_settings_js', 'p8o_js_resources' );
	register_setting( 'p8o_optimize_settings_js', 'p8o_external_js_hints' );

	// Images
	register_setting( 'p8o_optimize_settings_images', 'p8o_image_paths' );
	register_setting( 'p8o_optimize_settings_images', 'p8o_images_cls' );
	register_setting( 'p8o_optimize_settings_images', 'p8o_disable_wp_srcset_sizes' );
	register_setting( 'p8o_optimize_settings_images', 'p8o_disable_wp_width_height' );
	register_setting( 'p8o_optimize_settings_images', 'p8o_disable_wp_lazy_loading' );

	// Ads CLS
	register_setting( 'p8o_optimize_settings_images', 'p8o_ad_cls_rules' );

	/* ==============
	 * General page
	 * ============== */
	$general_page = 'p8o-optimize-general';

	add_settings_section(
		'p8o_optimize_general_section',
		'General',
		'p8o_optimize_general_section_callback',
		$general_page
	);

	add_settings_field(
		'p8o_optimize_enabled',
		'Enable plugin',
		'p8o_optimize_enabled_callback',
		$general_page,
		'p8o_optimize_general_section'
	);

	add_settings_field(
		'p8o_optimize_cleanup_on_delete',
		'Cleanup on delete',
		'p8o_optimize_cleanup_on_delete_callback',
		$general_page,
		'p8o_optimize_general_section'
	);

	/* ==============
	 * CSS page
	 * ============== */
	$css_page = 'p8o-optimize-css';

	add_settings_section(
		'p8o_optimize_css_section',
		'CSS Resources',
		'p8o_optimize_css_section_callback',
		$css_page
	);

	add_settings_field(
		'p8o_css_resources',
		'CSS Resources',
		'p8o_css_resources_callback',
		$css_page,
		'p8o_optimize_css_section'
	);

	/* ==============
	 * JS page
	 * ============== */
	$js_page = 'p8o-optimize-js';

	add_settings_section(
		'p8o_optimize_js_section',
		'JS Resources',
		'p8o_optimize_js_section_callback',
		$js_page
	);

	add_settings_field(
		'p8o_js_resources',
		'JS Resources',
		'p8o_js_resources_callback',
		$js_page,
		'p8o_optimize_js_section'
	);

	add_settings_section(
		'p8o_optimize_external_js_section',
		'External JS (Resource Hints)',
		'p8o_optimize_external_js_section_callback',
		$js_page
	);

	add_settings_field(
		'p8o_external_js_hints',
		'External scripts',
		'p8o_external_js_hints_callback',
		$js_page,
		'p8o_optimize_external_js_section'
	);

	/* ==============
	 * Images page
	 * ============== */
	$img_page = 'p8o-optimize-images';

	add_settings_section(
		'p8o_optimize_image_section',
		'Image Priority',
		'p8o_optimize_image_section_callback',
		$img_page
	);

	add_settings_field(
		'p8o_image_paths',
		'Image Paths',
		'p8o_image_paths_callback',
		$img_page,
		'p8o_optimize_image_section'
	);

	add_settings_section(
		'p8o_optimize_images_cls_section',
		'Images: CLS',
		'p8o_optimize_images_cls_section_callback',
		$img_page
	);

	add_settings_field(
		'p8o_images_cls',
		'Prevent CLS (reserve space)',
		'p8o_images_cls_callback',
		$img_page,
		'p8o_optimize_images_cls_section'
	);

	add_settings_field(
		'p8o_disable_wp_srcset_sizes',
		'Disable WP srcset/sizes',
		'p8o_disable_wp_srcset_sizes_callback',
		$img_page,
		'p8o_optimize_images_cls_section'
	);

	add_settings_field(
		'p8o_disable_wp_width_height',
		'Disable WP width/height',
		'p8o_disable_wp_width_height_callback',
		$img_page,
		'p8o_optimize_images_cls_section'
	);

	add_settings_field(
		'p8o_disable_wp_lazy_loading',
		'Disable WP lazy-loading',
		'p8o_disable_wp_lazy_loading_callback',
		$img_page,
		'p8o_optimize_images_cls_section'
	);

	add_settings_section(
		'p8o_optimize_ad_cls_section',
		'AD CLS fix',
		'p8o_optimize_ad_cls_section_callback',
		$img_page
	);

	add_settings_field(
		'p8o_ad_cls_rules',
		'Ad placeholders',
		'p8o_ad_cls_rules_callback',
		$img_page,
		'p8o_optimize_ad_cls_section'
	);

});

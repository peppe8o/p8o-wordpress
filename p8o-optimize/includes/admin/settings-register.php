<?php
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'admin_init', function () {

	/**
	 * Register settings (option keys).
	 */
	register_setting( 'p8ooptimizesettingsgeneral', 'p8ooptimizeenabled' );
	register_setting( 'p8ooptimizesettingsgeneral', 'p8ooptimizecleanupondelete' );

	register_setting( 'p8ooptimizesettingscss', 'p8ocssresources' );

	register_setting( 'p8ooptimizesettingsjs', 'p8ojsresources' );
	register_setting( 'p8ooptimizesettingsjs', 'p8oexternaljshints' );

	register_setting( 'p8ooptimizesettingsimages', 'p8oimagepaths' );
	register_setting( 'p8ooptimizesettingsimages', 'p8oimagescls' );
	register_setting( 'p8ooptimizesettingsimages', 'p8odisablewpsrcsetsizes' );
	register_setting( 'p8ooptimizesettingsimages', 'p8odisablewpwidthheight' );
	register_setting( 'p8ooptimizesettingsimages', 'p8odisablewplazyloading' );
	register_setting( 'p8ooptimizesettingsimages', 'p8oadclsrules' );

	/**
	 * GENERAL tab
	 */
	$general_page = 'p8o-optimize-general';

	add_settings_section(
		'p8ooptimizegeneralsection',
		'General',
		'p8ooptimizegeneralsectioncallback',
		$general_page
	);

	add_settings_field(
		'p8ooptimizeenabled',
		'Enable plugin',
		'p8ooptimizeenabledcallback',
		$general_page,
		'p8ooptimizegeneralsection'
	);

	add_settings_field(
		'p8ooptimizecleanupondelete',
		'Cleanup on delete',
		'p8ooptimizecleanupondeletecallback',
		$general_page,
		'p8ooptimizegeneralsection'
	);

	/**
	 * CSS tab
	 */
	$css_page = 'p8o-optimize-css';

	add_settings_section(
		'p8ooptimizecsssection',
		'CSS Resources',
		'p8ooptimizecsssectioncallback',
		$css_page
	);

	add_settings_field(
		'p8ocssresources',
		'CSS Resources',
		'p8ocssresourcescallback',
		$css_page,
		'p8ooptimizecsssection'
	);

	/**
	 * JS tab
	 */
	$js_page = 'p8o-optimize-js';

	add_settings_section(
		'p8ooptimizejssection',
		'JS Resources',
		'p8ooptimizejssectioncallback',
		$js_page
	);

	add_settings_field(
		'p8ojsresources',
		'JS Resources',
		'p8ojsresourcescallback',
		$js_page,
		'p8ooptimizejssection'
	);

	add_settings_section(
		'p8ooptimizeexternaljssection',
		'External JS Resource Hints',
		'p8ooptimizeexternaljssectioncallback',
		$js_page
	);

	add_settings_field(
		'p8oexternaljshints',
		'External scripts',
		'p8oexternaljshintscallback',
		$js_page,
		'p8ooptimizeexternaljssection'
	);

	/**
	 * IMAGES tab
	 */
	$img_page = 'p8o-optimize-images';

	add_settings_section(
		'p8ooptimizeimagesection',
		'Image Priority',
		'p8ooptimizeimagesectioncallback',
		$img_page
	);

	add_settings_field(
		'p8oimagepaths',
		'Image Paths',
		'p8oimagepathscallback',
		$img_page,
		'p8ooptimizeimagesection'
	);

	add_settings_section(
		'p8ooptimizeimagesclssection',
		'Images CLS',
		'p8ooptimizeimagesclssectioncallback',
		$img_page
	);

	add_settings_field(
		'p8oimagescls',
		'Prevent CLS (reserve space)',
		'p8oimagesclscallback',
		$img_page,
		'p8ooptimizeimagesclssection'
	);

	add_settings_field(
		'p8odisablewpsrcsetsizes',
		'Disable WP srcset/sizes',
		'p8odisablewpsrcsetsizescallback',
		$img_page,
		'p8ooptimizeimagesclssection'
	);

	add_settings_field(
		'p8odisablewpwidthheight',
		'Disable WP width/height',
		'p8odisablewpwidthheightcallback',
		$img_page,
		'p8ooptimizeimagesclssection'
	);

	add_settings_field(
		'p8odisablewplazyloading',
		'Disable WP lazy-loading',
		'p8odisablewplazyloadingcallback',
		$img_page,
		'p8ooptimizeimagesclssection'
	);

	add_settings_section(
		'p8ooptimizeadclssection',
		'AD CLS fix',
		'p8ooptimizeadclssectioncallback',
		$img_page
	);

	add_settings_field(
		'p8oadclsrules',
		'Ad placeholders',
		'p8oadclsrulescallback',
		$img_page,
		'p8ooptimizeadclssection'
	);
} );

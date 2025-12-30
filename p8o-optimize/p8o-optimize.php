<?php
/*
Plugin Name: p8o optimize
Description: Performance optimizations (CSS/JS/Images/Resource hints).
Version: 0.0.2
Author: p8o
License: GPLv2 or later
Text Domain: p8o-optimize
*/

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'P8OOPTIMIZEPATH', plugin_dir_path( __FILE__ ) );
define( 'P8OOPTIMIZEFILE', __FILE__ );

// Load shared helpers first (safe for both admin + frontend).
require_once P8OOPTIMIZEPATH . 'includes/helpers.php';

// Admin vs Frontend bootstrap.
if ( is_admin() ) {
	require_once P8OOPTIMIZEPATH . 'includes/admin/bootstrap.php';
} else {
	require_once P8OOPTIMIZEPATH . 'includes/frontend/bootstrap.php';
}

// Activation defaults (non-destructive).
register_activation_hook( __FILE__, 'p8ooptimizeactivate' );
function p8ooptimizeactivate() {
	add_option( 'p8ooptimizeenabled', 1 );
	add_option( 'p8ooptimizecleanupondelete', 0 );

	add_option( 'p8ocssresources', array() );
	add_option( 'p8ojsresources', array() );
	add_option( 'p8oexternaljshints', array() );

	add_option( 'p8oimagepaths', array() );
	add_option( 'p8oimagescls', 0 );
	add_option( 'p8odisablewpsrcsetsizes', 0 );
	add_option( 'p8odisablewpwidthheight', 0 );
	add_option( 'p8odisablewplazyloading', 0 );

	add_option( 'p8oadclsrules', array() );
}

<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function p8ooptimizegeneralsectioncallback() {
	echo '<p>Global plugin settings, import/export, and uninstall cleanup.</p>';
}

function p8ooptimizeenabledcallback() {
	$v = get_option( 'p8ooptimizeenabled', '1' );
	echo '<label>';
	echo '<input type="checkbox" name="p8ooptimizeenabled" value="1" ' . checked( $v, '1', false ) . '>';
	echo ' Enable all optimizations';
	echo '</label>';
}

function p8ooptimizecleanupondeletecallback() {
	$v = get_option( 'p8ooptimizecleanupondelete', '0' );
	echo '<label>';
	echo '<input type="checkbox" name="p8ooptimizecleanupondelete" value="1" ' . checked( $v, '1', false ) . '>';
	echo ' Clean all data/options on plugin delete (uninstall)';
	echo '</label>';
}

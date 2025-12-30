<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function p8o_optimize_general_section_callback() {
	echo '<p>Global plugin settings, import/export, and uninstall cleanup.</p>';
}

function p8o_optimize_enabled_callback() {
	$v = get_option( 'p8o_optimize_enabled', '1' );
	?>
	<label>
		<input type="checkbox" name="p8o_optimize_enabled" value="1" <?php checked( $v, '1' ); ?>>
		Enable all optimizations
	</label>
	<?php
}

function p8o_optimize_cleanup_on_delete_callback() {
	$v = get_option( 'p8o_optimize_cleanup_on_delete', '0' );
	?>
	<label>
		<input type="checkbox" name="p8o_optimize_cleanup_on_delete" value="1" <?php checked( $v, '1' ); ?>>
		Clean all data/options on plugin delete (uninstall)
	</label>
	<?php
}

<?php
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'admin_menu', function () {
	add_options_page(
		'p8o optimize',
		'p8o optimize',
		'manage_options',
		'p8o-optimize',
		'p8o_optimize_settings_page'
	);
});

function p8o_optimize_settings_page() {

	$tabs = array(
		'general' => 'General',
		'css'     => 'CSS',
		'js'      => 'Javascript',
		'images'  => 'Images',
	);

	$current_tab = isset( $_GET['tab'], $tabs[ $_GET['tab'] ] )
		? sanitize_key( $_GET['tab'] )
		: 'general';

	// Only import on General tab
	if ( $current_tab === 'general' ) {
		p8o_optimize_handle_import_only();
	}

	$page_slug    = 'p8o-optimize-' . $current_tab;
	$option_group = 'p8o_optimize_settings_' . $current_tab;
	?>
	<div class="wrap">
		<h1>p8o optimize</h1>

		<nav class="nav-tab-wrapper">
			<?php foreach ( $tabs as $tab => $label ) :
				$url = add_query_arg(
					array( 'page' => 'p8o-optimize', 'tab' => $tab ),
					admin_url( 'options-general.php' )
				);
				$active = ( $tab === $current_tab ) ? ' nav-tab-active' : '';
				?>
				<a class="nav-tab<?php echo esc_attr( $active ); ?>" href="<?php echo esc_url( $url ); ?>">
					<?php echo esc_html( $label ); ?>
				</a>
			<?php endforeach; ?>
		</nav>

		<?php settings_errors( 'p8o-optimize' ); ?>

		<form method="post" action="options.php">
			<?php
			settings_fields( $option_group );
			do_settings_sections( $page_slug );
			submit_button();
			?>
		</form>

		<?php if ( $current_tab === 'general' ) : ?>
			<hr />
			<h2>Import / Export</h2>

			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="margin-top:10px;">
				<?php wp_nonce_field( 'p8o_optimize_export_json' ); ?>
				<input type="hidden" name="action" value="p8o_optimize_export_json">
				<?php submit_button( 'Export settings (JSON)', 'secondary', 'submit', false ); ?>
			</form>

			<form method="post" enctype="multipart/form-data" style="margin-top:10px;">
				<?php wp_nonce_field( 'p8o_optimize_import_json', 'p8o_optimize_import_nonce' ); ?>
				<input type="hidden" name="p8o_optimize_action" value="import_json">
				<input type="file" name="p8o_optimize_json" accept=".json,application/json" required>
				<?php submit_button( 'Import settings (JSON)', 'secondary', 'submit', false ); ?>
				<p class="description">Import overwrites current plugin settings.</p>
			</form>
		<?php endif; ?>

	</div>
	<?php
}

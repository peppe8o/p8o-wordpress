<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * External JS (Resource Hints) field renderer.
 * - "Hints" controls <link rel="preconnect|dns-prefetch|preload"> output
 * - "Script loading" controls adding async/defer to the matching <script> tag
 *
 * Escaping: esc_url for URLs and esc_attr for attributes. [web:469]
 */

function p8o_external_js_hints_callback() {
	$rows = get_option( 'p8o_external_js_hints', array() );
	if ( ! is_array( $rows ) ) $rows = array();
	?>
	<div id="p8o-external-js-hints">
		<?php foreach ( $rows as $index => $row ) :
			$url      = isset( $row['url'] ) ? (string) $row['url'] : '';
			$strategy = isset( $row['strategy'] ) ? (string) $row['strategy'] : 'none'; // preconnect|preload|none
			$attr     = isset( $row['attr'] ) ? (string) $row['attr'] : 'none';         // defer|async|none
		?>
		<div class="p8o-ext-js-row" style="display:flex;align-items:center;margin-bottom:10px;gap:14px;flex-wrap:wrap;">
			<input type="url"
				   name="p8o_external_js_hints[<?php echo esc_attr( $index ); ?>][url]"
				   placeholder="https://example.com/script.js"
				   value="<?php echo esc_attr( $url ); ?>"
				   style="width:520px;">

			<div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
				<strong style="min-width:55px;display:inline-block;">Hints</strong>
				<label><input type="radio" name="p8o_external_js_hints[<?php echo esc_attr( $index ); ?>][strategy]" value="preconnect" <?php checked( 'preconnect', $strategy ); ?>> Preconnect</label>
				<label><input type="radio" name="p8o_external_js_hints[<?php echo esc_attr( $index ); ?>][strategy]" value="preload"    <?php checked( 'preload',    $strategy ); ?>> Preload</label>
				<label><input type="radio" name="p8o_external_js_hints[<?php echo esc_attr( $index ); ?>][strategy]" value="none"       <?php checked( 'none',       $strategy ); ?>> None (no hint)</label>
			</div>

			<div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
				<strong style="min-width:105px;display:inline-block;">Script loading</strong>
				<label><input type="radio" name="p8o_external_js_hints[<?php echo esc_attr( $index ); ?>][attr]" value="defer" <?php checked( 'defer', $attr ); ?>> Defer</label>
				<label><input type="radio" name="p8o_external_js_hints[<?php echo esc_attr( $index ); ?>][attr]" value="async" <?php checked( 'async', $attr ); ?>> Async</label>
				<label><input type="radio" name="p8o_external_js_hints[<?php echo esc_attr( $index ); ?>][attr]" value="none"  <?php checked( 'none',  $attr ); ?>> Normal</label>
			</div>

			<button type="button" class="button remove-ext-js">Remove</button>
		</div>
		<?php endforeach; ?>
	</div>

	<button type="button" class="button" id="add-ext-js">Add external script</button>

	<script>
	(function(){
		const container = document.getElementById('p8o-external-js-hints');
		const addBtn = document.getElementById('add-ext-js');

		if (!container || !addBtn) return;

		addBtn.addEventListener('click', function() {
			const index = container.querySelectorAll('.p8o-ext-js-row').length;
			const div = document.createElement('div');
			div.className = 'p8o-ext-js-row';
			div.style.cssText = 'display:flex;align-items:center;margin-bottom:10px;gap:14px;flex-wrap:wrap;';
			div.innerHTML = `
				<input type="url"
					   name="p8o_external_js_hints[${index}][url]"
					   placeholder="https://example.com/script.js"
					   style="width:520px;">

				<div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
					<strong style="min-width:55px;display:inline-block;">Hints</strong>
					<label><input type="radio" name="p8o_external_js_hints[${index}][strategy]" value="preconnect"> Preconnect</label>
					<label><input type="radio" name="p8o_external_js_hints[${index}][strategy]" value="preload"> Preload</label>
					<label><input type="radio" name="p8o_external_js_hints[${index}][strategy]" value="none" checked> None (no hint)</label>
				</div>

				<div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
					<strong style="min-width:105px;display:inline-block;">Script loading</strong>
					<label><input type="radio" name="p8o_external_js_hints[${index}][attr]" value="defer"> Defer</label>
					<label><input type="radio" name="p8o_external_js_hints[${index}][attr]" value="async"> Async</label>
					<label><input type="radio" name="p8o_external_js_hints[${index}][attr]" value="none" checked> Normal</label>
				</div>

				<button type="button" class="button remove-ext-js">Remove</button>
			`;
			container.appendChild(div);
		});

		document.addEventListener('click', function(e) {
			if (e.target && e.target.classList.contains('remove-ext-js')) {
				const row = e.target.closest('.p8o-ext-js-row');
				if (row) row.remove();
			}
		});
	})();
	</script>
	<?php
}

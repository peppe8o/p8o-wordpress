<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Admin field for p8oexternaljshints (array of rows).
 *
 * Row shape:
 * - url  (string) full URL to script
 * - hint (preconnect|preload|none)
 * - attr (defer|async|none)
 */
function p8oexternaljshintscallback() {
	$hints = get_option( 'p8oexternaljshints', array() );
	if ( ! is_array( $hints ) ) $hints = array();
	?>
	<div id="p8o-external-js-hints">
		<?php foreach ( $hints as $index => $row ) :
			$url  = isset($row['url']) ? (string) $row['url'] : '';
			$hint = isset($row['hint']) ? (string) $row['hint'] : 'preconnect';
			$attr = isset($row['attr']) ? (string) $row['attr'] : 'none';
		?>
		<div class="p8o-external-js-row" style="display:flex;align-items:center;margin-bottom:10px;gap:10px;flex-wrap:wrap;">
			<input type="text" name="p8oexternaljshints[<?php echo esc_attr($index); ?>][url]" placeholder="https://example.com/script.js" value="<?php echo esc_attr($url); ?>" style="width:420px;">

			<select name="p8oexternaljshints[<?php echo esc_attr($index); ?>][hint]">
				<option value="preconnect" <?php selected( $hint, 'preconnect' ); ?>>preconnect</option>
				<option value="preload" <?php selected( $hint, 'preload' ); ?>>preload</option>
				<option value="none" <?php selected( $hint, 'none' ); ?>>none</option>
			</select>

			<select name="p8oexternaljshints[<?php echo esc_attr($index); ?>][attr]">
				<option value="none" <?php selected( $attr, 'none' ); ?>>none</option>
				<option value="defer" <?php selected( $attr, 'defer' ); ?>>defer</option>
				<option value="async" <?php selected( $attr, 'async' ); ?>>async</option>
			</select>

			<button type="button" class="button remove-external-js-row">Remove</button>
		</div>
		<?php endforeach; ?>
	</div>

	<button type="button" class="button" id="add-external-js-row">Add external script</button>

	<script>
	(function(){
		const container = document.getElementById('p8o-external-js-hints');
		document.getElementById('add-external-js-row').addEventListener('click', function() {
			const index = container.children.length;
			const div = document.createElement('div');
			div.className = 'p8o-external-js-row';
			div.style.cssText = 'display:flex;align-items:center;margin-bottom:10px;gap:10px;flex-wrap:wrap;';
			div.innerHTML = `
				<input type="text" name="p8oexternaljshints[${index}][url]" placeholder="https://example.com/script.js" style="width:420px;">
				<select name="p8oexternaljshints[${index}][hint]">
					<option value="preconnect" selected>preconnect</option>
					<option value="preload">preload</option>
					<option value="none">none</option>
				</select>
				<select name="p8oexternaljshints[${index}][attr]">
					<option value="none" selected>none</option>
					<option value="defer">defer</option>
					<option value="async">async</option>
				</select>
				<button type="button" class="button remove-external-js-row">Remove</button>
			`;
			container.appendChild(div);
		});

		document.addEventListener('click', function(e) {
			if (e.target && e.target.classList.contains('remove-external-js-row')) {
				e.target.closest('.p8o-external-js-row').remove();
			}
		});
	})();
	</script>
	<?php
}

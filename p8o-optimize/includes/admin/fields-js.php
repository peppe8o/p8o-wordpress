<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function p8ojsresourcescallback() {
	$resources = get_option( 'p8ojsresources', array() );
	if ( ! is_array( $resources ) ) $resources = array();
	?>
	<div id="p8o-js-resources">
		<?php foreach ( $resources as $index => $row ) :
			$handle   = isset($row['handle']) ? (string) $row['handle'] : '';
			$path     = isset($row['path']) ? (string) $row['path'] : '';
			$strategy = isset($row['strategy']) ? (string) $row['strategy'] : 'none';
		?>
		<div class="p8o-js-row" style="display:flex;align-items:center;margin-bottom:10px;gap:10px;flex-wrap:wrap;">
			<input type="text" name="p8ojsresources[<?php echo esc_attr($index); ?>][handle]" placeholder="Handle (optional)" value="<?php echo esc_attr($handle); ?>" style="width:220px;">
			<input type="text" name="p8ojsresources[<?php echo esc_attr($index); ?>][path]" placeholder="Path (for inline: wp-relative or same-domain URL)" value="<?php echo esc_attr($path); ?>" style="width:420px;">

			<select name="p8ojsresources[<?php echo esc_attr($index); ?>][strategy]">
				<option value="none" <?php selected( $strategy, 'none' ); ?>>None</option>
				<option value="inline" <?php selected( $strategy, 'inline' ); ?>>Inline</option>
				<option value="defer" <?php selected( $strategy, 'defer' ); ?>>Defer</option>
				<option value="async" <?php selected( $strategy, 'async' ); ?>>Async</option>
			</select>

			<button type="button" class="button remove-js-row">Remove</button>
		</div>
		<?php endforeach; ?>
	</div>

	<button type="button" class="button" id="add-js-row">Add JS Resource</button>

	<script>
	(function(){
		const container = document.getElementById('p8o-js-resources');
		document.getElementById('add-js-row').addEventListener('click', function() {
			const index = container.children.length;
			const div = document.createElement('div');
			div.className = 'p8o-js-row';
			div.style.cssText = 'display:flex;align-items:center;margin-bottom:10px;gap:10px;flex-wrap:wrap;';
			div.innerHTML = `
				<input type="text" name="p8ojsresources[${index}][handle]" placeholder="Handle (optional)" style="width:220px;">
				<input type="text" name="p8ojsresources[${index}][path]" placeholder="Path (for inline: wp-relative or same-domain URL)" style="width:420px;">
				<select name="p8ojsresources[${index}][strategy]">
					<option value="none" selected>None</option>
					<option value="inline">Inline</option>
					<option value="defer">Defer</option>
					<option value="async">Async</option>
				</select>
				<button type="button" class="button remove-js-row">Remove</button>
			`;
			container.appendChild(div);
		});

		document.addEventListener('click', function(e) {
			if (e.target && e.target.classList.contains('remove-js-row')) {
				e.target.closest('.p8o-js-row').remove();
			}
		});
	})();
	</script>
	<?php
}

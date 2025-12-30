<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function p8o_js_resources_callback() {
	$resources = get_option( 'p8o_js_resources', array() );
	if ( ! is_array( $resources ) ) $resources = array();
	?>
	<div id="p8o-js-resources">
		<?php foreach ( $resources as $index => $resource ) :
			$path     = isset($resource['path']) ? $resource['path'] : '';
			$handle   = isset($resource['handle']) ? $resource['handle'] : '';
			$strategy = isset($resource['strategy']) ? $resource['strategy'] : 'none';
		?>
		<div class="p8o-resource" style="display:flex;align-items:center;margin-bottom:10px;gap:10px;flex-wrap:wrap;">
			<input type="text" name="p8o_js_resources[<?php echo esc_attr($index); ?>][path]" placeholder="Path or URL substring" value="<?php echo esc_attr($path); ?>" style="width:260px;">
			<input type="text" name="p8o_js_resources[<?php echo esc_attr($index); ?>][handle]" placeholder="Handle (optional)" value="<?php echo esc_attr($handle); ?>" style="width:160px;">
			<div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
				<label><input type="radio" name="p8o_js_resources[<?php echo esc_attr($index); ?>][strategy]" value="inline" <?php checked('inline', $strategy); ?>> Inline</label>
				<label><input type="radio" name="p8o_js_resources[<?php echo esc_attr($index); ?>][strategy]" value="defer"  <?php checked('defer',  $strategy); ?>> Defer</label>
				<label><input type="radio" name="p8o_js_resources[<?php echo esc_attr($index); ?>][strategy]" value="async"  <?php checked('async',  $strategy); ?>> Async</label>
				<label><input type="radio" name="p8o_js_resources[<?php echo esc_attr($index); ?>][strategy]" value="none"   <?php checked('none',   $strategy); ?>> None</label>
			</div>
			<button type="button" class="button remove-resource">Remove</button>
		</div>
		<?php endforeach; ?>
	</div>

	<button type="button" class="button" id="add-js-resource">Add JS Resource</button>

	<script>
	(function(){
		const container = document.getElementById('p8o-js-resources');
		document.getElementById('add-js-resource').addEventListener('click', function() {
			const index = container.children.length;
			const div = document.createElement('div');
			div.className = 'p8o-resource';
			div.style.cssText = 'display:flex;align-items:center;margin-bottom:10px;gap:10px;flex-wrap:wrap;';
			div.innerHTML = `
				<input type="text" name="p8o_js_resources[${index}][path]" placeholder="Path or URL substring" style="width:260px;">
				<input type="text" name="p8o_js_resources[${index}][handle]" placeholder="Handle (optional)" style="width:160px;">
				<div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
					<label><input type="radio" name="p8o_js_resources[${index}][strategy]" value="inline"> Inline</label>
					<label><input type="radio" name="p8o_js_resources[${index}][strategy]" value="defer"> Defer</label>
					<label><input type="radio" name="p8o_js_resources[${index}][strategy]" value="async"> Async</label>
					<label><input type="radio" name="p8o_js_resources[${index}][strategy]" value="none" checked> None</label>
				</div>
				<button type="button" class="button remove-resource">Remove</button>
			`;
			container.appendChild(div);
		});

		document.addEventListener('click', function(e) {
			if (e.target && e.target.classList.contains('remove-resource')) {
				e.target.closest('.p8o-resource').remove();
			}
		});
	})();
	</script>
	<?php
}

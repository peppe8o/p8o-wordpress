<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function p8oimagepathscallback() {
	$paths = get_option( 'p8oimagepaths', array() );
	if ( ! is_array( $paths ) ) $paths = array();
	?>
	<div id="p8o-image-paths">
		<?php foreach ( $paths as $index => $row ) :
			$path     = isset($row['path']) ? (string) $row['path'] : '';
			$strategy = isset($row['strategy']) ? (string) $row['strategy'] : 'none';
		?>
		<div class="p8o-image-path" style="display:flex;align-items:center;margin-bottom:10px;gap:10px;flex-wrap:wrap;">
			<input type="text" name="p8oimagepaths[<?php echo esc_attr($index); ?>][path]" placeholder="Partial image path (e.g., featured-image)" value="<?php echo esc_attr($path); ?>" style="width:320px;">
			<div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
				<label><input type="radio" name="p8oimagepaths[<?php echo esc_attr($index); ?>][strategy]" value="high" <?php checked('high', $strategy); ?>> High</label>
				<label><input type="radio" name="p8oimagepaths[<?php echo esc_attr($index); ?>][strategy]" value="none" <?php checked('none', $strategy); ?>> None</label>
			</div>
			<button type="button" class="button remove-image-path">Remove</button>
		</div>
		<?php endforeach; ?>
	</div>

	<button type="button" class="button" id="add-image-path">Add Image Path</button>

	<script>
	(function(){
		const container = document.getElementById('p8o-image-paths');
		document.getElementById('add-image-path').addEventListener('click', function() {
			const index = container.children.length;
			const div = document.createElement('div');
			div.className = 'p8o-image-path';
			div.style.cssText = 'display:flex;align-items:center;margin-bottom:10px;gap:10px;flex-wrap:wrap;';
			div.innerHTML = `
				<input type="text" name="p8oimagepaths[${index}][path]" placeholder="Partial image path (e.g., featured-image)" style="width:320px;">
				<div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
					<label><input type="radio" name="p8oimagepaths[${index}][strategy]" value="high"> High</label>
					<label><input type="radio" name="p8oimagepaths[${index}][strategy]" value="none" checked> None</label>
				</div>
				<button type="button" class="button remove-image-path">Remove</button>
			`;
			container.appendChild(div);
		});

		document.addEventListener('click', function(e) {
			if (e.target && e.target.classList.contains('remove-image-path')) {
				e.target.closest('.p8o-image-path').remove();
			}
		});
	})();
	</script>
	<?php
}

function p8oimagesclscallback() {
	$v = get_option( 'p8oimagescls', '0' );
	?>
	<label>
		<input type="checkbox" name="p8oimagescls" value="1" <?php checked( $v, '1' ); ?>>
		Enable width/height + aspect-ratio for attachment images
	</label>
	<?php
}

function p8odisablewpsrcsetsizescallback() {
	$v = get_option( 'p8odisablewpsrcsetsizes', '0' );
	?>
	<label>
		<input type="checkbox" name="p8odisablewpsrcsetsizes" value="1" <?php checked( $v, '1' ); ?>>
		Disable WordPress automatic <code>srcset</code> and <code>sizes</code>
	</label>
	<?php
}

function p8odisablewpwidthheightcallback() {
	$v = get_option( 'p8odisablewpwidthheight', '0' );
	?>
	<label>
		<input type="checkbox" name="p8odisablewpwidthheight" value="1" <?php checked( $v, '1' ); ?>>
		Disable WordPress automatic <code>width</code> and <code>height</code>
	</label>
	<?php
}

function p8odisablewplazyloadingcallback() {
	$v = get_option( 'p8odisablewplazyloading', '0' );
	?>
	<label>
		<input type="checkbox" name="p8odisablewplazyloading" value="1" <?php checked( $v, '1' ); ?>>
		Disable WordPress automatic <code>loading="lazy"</code>
	</label>
	<?php
}

function p8oadclsrulescallback() {
	$rules = get_option( 'p8oadclsrules', array() );
	if ( ! is_array( $rules ) ) $rules = array();
	?>
	<div id="p8o-ad-cls-rules">
		<?php foreach ( $rules as $index => $row ) :
			$id = isset($row['id']) ? (string) $row['id'] : '';
			$mh = isset($row['max_height']) ? (string) $row['max_height'] : '';
			$en = isset($row['enabled']) ? (string) $row['enabled'] : '1';
		?>
		<div class="p8o-ad-rule" style="display:flex;align-items:center;margin-bottom:10px;gap:10px;flex-wrap:wrap;">
			<input type="text" name="p8oadclsrules[<?php echo esc_attr($index); ?>][id]" placeholder="Element ID (without #)" value="<?php echo esc_attr($id); ?>" style="width:320px;">
			<input type="number" min="0" step="1" name="p8oadclsrules[<?php echo esc_attr($index); ?>][max_height]" placeholder="Max height (px)" value="<?php echo esc_attr($mh); ?>" style="width:160px;">
			<label style="display:flex;align-items:center;gap:6px;">
				<input type="checkbox" name="p8oadclsrules[<?php echo esc_attr($index); ?>][enabled]" value="1" <?php checked( $en, '1' ); ?>>
				Enabled
			</label>
			<button type="button" class="button remove-ad-rule">Remove</button>
		</div>
		<?php endforeach; ?>
	</div>

	<button type="button" class="button" id="add-ad-rule">Add Ad Rule</button>

	<script>
	(function(){
		const container = document.getElementById('p8o-ad-cls-rules');
		document.getElementById('add-ad-rule').addEventListener('click', function() {
			const index = container.children.length;
			const div = document.createElement('div');
			div.className = 'p8o-ad-rule';
			div.style.cssText = 'display:flex;align-items:center;margin-bottom:10px;gap:10px;flex-wrap:wrap;';
			div.innerHTML = `
				<input type="text" name="p8oadclsrules[${index}][id]" placeholder="Element ID (without #)" style="width:320px;">
				<input type="number" min="0" step="1" name="p8oadclsrules[${index}][max_height]" placeholder="Max height (px)" style="width:160px;">
				<label style="display:flex;align-items:center;gap:6px;">
					<input type="checkbox" name="p8oadclsrules[${index}][enabled]" value="1" checked>
					Enabled
				</label>
				<button type="button" class="button remove-ad-rule">Remove</button>
			`;
			container.appendChild(div);
		});

		document.addEventListener('click', function(e) {
			if (e.target && e.target.classList.contains('remove-ad-rule')) {
				e.target.closest('.p8o-ad-rule').remove();
			}
		});
	})();
	</script>
	<?php
}

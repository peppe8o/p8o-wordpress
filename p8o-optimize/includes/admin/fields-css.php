<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function p8ocssresourcescallback() {
	$resources = get_option( 'p8ocssresources', array() );
	if ( ! is_array( $resources ) ) $resources = array();
	$resources = array_values( $resources );
	?>
	<style>
		/* Container aligned to left */
		.p8o-css-wrap { max-width: 1100px; }

		/* Table-like grid */
		.p8o-css-table {
			border: 1px solid #dcdcde;
			background: #fff;
		}
		.p8o-css-row {
			display: grid;
			grid-template-columns: 220px 1fr 160px 160px;
			gap: 10px;
			align-items: center;
			padding: 8px;
			border-top: 1px solid #f0f0f1;
		}
		.p8o-css-row:first-child { border-top: 0; }

		.p8o-css-head {
			background: #f6f7f7;
			font-weight: 600;
			border-bottom: 1px solid #dcdcde;
		}
		.p8o-css-head > div { padding: 8px; }

		.p8o-css-actions {
			display: flex;
			justify-content: flex-end;
			gap: 8px;
			flex-wrap: wrap;
		}
		.p8o-css-actions .button {
			padding: 0 8px;
			min-height: 28px;
			line-height: 26px;
		}

		/* Saved view (p tags) */
		.p8o-css-cellp {
			margin: 0;
			padding: 4px 0;
			white-space: nowrap;
			overflow: hidden;
			text-overflow: ellipsis;
		}

		/* Inputs fill cells */
		.p8o-css-row input[type="text"],
		.p8o-css-row select {
			width: 100%;
		}

		@media (max-width: 900px) {
			.p8o-css-row { grid-template-columns: 1fr; }
			.p8o-css-actions { justify-content: flex-start; }
			.p8o-css-head { display: none; }
			.p8o-css-cellp { white-space: normal; }
		}
	</style>

	<div class="p8o-css-wrap">
		<h2 style="margin:0 0 6px;">CSS Resources</h2>
		<p class="description" style="margin:0 0 12px;">
			Match by <strong>Handle</strong> when possible; otherwise use “Path contains” to match a substring of the stylesheet URL.
		</p>

		<div class="p8o-css-table" id="p8o-css-resources">
			<div class="p8o-css-row p8o-css-head">
				<div>Handle</div>
				<div>Path contains</div>
				<div>Strategy</div>
				<div style="text-align:right;">Actions</div>
			</div>

			<?php foreach ( $resources as $index => $row ) :
				$handle   = isset($row['handle']) ? trim((string)$row['handle']) : '';
				$path     = isset($row['path']) ? trim((string)$row['path']) : '';
				$strategy = isset($row['strategy']) ? (string) $row['strategy'] : 'none';

				$is_saved = ( $handle !== '' || $path !== '' );
			?>
				<div class="p8o-css-row" data-index="<?php echo esc_attr($index); ?>" data-mode="<?php echo $is_saved ? 'view' : 'edit'; ?>">

					<!-- VIEW CELLS -->
					<div class="p8o-css-view" <?php echo $is_saved ? '' : 'style="display:none;"'; ?>>
						<p class="p8o-css-cellp" title="<?php echo esc_attr($handle); ?>">
							<?php echo $handle !== '' ? esc_html($handle) : '—'; ?>
						</p>
					</div>
					<div class="p8o-css-view" <?php echo $is_saved ? '' : 'style="display:none;"'; ?>>
						<p class="p8o-css-cellp" title="<?php echo esc_attr($path); ?>">
							<?php echo $path !== '' ? esc_html($path) : '—'; ?>
						</p>
					</div>
					<div class="p8o-css-view" <?php echo $is_saved ? '' : 'style="display:none;"'; ?>>
						<p class="p8o-css-cellp"><?php echo esc_html($strategy); ?></p>
					</div>
					<div class="p8o-css-actions p8o-css-view" <?php echo $is_saved ? '' : 'style="display:none;"'; ?>>
						<button type="button" class="button p8o-css-edit">Edit</button>
						<button type="button" class="button button-link-delete p8o-css-remove">Remove</button>
					</div>

					<!-- EDIT CELLS -->
					<div class="p8o-css-editview" <?php echo $is_saved ? 'style="display:none;"' : ''; ?>>
						<input type="text"
							name="p8ocssresources[<?php echo esc_attr($index); ?>][handle]"
							placeholder="(optional) e.g. theme-style"
							value="<?php echo esc_attr($handle); ?>">
					</div>

					<div class="p8o-css-editview" <?php echo $is_saved ? 'style="display:none;"' : ''; ?>>
						<input type="text"
							name="p8ocssresources[<?php echo esc_attr($index); ?>][path]"
							placeholder="(optional) substring in stylesheet URL"
							value="<?php echo esc_attr($path); ?>">
					</div>

					<div class="p8o-css-editview" <?php echo $is_saved ? 'style="display:none;"' : ''; ?>>
						<select name="p8ocssresources[<?php echo esc_attr($index); ?>][strategy]">
							<option value="none" <?php selected( $strategy, 'none' ); ?>>None</option>
							<option value="inline" <?php selected( $strategy, 'inline' ); ?>>Inline</option>
							<option value="preload" <?php selected( $strategy, 'preload' ); ?>>Preload</option>
							<option value="defer" <?php selected( $strategy, 'defer' ); ?>>Defer</option>
						</select>
					</div>

					<div class="p8o-css-actions p8o-css-editview" <?php echo $is_saved ? 'style="display:none;"' : ''; ?>>
						<button type="button" class="button button-secondary p8o-css-done">Done</button>
						<button type="button" class="button button-link-delete p8o-css-remove">Remove</button>
					</div>
				</div>
			<?php endforeach; ?>
		</div>

		<p style="margin-top:10px;">
			<button type="button" class="button button-primary" id="p8o-css-add">Add rule</button>
		</p>
	</div>

	<script>
	(function(){
		const table = document.getElementById('p8o-css-resources');
		const addBtn = document.getElementById('p8o-css-add');

		function dataRows() {
			return table.querySelectorAll('.p8o-css-row[data-index]');
		}

		function getRowIndex(row) {
			return row.getAttribute('data-index');
		}

		function showMode(row, mode) {
			row.setAttribute('data-mode', mode);
			row.querySelectorAll('.p8o-css-view').forEach(el => el.style.display = (mode === 'view') ? '' : 'none');
			row.querySelectorAll('.p8o-css-editview').forEach(el => el.style.display = (mode === 'edit') ? '' : 'none');
		}

		function updateViewTexts(row) {
			const idx = getRowIndex(row);
			const handle = row.querySelector(`[name="p8ocssresources[${idx}][handle]"]`)?.value?.trim() || '';
			const path = row.querySelector(`[name="p8ocssresources[${idx}][path]"]`)?.value?.trim() || '';
			const strategy = row.querySelector(`[name="p8ocssresources[${idx}][strategy]"]`)?.value || 'none';

			const viewCells = row.querySelectorAll('.p8o-css-view .p8o-css-cellp');

			// Handle
			if (viewCells[0]) {
				viewCells[0].textContent = handle || '—';
				viewCells[0].setAttribute('title', handle);
			}
			// Path
			if (viewCells[1]) {
				viewCells[1].textContent = path || '—';
				viewCells[1].setAttribute('title', path);
			}
			// Strategy
			if (viewCells[2]) viewCells[2].textContent = strategy;

			return {handle, path};
		}

		addBtn.addEventListener('click', function() {
			const index = dataRows().length;

			const row = document.createElement('div');
			row.className = 'p8o-css-row';
			row.setAttribute('data-index', index);
			row.setAttribute('data-mode', 'edit');

			row.innerHTML = `
				<div class="p8o-css-view" style="display:none;">
					<p class="p8o-css-cellp" title="">—</p>
				</div>
				<div class="p8o-css-view" style="display:none;">
					<p class="p8o-css-cellp" title="">—</p>
				</div>
				<div class="p8o-css-view" style="display:none;">
					<p class="p8o-css-cellp">none</p>
				</div>
				<div class="p8o-css-actions p8o-css-view" style="display:none;">
					<button type="button" class="button p8o-css-edit">Edit</button>
					<button type="button" class="button button-link-delete p8o-css-remove">Remove</button>
				</div>

				<div class="p8o-css-editview">
					<input type="text" name="p8ocssresources[${index}][handle]" placeholder="(optional) e.g. theme-style">
				</div>
				<div class="p8o-css-editview">
					<input type="text" name="p8ocssresources[${index}][path]" placeholder="(optional) substring in stylesheet URL">
				</div>
				<div class="p8o-css-editview">
					<select name="p8ocssresources[${index}][strategy]">
						<option value="none" selected>None</option>
						<option value="inline">Inline</option>
						<option value="preload">Preload</option>
						<option value="defer">Defer</option>
					</select>
				</div>
				<div class="p8o-css-actions p8o-css-editview">
					<button type="button" class="button button-secondary p8o-css-done">Done</button>
					<button type="button" class="button button-link-delete p8o-css-remove">Remove</button>
				</div>
			`;

			table.appendChild(row);
			showMode(row, 'edit');
		});

		document.addEventListener('click', function(e) {
			const row = e.target.closest('.p8o-css-row[data-index]');
			if (!row) return;

			if (e.target.classList.contains('p8o-css-remove')) {
				row.remove();
				return;
			}

			if (e.target.classList.contains('p8o-css-edit')) {
				showMode(row, 'edit');
				return;
			}

			if (e.target.classList.contains('p8o-css-done')) {
				const {handle, path} = updateViewTexts(row);

				// Only allow switching to view mode if there's something to save.
				if (handle || path) showMode(row, 'view');
				return;
			}
		});
	})();
	</script>
	<?php
}

<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function p8ooptimizecsssectioncallback() {
	echo '<p><strong>Handle</strong>: if empty, matching is done by URL substring using the “Path” field.</p>';
	echo '<p>CSS strategies: Inline / Preload / Defer / None.</p>';
	echo '<p><strong>Preload</strong> uses preload + rel swap + noscript fallback.</p>';
}

function p8ooptimizejssectioncallback() {
	echo '<p><strong>Handle</strong>: if empty, matching is done by URL substring using the “Path” field.</p>';
	echo '<p>JS strategies: Inline / Defer / Async / None.</p>';
	echo '<p><strong>Inline</strong>: “Path” must be a wp-relative path (e.g. <code>wp-content/...</code>) or a same-domain URL.</p>';
}

function p8ooptimizeexternaljssectioncallback() {
	echo '<p><strong>Handle</strong>: if empty, matching is done by URL substring using the “Path” field.</p>';
	echo '<p>Add external script URLs (different domains) and output resource hints in <code>&lt;head&gt;</code>:</p>';
	echo '<ul>';
	echo '<li><code>preconnect</code> (+ <code>dns-prefetch</code> fallback) to establish the connection early.</li>';
	echo '<li><code>preload</code> to start downloading a critical script early.</li>';
	echo '<li><code>none</code> to disable output for that row.</li>';
	echo '</ul>';
	echo '<p>Loading attributes: choose <code>defer</code>/<code>async</code>/<code>none</code> per script.</p>';
}

function p8ooptimizeimagesectioncallback() {
	echo '<p>Add partial strings (e.g. <code>featured-image</code>) to set <code>fetchpriority="high"</code> on matching attachment images.</p>';
}

function p8ooptimizeimagesclssectioncallback() {
	echo '<p>When enabled, adds missing width/height and an inline <code>aspect-ratio</code> to attachment images to reserve space and reduce CLS.</p>';
	echo '<p>Optional: disable WordPress automatic image attributes (srcset/sizes, width/height, loading="lazy").</p>';
}

function p8ooptimizeadclssectioncallback() {
	echo '<p>Reserve space for ad containers to prevent CLS using <code>min-height</code> placeholders.</p>';
	echo '<p>Tip: use <strong>min-height</strong> (not height) so the container can grow when a bigger creative is returned.</p>';
}

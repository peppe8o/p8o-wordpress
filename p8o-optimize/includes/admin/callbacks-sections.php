<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function p8o_optimize_css_section_callback() {
	echo '<p>CSS strategies: Inline / Preload / Defer / None.</p>';
	echo '<p><strong>Preload</strong> uses preload + rel swap + noscript fallback.</p>';
	echo '<p><strong>Handle</strong>: if empty, matching is done by URL substring using the "Path" field.</p>';
}

function p8o_optimize_js_section_callback() {
	echo '<p>JS strategies: Inline / Defer / Async / None.</p>';
	echo '<p><strong>Inline</strong>: "Path" must be a wp-relative path (e.g. <code>wp-content/...</code>) or a same-domain URL.</p>';
	echo '<p><strong>Handle</strong>: if empty, matching is done by URL substring using the "Path" field.</p>';
}

function p8o_optimize_external_js_section_callback() {
	echo '<p>Add external script URLs (different domains) and output resource hints in <code>&lt;head&gt;</code>:</p>';
	echo '<ul style="list-style:disc;margin-left:18px;">';
	echo '<li><code>preconnect</code> (+ <code>dns-prefetch</code> fallback) to establish the connection early.</li>';
	echo '<li><code>preload</code> to start downloading a critical script early.</li>';
	echo '<li><code>none</code> to disable output for that row.</li>';
	echo '</ul>';
	echo '<p>Loading attributes: choose <code>defer</code>/<code>async</code>/<code>none</code> per script.</p>';
}

function p8o_optimize_image_section_callback() {
	echo '<p>Add partial strings (e.g., <code>featured-image</code>) to set <code>fetchpriority="high"</code> on matching attachment images.</p>';
}

function p8o_optimize_images_cls_section_callback() {
	echo '<p>When enabled, adds missing width/height and an inline <code>aspect-ratio</code> to attachment images to reserve space and reduce CLS.</p>';
	echo '<p>Optional: disable WordPress automatic image attributes (srcset/sizes, width/height, loading="lazy").</p>';
}

function p8o_optimize_ad_cls_section_callback() {
	echo '<p>Reserve space for ad containers (Ezoic and other networks) to prevent CLS using <code>min-height</code> placeholders.</p>';
	echo '<p>Add rules as: <code>ID,maxHeightPx</code> (example: <code>ezoic-pub-ad-placeholder-101,250</code>).</p>';
	echo '<p>Tip: use <strong>min-height</strong> (not height) so the container can grow when a bigger creative is returned.</p>';
}

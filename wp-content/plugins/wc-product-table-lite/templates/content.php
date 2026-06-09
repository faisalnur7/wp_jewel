<?php
if (!defined('ABSPATH')) {
	exit;
}

if (empty($limit_by)) {
	$limit = false;
	$line_clamp = false;
} else if ($limit_by == "lines") {
	$limit = false;
}

if ($product->get_type() == 'variation') {
	$content = $product->get_description();

} else {
	$content = apply_filters('the_content', get_the_content());

}

if (!empty($shortcode_action)) {
	if ($shortcode_action === 'process') {
		remove_shortcode('product_table');
		$content = do_shortcode($content);
		add_shortcode('product_table', 'wcpt_shortcode_product_table');

	} else if ($shortcode_action === 'strip') {
		$content = strip_shortcodes($content);

	}
}

// this code is common between the 'Short description' and 'Content' elements

// -- begin

if (!$content) {
	return;
}

// complete unclosed tags in $content
if (
	defined('LIBXML_DOTTED_VERSION') &&
	version_compare(LIBXML_DOTTED_VERSION, '2.7.0', '>')
) {
	// Complete unclosed tags in $content
	$dom = new DOMDocument();
	$html = '<!DOCTYPE html><html><head><meta charset="UTF-8"></head><body><div>' . $content . '</div></body></html>';

	// Save the current state of error handling
	$_errors = libxml_use_internal_errors(true);

	// Load the HTML with proper encoding and without adding default doctype
	$dom->loadHTML($html, LIBXML_HTML_NODEFDTD);

	// Extract just the div content
	$div = $dom->getElementsByTagName('div')->item(0);
	$content = '';

	// Process each child node of the div
	if ($div) {
		foreach ($div->childNodes as $child) {
			$content .= $dom->saveHTML($child);
		}
	}

	// Restore the previous error handling state
	libxml_use_internal_errors($_errors);
}

// content stripped of html and slashes
$content__html_stripped = strip_tags(stripslashes($content));

// no markup if limit is defined by user
if (!empty($limit)) {
	$content = $content__html_stripped;
}

// get truncation symbol "..."
$truncation_symbol_content = "…"; // default
if (!empty($truncation_symbol)) {
	if ($truncation_symbol == 'hide') {
		$truncation_symbol_content = '';
	} else if ($truncation_symbol == 'custom') {
		$truncation_symbol_content = $custom_truncation_symbol;
	}
}

// truncate content
$truncate = false;
$limit = empty($limit) ? 1000 : (int) $limit;
$rtrim_chars = ' .,…';

if (count(explode(' ', $content__html_stripped)) > $limit) {
	$truncate = true;
	$content__html_stripped__truncated = rtrim(implode(' ', array_slice(explode(' ', trim($content__html_stripped)), 0, $limit)), $rtrim_chars) . $truncation_symbol_content;
}

// toggle enabled
if (
	!empty($toggle_enabled) &&
	$truncate
) {

	$html_class .= ' wcpt-toggle-enabled ';
	$show_more_label = empty($show_more_label) ? 'show more (+)' : $show_more_label;
	$show_less_label = empty($show_less_label) ? 'show less (-)' : $show_less_label;

	ob_start();
	?>
	<!-- before toggle -->
	<span class="wcpt-pre-toggle">
		<?php echo $content__html_stripped__truncated; ?>
		<span class="wcpt-toggle-trigger">
			<?php echo wcpt_parse_2($show_more_label); ?>
		</span>
	</span>
	<!-- after toggle -->
	<span class="wcpt-post-toggle">
		<?php echo $content; ?>
		<span class="wcpt-toggle-trigger">
			<?php echo wcpt_parse_2($show_less_label); ?>
		</span>
	</span>
	<?php
	$content = ob_get_clean();

	// toggle disabled
} else if ($truncate) {
	$content = $content__html_stripped__truncated;

	// 'read more' link
	$read_more = false;
	if (
		!empty($read_more_label) &&
		wcpt_parse_2($read_more_label)
	) {
		$content .= ' <a class="wcpt-read-more" href="' . $product->get_permalink() . '">'
			. wcpt_parse_2($read_more_label) .
			'</a>';
	}
}


// line clamp
if (empty($line_clamp)) {
	$line_clamp = 0;
}
if ($line_clamp > 0) {
	$html_class .= ' wcpt-line-clamp-enabled ';
}

// -- end

echo '<div class="wcpt-content ' . $html_class . '" style="--wcpt-line-clamp: ' . $line_clamp . ';">';
echo stripslashes($content);
echo '</div>';

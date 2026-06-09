<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

$title = get_the_title($product->get_id());

if (empty($html_tag)) {
	$html_tag = 'span';
}

$url = false;

if (
	!empty($product_link_enabled) ||
	!empty($link)
) {
	$target = empty($target_new_page) || trim($target_new_page) === "false" ? '' : ' target="_blank" ';

	$url = get_the_permalink($product->get_id());

	if (
		!empty($link) &&
		$link == 'custom_field' &&
		!empty($custom_field)
	) {
		$post_meta = get_post_meta($product->get_id(), $custom_field, true);

		if (!$post_meta) {
			if (!empty($custom_field_default_product_page)) {
				$url = get_the_permalink($product->get_id());
			} else {
				$url = false;
			}

		} else {
			$url = $post_meta;

		}

	}

}

// line clamp
if (empty($line_clamp)) {
	$line_clamp = 0;
}
if ($line_clamp > 0) {
	$html_class .= ' wcpt-line-clamp-enabled ';
}

if ($url) {

	$href = "href='$url'";

	$esc_title = esc_attr($title);

	$title_attr = "title='$esc_title'";

	$attr = "$href $target $title_attr style='--wcpt-line-clamp: $line_clamp;'";

	if ($html_tag == 'span') {
		echo "<a class='wcpt-title $html_class' $attr>$title</a>";
		return;
	}

	$title = "<a $attr>$title</a>";
}

echo "<$html_tag class='wcpt-title $html_class' style='--wcpt-line-clamp: $line_clamp;'>$title</$html_tag>";
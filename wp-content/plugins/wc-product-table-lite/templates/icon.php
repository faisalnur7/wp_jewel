<?php
if (!defined('ABSPATH')) {
	exit;
}

$style = '';

if (!empty($thickness)) {
	$style .= 'stroke-width:' . $thickness . ';';
}

if (!empty($color)) {
	$style .= 'color:' . $color . ';';
}

if (!empty($size)) {
	$style .= 'font-size:' . $size . ';';
}

if (empty($tooltip)) {
	$tooltip = '';
}

if (empty($title)) {
	$title = '';
}

if (empty($icon_source)) {
	$icon_source = 'included';
}

if (
	$icon_source == 'custom'
) {
	if (empty($custom_icon)) {
		return;
	}
	echo sprintf(
		'<span class="wcpt-icon wcpt-icon--custom %s" style="%s" title="%s">%s</span>',
		$html_class,
		$style,
		$title,
		$custom_icon
	);
} else {
	if (!$name) {
		return;
	}

	wcpt_icon($name, $html_class . ' wcpt-feather-icon', false, $tooltip, $title);
}

<?php
if (!defined('ABSPATH')) {
	exit;
}

// Property label (text + optional icon)
$property_label_html = '';
include 'property_label.php';

$stock = $product->get_stock_quantity();
$label = is_numeric($stock) ? $stock : '';

if (empty($range_labels)) {
	$range_labels = '';
}

$rules = preg_split('/\r\n|[\r\n]/', trim($range_labels));
$_rules = array();

$found_rule = false;

foreach ($rules as $rule) {
	if (!$rule) {
		continue;
	}
	$exploded_rule = array_map('trim', explode(':', $rule));
	$range = $exploded_rule[0];
	$_label = empty($exploded_rule[1]) ? "" : $exploded_rule[1];

	$exploded_range = array_map('trim', explode(' - ', $range));
	$min = intval($exploded_range[0]);
	$max = isset($exploded_range[1]) ? intval($exploded_range[1]) : 9999999;

	if (
		NULL !== $stock &&
		$min <= $stock &&
		$max >= $stock
	) {
		$label = $_label;
		$found_rule = true;
	}

	$_rules[] = array($min, $max, $_label);
}

$range_labels_attr = !empty($_rules) ? 'data-wcpt-stock-range-labels="' . esc_attr(json_encode($_rules)) . '"' : '';

$label = trim(str_replace('[stock]', $stock !== null ? $stock : '', $label));

if (
	!isset($variable_switch) || // prev. version
	$variable_switch
) {
	$html_class .= ' wcpt-variable-switch ';
}

echo '<span class="wcpt-stock ' . $html_class . '" data-wcpt-stock="' . $stock . '" ' . $range_labels_attr . '>' . $property_label_html . $label . '</span>';

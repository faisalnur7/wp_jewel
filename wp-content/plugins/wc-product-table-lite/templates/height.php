<?php
if (!defined('ABSPATH')) {
	exit;
}

if (
	!isset($variable_switch) || // prev. version
	$variable_switch
) {
	$html_class .= ' wcpt-variable-switch ';
}

$height = $product->get_height();

$template = isset($template) ? $template : '{n}';
$output = $height ? str_replace('{n}', $height, $template) : $empty_template;
echo '<div class="wcpt-height ' . $html_class . '" data-wcpt-default-height="' . esc_attr($height) . '" data-wcpt-template="' . esc_attr($template) . '" data-wcpt-empty-template="' . esc_attr($empty_template) . '">' . $output . '</div>';

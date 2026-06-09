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

$width = $product->get_width();

$template = isset($template) ? $template : '{n}';
$output = $width ? str_replace('{n}', $width, $template) : $empty_template;
echo '<div class="wcpt-width ' . $html_class . '" data-wcpt-default-width="' . esc_attr($width) . '" data-wcpt-template="' . esc_attr($template) . '" data-wcpt-empty-template="' . esc_attr($empty_template) . '">' . $output . '</div>';
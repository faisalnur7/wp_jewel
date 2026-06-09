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

$length = $product->get_length();

$template = isset($template) ? $template : '{n}';
$output = $length ? str_replace('{n}', $length, $template) : $empty_template;
echo '<div class="wcpt-length ' . $html_class . '" data-wcpt-default-length="' . esc_attr($length) . '" data-wcpt-template="' . esc_attr($template) . '" data-wcpt-empty-template="' . esc_attr($empty_template) . '">' . $output . '</div>';
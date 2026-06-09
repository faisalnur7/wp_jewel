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

$weight = $product->get_weight();

$template = isset($template) ? $template : '{n}';
$output = $weight ? str_replace('{n}', $weight, $template) : $empty_template;
echo '<div class="wcpt-weight ' . $html_class . '" data-wcpt-default-weight="' . esc_attr($weight) . '" data-wcpt-template="' . esc_attr($template) . '" data-wcpt-empty-template="' . esc_attr($empty_template) . '">' . $output . '</div>';
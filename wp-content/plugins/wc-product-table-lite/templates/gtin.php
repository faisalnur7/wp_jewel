<?php
if (!defined('ABSPATH')) {
	exit;
}

$gtin = $product->get_global_unique_id();

if (!empty($variable_switch)) {
	$html_class .= ' wcpt-variable-switch ';
}

if (!empty($gtin)) {
	echo '<span class="wcpt-gtin ' . $html_class . '" data-wcpt-gtin="' . $gtin . '">' . $gtin . '</span>';
}


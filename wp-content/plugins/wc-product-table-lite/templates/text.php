<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

if (empty($text)) {
	return;
}

echo '<span class="wcpt-text ' . $html_class . '">' . wcpt_esc_tag($text) . '</span>';

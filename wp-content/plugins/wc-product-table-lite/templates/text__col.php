<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

if (empty($text)) {
	return;
}

echo '<span class="wcpt-text ' . $html_class . '">' . wcpt_esc_tag(wcpt_general_placeholders__parse($text)) . '</span>';

<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
} else {
	add_filter('wcpt_markup', 'wcpt_pagination_markup__header', 100, 1);
	if (!function_exists('wcpt_pagination_markup__header')) {
		function wcpt_pagination_markup__header($markup)
		{
			remove_filter('wcpt_markup', 'wcpt_pagination_markup__header', 100);
			global $wcpt_pagination_markup;
			return str_replace('{{wcpt_pagination}}', $wcpt_pagination_markup, $markup);
		}
	}
} ?>
{{wcpt_pagination}}
<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

$total_pages = isset($products->max_num_pages) ? (int) $products->max_num_pages : 0;
$current_page = isset($products->query_vars['paged']) ? max(1, (int) $products->query_vars['paged']) : 1;
$table_data = wcpt_get_table_data();
$sc_attrs = empty($table_data['query']['sc_attrs']) ? array() : $table_data['query']['sc_attrs'];

// Pagination controls toggles.
$show_prev_next_icons = isset($sc_attrs['pagination_show_prev_next_icons']) ? wc_string_to_bool($sc_attrs['pagination_show_prev_next_icons']) : false;
$show_first_last_icons = isset($sc_attrs['pagination_show_first_last_icons']) ? wc_string_to_bool($sc_attrs['pagination_show_first_last_icons']) : true;
$show_first_last_numbers = isset($sc_attrs['pagination_show_first_last_numbers']) ? wc_string_to_bool($sc_attrs['pagination_show_first_last_numbers']) : false;

?>
<div class="wcpt-pagination <?php if ($total_pages <= 1)
	echo ' wcpt-hide '; ?>">
	<?php
	if ($total_pages > 1) {
		// Build page URLs using WordPress' paginate_links so we respect the table's paging format.
		$args = array(
			'total' => $total_pages,
			'current' => $current_page,
			'prev_next' => false,
			'prev_text' => false,
			'next_text' => false,
			'type' => 'array',
			// Show every page so we can grab URLs for any page number.
			'end_size' => $total_pages,
			'mid_size' => 2,
			'before_page_number' => '',
			'after_page_number' => '',
			'add_args' => false,
		);

		if (wp_doing_ajax() || empty($table_data['query']['sc_attrs']['_archive'])) {
			$args['format'] = '?' . $table_id . '_paged=%#%';
		}

		$links_array = paginate_links(apply_filters('wcpt_pagination_options', $args));
		$page_urls = array();

		if (is_array($links_array)) {
			$page_number = 1;

			foreach ($links_array as $link_html) {
				$url = '';

				if (preg_match('/href="([^"]+)"/', $link_html, $matches)) {
					$url = $matches[1];
				}

				$page_urls[$page_number] = $url;
				$page_number++;
			}
		}

		// Fallback: if for some reason URLs are missing, bail to avoid broken markup.
		if (empty($page_urls)) {
			// Fallback to default paginate_links output.
			$fallback_args = $args;
			$fallback_args['type'] = 'plain';

			if ($mkp = paginate_links(apply_filters('wcpt_pagination_options', $fallback_args))) {
				echo str_replace(' current', ' current wcpt-active', $mkp);
			}
		} else {

			$first_page = 1;
			$last_page = $total_pages;

			if ($current_page === $first_page) {
				$pages_before = array();
				$pages_after = array($first_page + 1, $first_page + 2);
			} elseif ($current_page === $last_page) {
				$pages_before = array($last_page - 2, $last_page - 1);
				$pages_after = array();
			} else {
				$pages_before = array($current_page - 1);
				$pages_after = array($current_page + 1);
			}

			// First page controls.
			$first_url = isset($page_urls[$first_page]) ? $page_urls[$first_page] : '';
			$left_style = ($current_page === $first_page) ? ' style="opacity:0.2; pointer-events: none;"' : '';
			if ($show_first_last_icons) {
				echo '<a class="page-numbers wcpt-pagination-chevron wcpt-pagination-chevron-first" href="' . esc_url($first_url) . '"' . $left_style . '>' . wcpt_get_icon('chevrons-left') . '</a>';
			}
			if ($show_prev_next_icons) {
				$prev_page = max($first_page, $current_page - 1);
				$prev_url = isset($page_urls[$prev_page]) ? $page_urls[$prev_page] : '';
				$prev_style = ($current_page === $first_page) ? ' style="opacity:0.2; pointer-events: none;"' : '';
				echo '<a class="page-numbers wcpt-pagination-chevron wcpt-pagination-chevron-prev" href="' . esc_url($prev_url) . '"' . $prev_style . '>' . wcpt_get_icon('chevron-left') . '</a>';
			}

			$visible_number_pages = array_unique(array_merge($pages_before, array($current_page), $pages_after));
			sort($visible_number_pages);

			if ($show_first_last_numbers && !in_array($first_page, $visible_number_pages, true)) {
				echo '<a class="page-numbers" href="' . esc_url($first_url) . '">' . number_format_i18n($first_page) . '</a>';
				$first_visible_number = reset($visible_number_pages);
				if ($first_visible_number > ($first_page + 1)) {
					echo '<span class="page-numbers dots">...</span>';
				}
			}

			// Number buttons before current.
			foreach ($pages_before as $p) {
				if ($p >= 1) {
					$url = isset($page_urls[$p]) ? $page_urls[$p] : '';
					echo '<a class="page-numbers" href="' . esc_url($url) . '">' . number_format_i18n($p) . '</a>';
				}
			}

			// Current page (non-clickable, highlighted).
			echo '<span class="page-numbers current wcpt-active">' . number_format_i18n($current_page) . '</span>';

			// Number buttons after current.
			foreach ($pages_after as $p) {
				if ($p <= $total_pages) {
					$url = isset($page_urls[$p]) ? $page_urls[$p] : '';
					echo '<a class="page-numbers" href="' . esc_url($url) . '">' . number_format_i18n($p) . '</a>';
				}
			}

			if ($show_first_last_numbers && !in_array($last_page, $visible_number_pages, true)) {
				$last_visible_number = end($visible_number_pages);
				if ($last_visible_number < ($last_page - 1)) {
					echo '<span class="page-numbers dots">...</span>';
				}
				$last_number_url = isset($page_urls[$last_page]) ? $page_urls[$last_page] : '';
				echo '<a class="page-numbers" href="' . esc_url($last_number_url) . '">' . number_format_i18n($last_page) . '</a>';
			}

			if ($show_prev_next_icons) {
				$next_page = min($last_page, $current_page + 1);
				$next_url = isset($page_urls[$next_page]) ? $page_urls[$next_page] : '';
				$next_style = ($current_page === $last_page) ? ' style="opacity:0.2; pointer-events: none;"' : '';
				echo '<a class="page-numbers wcpt-pagination-chevron wcpt-pagination-chevron-next" href="' . esc_url($next_url) . '"' . $next_style . '>' . wcpt_get_icon('chevron-right') . '</a>';
			}

			// Right double chevron (last page link).
			$last_url = isset($page_urls[$last_page]) ? $page_urls[$last_page] : '';
			$right_style = ($current_page === $last_page) ? ' style="opacity:0.2; pointer-events: none;"' : '';
			if ($show_first_last_icons) {
				echo '<a class="page-numbers wcpt-pagination-chevron wcpt-pagination-chevron-last" href="' . esc_url($last_url) . '"' . $right_style . '>' . wcpt_get_icon('chevrons-right') . '</a>';
			}

		}
	}
	?>
</div>
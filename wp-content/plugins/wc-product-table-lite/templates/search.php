<?php
if (!defined('ABSPATH')) {
	exit;
}

++$GLOBALS['wcpt_search_count'];

$keyword = '';
$table_id = $GLOBALS['wcpt_table_data']['id'];
$param = $table_id . '_search_' . $GLOBALS['wcpt_search_count'];
$char_limit = 100;

if (
	!empty($_GET[$param]) &&
	empty($connect_with_archive_search)
) {
	$keyword = substr(stripslashes($_GET[$param]), 0, $char_limit);

	if (empty($custom_fields)) {
		$custom_fields = array();
	}

	if (!empty($custom_fields_textarea)) {
		$custom_fields = array_merge($custom_fields, preg_split("/\r\n|\n|\r/", $custom_fields_textarea));
	}

	$filter_info = array(
		'filter' => 'search',
		'values' => array($keyword),
		'match_type' => isset($match_type) ? $match_type : 'LIKE',
		'searches' => array(
			array(
				'keyword' => $keyword,
				'target' => $target,
				'custom_fields' => $custom_fields,
				'attributes' => empty($attributes) ? array() : $attributes,
				'keyword_separator' => isset($keyword_separator) ? $keyword_separator : ' ',
				'include_variation_skus' => !empty($include_variation_skus),
				'include_variation_gtins' => !empty($include_variation_gtins),
				'keyword_match_type' => !empty($keyword_match_type) ? $keyword_match_type : 'any',
			)
		)
	);

	if ($prev_search = wcpt_get_nav_filter('search')) {
		$filter_info['searches'] = array_merge($filter_info['searches'], $prev_search['searches']);
	}

	if (!empty($clear_label)) {
		$filter_info['clear_labels_2'] = array(
			// $keyword => str_replace( '[kw]', htmlentities( $keyword ), $clear_label ),
			$keyword => str_replace('[kw]', esc_html($keyword), $clear_label),
		);

	} else {
		$filter_info['clear_labels_2'] = array(
			// $keyword => __('Search') . ' : ' . htmlentities( $keyword ),
			$keyword => __('Search') . ' : ' . esc_html($keyword),
		);
	}

	$single = false;

	wcpt_update_user_filters($filter_info, $single);
}

if (
	!empty($connect_with_archive_search) &&
	!empty($_GET['s'])
) {
	$keyword = esc_html($_GET['s']);
}

if (!empty($connect_with_archive_search)) {
	$html_class .= ' wcpt-search--connect-with-archive ';
}

$search_label = '';
$placeholder = !empty($placeholder) ? $placeholder : __('Search', 'wc-product-table');


if (
	!empty($heading) &&
	!empty($heading_separate_line)
) {
	$html_class .= ' wcpt-search-heading-separate-line ';
}

if (
	!isset($reset_others) ||
	$reset_others
) {
	$html_class .= ' wcpt-search--reset-others ';
}

?>
<div class="wcpt-search-wrapper <?php echo esc_attr($html_class); ?>">
	<?php if (!empty($heading)): ?>
		<div class="wcpt-search-heading"><?php echo wcpt_parse_2($heading); ?></div>
	<?php endif; ?>
	<div class="wcpt-search <?php if (!empty($keyword))
		echo 'wcpt-active'; ?>" data-wcpt-table-id="<?php echo $GLOBALS['wcpt_table_data']['id']; ?>">

		<!-- input -->
		<input class="wcpt-search-input" type="search" name="<?php echo $param; ?>"
			data-wcpt-value="<?php echo htmlentities($keyword); ?>" placeholder="<?php echo do_shortcode($placeholder); ?>"
			value="<?php echo htmlentities($keyword); ?>" autocomplete="off" spellcheck="false" />

		<!-- submit -->
		<span class="wcpt-search-submit">
			<?php wcpt_icon('search', 'wcpt-search-submit-icon'); ?>
		</span>

		<!-- clear -->
		<?php if (!empty($keyword)) { ?>
			<span href="javascript:void(0)" class="wcpt-search-clear">
				<?php wcpt_icon('x', 'wcpt-search-clear-icon'); ?>
			</span>
		<?php } ?>

	</div>
</div>
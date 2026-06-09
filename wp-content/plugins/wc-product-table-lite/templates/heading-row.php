<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}
?>
<thead>
	<?php
	$hide_headings = true;
	$headings_markup = [];

	if (!empty($columns)) {
		foreach ($columns as $column_index => $column) {
			$GLOBALS['wcpt_col_index'] = $column_index;
			wcpt_parse_style_2($column['heading']);

			$heading_id = isset($column['heading']['id']) ? $column['heading']['id'] : $column_index;
			$col_heading_id = "wcpt-" . esc_attr($heading_id);

			// Get the content for the heading cell
			$curr_heading_markup = wcpt_parse_2(isset($column['heading']['content']) ? $column['heading']['content'] : '');

			if (!empty($curr_heading_markup)) {
				$hide_headings = false;
			}

			$column_name = !empty($column['name']) ? $column['name'] : "column {$column_index}";
			$column_name_attr = esc_attr($column_name);

			// Compose the TH element using clear string interpolation and attribute formatting
			$th_attributes = sprintf(
				'class="wcpt-heading %s" data-wcpt-column-index="%d" data-wcpt-column-name="%s" %s',
				$col_heading_id,
				$column_index,
				$column_name_attr,
				apply_filters('wcpt_heading_cell_html_attributes', '', $column)
			);

			$headings_markup[] = sprintf(
				'<th %s>%s</th>',
				$th_attributes,
				$curr_heading_markup
			);
		}
	}
	?>

	<?php do_action('wcpt_before_heading_row', $columns, $device); ?>

	<tr class="wcpt-heading-row<?php echo $hide_headings ? ' wcpt-hide' : ''; ?>">
		<?php
		do_action('wcpt_after_heading_row_open');
		echo implode("\n", $headings_markup);
		do_action('wcpt_before_heading_row_close');
		?>
	</tr>

	<?php do_action('wcpt_after_heading_row', $columns, $device); ?>
</thead>
<?php

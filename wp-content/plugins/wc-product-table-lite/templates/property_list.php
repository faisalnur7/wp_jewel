<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

if (empty($initial_reveal)) {
	$initial_reveal = 999;
}

if (
	!defined('WCPT_PRO') &&
	(
		empty($layout) ||
		!in_array($layout, ['row', 'column'])
	)
) {
	$layout = 'row';
}

if (empty($layout)) {
	$layout = 'grid';
}

if ($layout == 'row') {
	$columns = 1;
}

if ($layout == 'column') {
	$columns = $column_columns ?? 1;
}

if ($layout == 'grid') {
	$columns = $grid_columns ?? 1;
}

if ($layout == 'table') {
	$columns = $table_columns ?? 1;
}

if ($layout == 'bar') {
	$columns = 1;
	$initial_reveal = 999;
}

if ($layout == 'justified') {
	$columns = $justified_columns ?? 1;
}

// normalize columns
if (empty($columns) || $columns < 1) {
	$columns = 1;
}

if (empty($row_layout_name_value_separator_character)) {
	$row_layout_name_value_separator_character = '';
}

if (empty($column_layout_name_value_separator_character)) {
	$column_layout_name_value_separator_character = '';
}

if (empty($table_layout_name_value_separator_character)) {
	$table_layout_name_value_separator_character = '';
}

if (empty($justified_layout_name_value_separator_character)) {
	$justified_layout_name_value_separator_character = '';
}

// add layout and column classes
$html_class .= ' wcpt-property-list--' . $layout . '-layout ';
$html_class .= ' wcpt-property-list--' . $columns . '-column ';

// full width
$full_width = false;
switch ($layout) {
	case 'column':
		$full_width = $column_full_width ?? true;
		break;
	case 'grid':
		$full_width = $grid_full_width ?? true;
		break;
	case 'justified':
		$full_width = $justified_full_width ?? true;
		break;
	case 'table':
		$full_width = $table_full_width ?? true;
		break;
	case 'row':
		// Row layout doesn't use full width
		$full_width = false;
		break;
}

if ($full_width && $layout != 'row') {
	$html_class .= ' wcpt-property-list--full-width ';
}

// border and padding
$border_and_padding = false;
$only_border_top_and_bottom = false;
switch ($layout) {
	case 'row':
		$border_and_padding = $row_border_and_padding ?? false;
		$only_border_top_and_bottom = $row_only_border_top_and_bottom ?? false;
		break;
	case 'column':
		$border_and_padding = $column_border_and_padding ?? false;
		$only_border_top_and_bottom = $column_only_border_top_and_bottom ?? false;
		break;
	case 'grid':
		$border_and_padding = $grid_border_and_padding ?? false;
		$only_border_top_and_bottom = $grid_only_border_top_and_bottom ?? false;
		break;
	case 'bar':
		$border_and_padding = $bar_border_and_padding ?? false;
		$only_border_top_and_bottom = $bar_only_border_top_and_bottom ?? false;
		break;
	case 'justified':
		$border_and_padding = $justified_border_and_padding ?? false;
		$only_border_top_and_bottom = $justified_only_border_top_and_bottom ?? false;
		break;
	case 'table':
		// Table is the only layout where border and padding are always enabled by default;
		// do not disable for 'table'.
		$border_and_padding = true;
		$only_border_top_and_bottom = false;
		break;
}

// bar layout flip name and value
if ($layout == 'bar' && !empty($bar_layout_flip_name_and_value)) {
	$html_class .= ' wcpt-property-list--bar-layout-flip-name-and-value ';
}

if (empty($border_and_padding)) {
	$html_class .= ' wcpt-property-list--disable-border-and-padding ';
} else if ($only_border_top_and_bottom) {
	$html_class .= ' wcpt-property-list--only-border-top-and-bottom ';
}

// row separator for column and justified layouts
if ($layout == 'column') {
	if (empty($column_row_separator)) {
		$html_class .= ' wcpt-property-list--disable-column-row-separator ';
	}
}

// column separator for column and justified layouts
if ($layout == 'column' || $layout == 'justified') {
	if (empty($column_separator)) {
		$html_class .= ' wcpt-property-list--disable-column-separator ';
	}
}

// disable wrapping
if (!empty($disable_wrapping) && in_array($layout, ['column', 'justified'])) {
	$html_class .= ' wcpt-property-list--disable-wrapping ';
}

// hide property name
if ($layout == 'row' && !empty($row_hide_property_names)) {
	$html_class .= ' wcpt-property-list--row-hide-property-names ';
}

$hide_toggle = true;

ob_start();

?>
<div class="wcpt-pl-inner">
	<?php
	$displayed = 0;
	$current_column = 0;
	$row_open = false;
	$column_properties = [];
	$grid_properties = [];
	$justified_properties = [];
	$bar_properties = [];

	foreach ($rows as $row) {
		if (
			function_exists('wcpt_condition') &&
			!wcpt_condition($row['condition'])
		) {
			continue;
		}

		$displayed++;

		// determine if this property should be initially hidden
		$is_hidden = false;
		if ($displayed > $initial_reveal) {
			$is_hidden = true;
			$hide_toggle = false;
		}

		// row layout: all properties in a single horizontal row
		if ($layout == 'row') {
			if ($is_hidden) {
				$hide_class = ' wcpt-tg-hide ';
			} else {
				$hide_class = '';
			}

			if ($displayed > 1) {
				echo '<span class="wcpt-property-separator ' . $hide_class . '"></span>';
			}

			echo '<span class="wcpt-pl-row ' . $hide_class . '">';
			echo '<span class="wcpt-property-name">' . wcpt_parse_2($row['property_name']) . '<span class="wcpt-property-name-value-separator">' . $row_layout_name_value_separator_character . '</span></span>';
			echo '<span class="wcpt-property-value">' . wcpt_parse_2($row['property_value']) . '</span>';
			echo '</span>';

			continue;
		}

		// column layout: collect properties first, then distribute into columns
		if ($layout == 'column') {
			// Store properties for column distribution
			$column_properties[] = [
				'property_name' => $row['property_name'],
				'property_value' => $row['property_value'],
				'is_hidden' => $is_hidden,
			];
			continue;
		}

		// grid layout: collect properties first, then output in grid
		if ($layout == 'grid') {
			// Store properties for grid distribution
			$grid_properties[] = [
				'property_name' => $row['property_name'],
				'property_value' => $row['property_value'],
				'is_hidden' => $is_hidden,
			];
			continue;
		}

		// justified layout: collect properties first, then distribute into columns (like column but with dots)
		if ($layout == 'justified') {
			// Store properties for justified distribution
			$justified_properties[] = [
				'property_name' => $row['property_name'],
				'property_value' => $row['property_value'],
				'is_hidden' => $is_hidden,
			];
			continue;
		}

		// bar layout: collect properties first, then output in single row
		if ($layout == 'bar') {
			// Store properties for bar distribution
			$bar_properties[] = [
				'property_name' => $row['property_name'],
				'property_value' => $row['property_value'],
				'is_hidden' => $is_hidden,
			];
			continue;
		}

		// table layout: group multiple properties into the same table row
		if ($layout == 'table') {
			if (!$row_open) {
				echo '<div class="wcpt-pl-row">';
				$row_open = true;
				$current_column = 0;
			}

			$name_class = 'wcpt-property-name';
			$value_class = 'wcpt-property-value';

			if ($is_hidden) {
				$name_class .= ' wcpt-tg-hide';
				$value_class .= ' wcpt-tg-hide';
			}

			echo '<span class="' . $name_class . '">' . wcpt_parse_2($row['property_name']) . '<span class="wcpt-property-name-value-separator">' . $table_layout_name_value_separator_character . '</span></span>';
			echo '<span class="' . $value_class . '">' . wcpt_parse_2($row['property_value']) . '</span>';

			$current_column++;

			// close the row after the configured number of properties
			if ($current_column >= $columns) {
				echo '</div>';
				$row_open = false;
				$current_column = 0;
			}

			continue;
		}

		// non table layout: keep original behavior (one property per row)
		if (empty($table_layout)) {
			if ($is_hidden) {
				$hide_class = ' wcpt-tg-hide ';
			} else {
				$hide_class = '';
			}

			echo '<div class="wcpt-pl-row ' . $hide_class . '">';
			echo '<span class="wcpt-property-name">' . wcpt_parse_2($row['property_name']) . '</span>';
			echo '<span class="wcpt-property-value">' . wcpt_parse_2($row['property_value']) . '</span>';
			echo '</div>';

			continue;
		}


	}

	// close any open row if there were leftover properties (for table layout)
	// fill remaining cells with empty cells to complete the table row
	// exception: only fill if we have at least one complete row (enough properties for a full row)
	if ($layout == 'table' && $row_open) {
		// only fill remaining cells if we have enough properties to make at least one complete row
		if ($displayed >= $columns) {
			// Match real cells: hide fillers when the last property in this row is past initial reveal
			// (same rule as $is_hidden for the property at $displayed).
			$filler_hide = ($displayed > $initial_reveal) ? ' wcpt-tg-hide' : '';
			while ($current_column < $columns) {
				echo '<span class="wcpt-property-name' . $filler_hide . '">-</span>';
				echo '<span class="wcpt-property-value' . $filler_hide . '">-</span>';
				$current_column++;
			}
		}
		echo '</div>';
	}

	// column layout: distribute properties into columns with separators
	if ($layout == 'column' && !empty($column_properties)) {
		$total_properties = count($column_properties);

		// reduce the effective number of columns if there are fewer items than columns
		$effective_columns = min($columns, $total_properties);
		if ($effective_columns < 1) {
			$effective_columns = 1;
		}

		// how many items should be initially visible in total
		$visible_total = min($initial_reveal, $total_properties);

		// compute how many items are initially visible in each column so that
		// the initial reveal is distributed across columns
		$visible_per_column = array_fill(0, $effective_columns, 0);
		if ($visible_total > 0) {
			$base_visible = intdiv($visible_total, $effective_columns);
			$extra_visible = $visible_total % $effective_columns;

			for ($i = 0; $i < $effective_columns; $i++) {
				$visible_per_column[$i] = $base_visible + ($i < $extra_visible ? 1 : 0);
			}
		}

		// Distribute properties in a way that fills all columns as evenly as possible (round robin)
		$column_data = [];
		for ($i = 0; $i < $effective_columns; $i++) {
			$column_data[$i] = [];
		}

		$property_index = 0;
		foreach ($column_properties as $prop) {
			$col = $property_index % $effective_columns;
			$column_data[$col][] = $prop;
			$property_index++;
		}

		// Output columns with separators
		foreach ($column_data as $col_index => $props) {
			if ($col_index > 0 && $col_index < $effective_columns && array_sum(array_map('count', $column_data)) > array_sum(array_map('count', array_slice($column_data, 0, $col_index)))) {
				echo '<div class="wcpt-pl-column-separator"></div>';
			}

			echo '<div class="wcpt-pl-column">';
			$prop_index = 0;
			$column_visible_limit = $visible_per_column[$col_index] ?? 0;

			foreach ($props as $prop) {
				// decide visibility based on per-column index to distribute initial reveal
				$is_hidden = ($visible_total > 0 && $prop_index >= $column_visible_limit) || ($visible_total === 0);
				$hide_class = $is_hidden ? ' wcpt-tg-hide ' : '';

				// Add row separator after each row except the last one
				echo '<span class="wcpt-pl-row-separator ' . $hide_class . '"></span>';
				echo '<div class="wcpt-pl-row ' . $hide_class . '">';
				echo '<span class="wcpt-property-name">' . wcpt_parse_2($prop['property_name']) . '<span class="wcpt-property-name-value-separator">' . $column_layout_name_value_separator_character . '</span></span>';
				echo '<span class="wcpt-property-value">' . wcpt_parse_2($prop['property_value']) . '</span>';
				echo '</div>';

				$prop_index++;
			}
			echo '</div>';
		}
	}

	// grid layout: output properties in grid format
	if ($layout == 'grid' && !empty($grid_properties)) {
		foreach ($grid_properties as $prop) {
			$hide_class = $prop['is_hidden'] ? ' wcpt-tg-hide ' : '';
			echo '<div class="wcpt-pl-row ' . $hide_class . '">';
			echo '<span class="wcpt-property-name">' . wcpt_parse_2($prop['property_name']) . '</span>';
			echo '<span class="wcpt-property-value">' . wcpt_parse_2($prop['property_value']) . '</span>';
			echo '</div>';
		}
	}

	// justified layout: distribute properties into columns (like column but with dots between name and value, no row separators)
	if ($layout == 'justified' && !empty($justified_properties)) {
		$total_properties = count($justified_properties);

		// reduce the effective number of columns if there are fewer items than columns
		$effective_columns = min($columns, $total_properties);
		if ($effective_columns < 1) {
			$effective_columns = 1;
		}

		// how many items should be initially visible in total
		$visible_total = min($initial_reveal, $total_properties);

		// compute how many items are initially visible in each column so that
		// the initial reveal is distributed across columns
		$visible_per_column = array_fill(0, $effective_columns, 0);
		if ($visible_total > 0) {
			$base_visible = intdiv($visible_total, $effective_columns);
			$extra_visible = $visible_total % $effective_columns;

			for ($i = 0; $i < $effective_columns; $i++) {
				$visible_per_column[$i] = $base_visible + ($i < $extra_visible ? 1 : 0);
			}
		}

		// Distribute properties in a way that fills all columns as evenly as possible (round robin)
		$column_data = [];
		for ($i = 0; $i < $effective_columns; $i++) {
			$column_data[$i] = [];
		}

		$property_index = 0;
		foreach ($justified_properties as $prop) {
			$col = $property_index % $effective_columns;
			$column_data[$col][] = $prop;
			$property_index++;
		}

		// Output columns with separators (but no row separators)
		foreach ($column_data as $col_index => $props) {
			if ($col_index > 0 && $col_index < $effective_columns && array_sum(array_map('count', $column_data)) > array_sum(array_map('count', array_slice($column_data, 0, $col_index)))) {
				echo '<div class="wcpt-pl-column-separator"></div>';
			}

			echo '<div class="wcpt-pl-column">';
			$prop_index = 0;
			$column_visible_limit = $visible_per_column[$col_index] ?? 0;

			foreach ($props as $prop) {
				// decide visibility based on per-column index to distribute initial reveal
				$is_hidden = ($visible_total > 0 && $prop_index >= $column_visible_limit) || ($visible_total === 0);
				$hide_class = $is_hidden ? ' wcpt-tg-hide ' : '';

				echo '<div class="wcpt-pl-row ' . $hide_class . '">';
				echo '<span class="wcpt-property-name">' . wcpt_parse_2($prop['property_name']) . '<span class="wcpt-property-name-value-separator">' . $justified_layout_name_value_separator_character . '</span></span>';
				echo '<span class="wcpt-property-dot-separator"></span>';
				echo '<span class="wcpt-property-value">' . wcpt_parse_2($prop['property_value']) . '</span>';
				echo '</div>';

				$prop_index++;
			}
			echo '</div>';
		}
	}

	// bar layout: output properties in single horizontal row with name above value
	if ($layout == 'bar' && !empty($bar_properties)) {
		$bar_count = count($bar_properties);
		$bar_index = 0;
		foreach ($bar_properties as $prop) {
			$hide_class = $prop['is_hidden'] ? ' wcpt-tg-hide ' : '';
			echo '<div class="wcpt-pl-row ' . $hide_class . '">';
			echo '<span class="wcpt-property-name">' . wcpt_parse_2($prop['property_name']) . '</span>';
			echo '<span class="wcpt-property-value">' . wcpt_parse_2($prop['property_value']) . '</span>';
			echo '</div>';

			// Add separator after each property except the last one
			if ($bar_index < $bar_count - 1) {
				echo '<div class="wcpt-pl-bar-separator"></div>';
			}
			$bar_index++;
		}
	}
	?>
</div>
<?php

$template = ob_get_clean();

// Don't output anything if no rows were displayed
if ($displayed === 0) {
	return;
}

$toggle_class = '';
$toggle_mkp = '';

if (!$hide_toggle) {
	$toggle_class = 'wcpt-toggle wcpt-tg-off';
	$toggle_on = '<span class="wcpt-tg-on-label">' . wcpt_parse_2($show_more_label ?? 'Show more') . '</span>';
	$toggle_off = '<span class="wcpt-tg-off-label">' . wcpt_parse_2($show_less_label ?? 'Show less') . '</span>';
	ob_start();
	$layout == 'row' ? wcpt_icon('plus', 'wcpt-toggle-closed') . wcpt_icon('minus', 'wcpt-toggle-opened') : wcpt_icon('chevron-down', 'wcpt-toggle-rotate');
	$toggle_icon = ob_get_clean();
	$toggle_mkp = '<span class="wcpt-tg-trigger">' . $toggle_icon . $toggle_on . $toggle_off . '</span>';
}

echo '<div class="wcpt-property-list ' . $toggle_class . ' ' . $html_class . '">' . $template . $toggle_mkp . '</div>';

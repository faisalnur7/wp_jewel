<?php do_action('wcpt_before_cell_open', $column, $column_index); ?>
<td
  class="<?php echo apply_filters('wcpt_cell_html_class', 'wcpt-cell wcpt-' . $column['cell']['id'], $column, $column_index); ?>"
  data-wcpt-column-index="<?php echo esc_attr($column_index); ?>" <?php echo apply_filters('wcpt_cell_html_attributes', '', $column); ?>
  data-wcpt-column-name="<?php echo esc_attr(empty($column['name']) ? "column " . $column_index : $column['name']); ?>">
  <?php do_action('wcpt_after_cell_open', $column, $column_index); ?>
<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

$table_data = wcpt_get_table_data();
$html_class = trim(apply_filters('wcpt_container_html_class', 'wcpt wcpt-' . $table_data['id'] . ' ' . trim($this->attributes['class']) . ' ' . trim($this->attributes['html_class'])));

?>
<div id="wcpt-<?php echo esc_attr($table_data['id']); ?>" class="<?php echo esc_attr($html_class); ?>" <?php echo wcpt_get_container_attributes(); ?>>
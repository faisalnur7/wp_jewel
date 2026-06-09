<?php
$property_label_html = '';
if (!empty($enable_property_label)) {

  if (empty($html_class)) {
    $html_class = '';
  }

  $html_class .= ' wcpt-property-label-enabled ';

  $text = !empty($property_label_text) ? $property_label_text : '';
  $icon_html = '';

  if (wcpt_pro_enabled()) {
    // values set by the editor UI
    $name = isset($property_label_icon_name) ? $property_label_icon_name : '';
    $icon_source = isset($property_label_icon_source) ? $property_label_icon_source : 'included';
    $custom_icon = isset($property_label_custom_icon) ? $property_label_custom_icon : '';
    $title = '';
    $tooltip = '';
    $thickness = '';
    $color = '';
    $size = '';

    // temporarily override $html_class for icon.php and restore it
    $__old_html_class = $html_class;
    $html_class = 'wcpt-property-label-icon';

    ob_start();
    include 'icon.php';
    $icon_html = ob_get_clean();

    $html_class = $__old_html_class;
  }

  $property_label_html = '<div class="wcpt-property-label">';
  $property_label_html .= $icon_html;
  if (!empty($text)) {
    $property_label_html .= '<span class="wcpt-property-label-text">' . esc_html($text) . '</span>';
  }
  $property_label_html .= '</div>';
}
<?php

if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}

$label = array(
  array(
    "id" => $id . "-label",
    "style" => [],
    "elements" => [],
    "type" => "row"
  )
);

if (!empty($label_icon) && $label_icon !== 'none') {
  $label[0]['elements'][] = array(
    "name" => $label_icon,
    "style" => [],
    "type" => "icon",
    "id" => $id . "-label-icon"
  );
}

if (!empty($label_text)) {
  $label[0]['elements'][] = array(
    "text" => $label_text,
    "style" => [],
    "type" => "text",
    "id" => $id . "-label-text"
  );
}

if (empty($link)) {
  $link = 'custom_field';
}

include(WCPT_PLUGIN_PATH . 'templates/button.php');

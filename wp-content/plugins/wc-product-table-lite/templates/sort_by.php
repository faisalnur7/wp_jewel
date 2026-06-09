<?php
if (!defined('ABSPATH')) {
  exit;
}

$table_data = wcpt_get_table_data();
$table_id = $table_data['id'];

$field_name_orderby = $table_id . '_orderby';

// get table's original default orderby params (from table query settings,
// not from the visitor's current selection / user filters)
$default_params = array(
  'filter' => 'orderby',
  'orderby' => !empty($table_data['query']['orderby']) ? $table_data['query']['orderby'] : 'date',
  'order' => !empty($table_data['query']['order']) ? $table_data['query']['order'] : 'DESC',
  'meta_key' => !empty($table_data['query']['meta_key']) && in_array($table_data['query']['orderby'], array('meta_value_num', 'meta_value')) ? $table_data['query']['meta_key'] : '',
);

// carry over extra criteria for category / attribute / taxonomy defaults
if (!empty($table_data['query']['orderby'])) {
  $keys = array();

  // category
  if ($table_data['query']['orderby'] == 'category') {
    $keys = array_merge(
      $keys,
      array(
        'orderby_focus_category',
        'orderby_ignore_category',
      )
    );
  }

  // attribute
  if (in_array($table_data['query']['orderby'], array('attribute', 'attribute_num'))) {
    $keys = array_merge(
      $keys,
      array(
        'orderby_attribute',
        'orderby_focus_attribute_term',
        'orderby_ignore_attribute_term',
        'orderby_attribute_include_all',
      )
    );
  }

  // taxonomy
  if ($table_data['query']['orderby'] == 'taxonomy') {
    $keys = array_merge(
      $keys,
      array(
        'orderby_taxonomy',
        'orderby_focus_taxonomy_term',
        'orderby_ignore_taxonomy_term',
        'orderby_taxonomy_include_all',
      )
    );
  }

  foreach ($keys as $key) {
    if (!empty($table_data['query'][$key])) {
      $default_params[$key] = $table_data['query'][$key];
    }
  }
}

// ensure drop down options
if (empty($dropdown_options)) {
  $dropdown_options = json_decode('[{"orderby":"popularity","order":"DESC","meta_key":"","label":"Sort by Popularity"},{"orderby":"rating","order":"DESC","meta_key":"","label":"Sort by Rating"},{"orderby":"price","order":"ASC","meta_key":"","label":"Sort by Price low to high"},{"orderby":"price-desc","order":"DESC","meta_key":"","label":"Sort by Price high to low"},{"orderby":"date","order":"DESC","meta_key":"","label":"Sort by Newness"},{"orderby":"title","order":"ASC","meta_key":"","label":"Sort by Name A - Z"},{"orderby":"title","order":"DESC","meta_key":"","label":"Sort by Name Z - A"}]', true);
}

// maybe add search relevance option
$relevance_option = wcpt_maybe_apply_sortby_relevance();

foreach ($_GET as $key => $val) {
  if (
    strpos($key, $table_id . '_search') !== false &&
    $val
  ) {
    $relevance_option = true;
    break;
  }
}

// and keep it hidden until search is used
if ($relevance_option) {
  // add the option so it can be auto-selected
  $relevance_params = array(
    'label' => __('Relevance', 'woocoommerce'),
    'orderby' => 'relevance',
    'order' => 'DESC',
  );

  $settings = wcpt_get_settings_data();
  if (!empty($settings['search']['relevance_label'])) {
    $locale = get_locale();
    $translations = array();
    $translation = false;

    foreach (preg_split('/$\R?^/m', trim($settings['search']['relevance_label'])) as $translation_rule) {
      $array = explode(':', $translation_rule);

      // rule with locale code: translation
      if (!empty($array[1])) {
        $translations[trim($array[0])] = stripslashes(trim($array[1]));

        // rule with just default translation
      } else {
        $translations['default'] = stripslashes(trim($array[0]));
      }
    }

    // maybe use defaults
    if (empty($translations[$locale])) {

      if (!empty($translations['default'])) {
        $translation = $translations['default'];
      } else if (!empty($translations['en_US'])) {
        $translation = $translations['en_US'];
      }

    } else {
      $translation = $translations[$locale];

    }

  }

  if (!empty($translation)) {
    $relevance_params['label'] = $translation;
  }

  $dropdown_options[] = $relevance_params;
  $relevance_index = count($dropdown_options) - 1;

  if (
    empty($_GET[$field_name_orderby]) &&
    empty($table_data['query']['sc_attrs']['search_orderby'])
  ) {
    $_GET[$field_name_orderby] = 'relevance';
  }

} else if (
  !empty($_GET[$field_name_orderby]) &&
  $_GET[$field_name_orderby] === 'relevance'
) {
  // option to sort by relevance should not exist
  $_GET[$field_name_orderby] = '';

}

$default_index = wcpt_sortby_get_matching_option_index($default_params, $dropdown_options);

// unique case of sku for default match
if (
  false === $default_index &&
  !empty($default_params['meta_key']) &&
  $default_params['meta_key'] == '_sku'
) {
  $arr = array(
    'orderby' => $default_params['orderby'] === 'meta_value' ? 'sku' : 'sku_num',
    'order' => $default_params['order']
  );
  $default_index = wcpt_sortby_get_matching_option_index($arr, $dropdown_options);
}

// if no dropdown option matches the default, add default as the last option
if ($default_index === false) {
  $default_option = array(
    'label' => !empty($default_option_label) ? esc_html($default_option_label) : __('Sort by ', 'wc-product-table'),
    'orderby' => $default_params['orderby'],
    'order' => isset($default_params['order']) ? $default_params['order'] : 'ASC',
    'meta_key' => isset($default_params['meta_key']) ? $default_params['meta_key'] : '',
  );
  $dropdown_options[] = $default_option;
  $default_index = count($dropdown_options) - 1;
}

// get current orderby

//-- none selected
if (empty($_GET[$field_name_orderby])) {

  $selected_index = $default_index;

  // matching option found (default was already ensured above)
  $current_params = $dropdown_options[$selected_index];

  //-- user selected
} else {

  $orderby = $_GET[$field_name_orderby];
  //-- -- column sort
  if (substr($orderby, 0, 7) == 'column_') {

    $data =& $GLOBALS['wcpt_table_data'];
    $sort_id = substr($orderby, 7);
    $device = empty($_GET[$table_id . '_device']) ? 'laptop' : (string) $_GET[$table_id . '_device'];

    $column_sorting = wcpt_get_column_sorting_info($sort_id, $device);

    $current_order = isset($_GET[$table_id . '_order']) ? strtolower($_GET[$table_id . '_order']) : 'desc';
    $column_sorting['order'] = in_array($current_order, array('asc', 'desc')) ? $current_order : 'desc';
    if ($column_sorting['orderby'] == 'price' && strtolower($current_order) == 'desc') {
      $column_sorting['orderby'] = 'price-desc';
    }

    $selected_index = wcpt_sortby_get_matching_option_index($column_sorting, $dropdown_options);

    // matching option found
    if ($selected_index !== false) {
      $current_params = $dropdown_options[$selected_index];

      // no matching option
    } else {

      // add the column as an option
      $label = __('Sort by ', 'wc-product-table');
      $label_suffix = '';

      if (in_array($column_sorting['orderby'], array('meta_value', 'meta_value_num'))) {
        $label_suffix = $column_sorting['meta_key'];
      } else {
        $label_suffix = $column_sorting['orderby'];

        if ($label_suffix == 'id') {
          $label_suffix = 'ID';
        }

        if ($label_suffix == 'sku') {
          $label_suffix = 'SKU';
        }

        if ($column_sorting['orderby'] == 'attribute' || $column_sorting['orderby'] == 'attribute_num') {
          $label_suffix = 'Attribute';

          if (!empty($column_sorting['orderby_attribute'])) {
            $attribute = wc_get_attribute(wc_attribute_taxonomy_id_by_name($column_sorting['orderby_attribute']));
            $label_suffix = $attribute ? $attribute->name : $column_sorting['orderby_attribute'];
          }
        }
      }

      $label_suffix = strtoupper($label_suffix[0]) . substr($label_suffix, 1);

      $current_params = array(
        'label' => $label . $label_suffix,
        'orderby' => $column_sorting['orderby'],
        'order' => $column_sorting['order'],
        'meta_key' => isset($column_sorting['meta_key']) ? $column_sorting['meta_key'] : false,
      );

      $dropdown_options[] = $current_params;
      $selected_index = count($dropdown_options) - 1;

    }

    //-- -- manual
  } else if (is_numeric($orderby)) {

    $selected_index = $orderby - 1;
    $current_params = $dropdown_options[$selected_index];

    $current_params['filter'] = 'orderby';
    wcpt_update_user_filters($current_params, true);

    //-- -- dropdown
  } else if (substr($orderby, 0, 7) == 'option_') {

    $selected_index = (int) substr($orderby, 7);

    if (empty($dropdown_options[$selected_index])) {
      $dropdown_options[$selected_index] = array(
        'label' => __('Sort by ', 'wc-product-table'),
        'orderby' => $default_params['orderby'],
        'order' => $default_params['order'],
        'meta_key' => $default_params['meta_key'],
      );
    }

    $current_params = $dropdown_options[$selected_index];

    $current_params['filter'] = 'orderby';
    wcpt_update_user_filters($current_params, true);

    //-- -- search relevance
  } else if ($orderby === 'relevance') {
    $selected_index = $relevance_index;
    $current_params = $dropdown_options[$relevance_index];

    $current_params['filter'] = 'orderby';
    wcpt_update_user_filters($current_params, true);

  }

}

if (
  empty($display_type) ||
  (!empty($position) && $position === 'left_sidebar')
) {
  $display_type = 'dropdown';
}

if ($display_type == 'dropdown') {
  $container_html_class = 'wcpt-dropdown wcpt-filter ' . $html_class;
  $heading_html_class = 'wcpt-dropdown-label';
  $options_container_html_class = 'wcpt-dropdown-menu';
  $single_option_container_html_class = 'wcpt-dropdown-option';

} else {
  $container_html_class = 'wcpt-options-row wcpt-filter ' . $html_class;
  $heading_html_class = 'wcpt-options-heading';
  $options_container_html_class = 'wcpt-options';
  $single_option_container_html_class = 'wcpt-option';

}

// heading row
if (empty($heading)) {
  $heading = '';
}

if (!$heading = wcpt_parse_2($heading)) {
  $container_html_class .= ' wcpt-no-heading wcpt-filter-open';
}

// filter open in sidebar
$accordion_always_open = true;
if (
  !empty($position) &&
  $position === 'left_sidebar' &&
  !empty($accordion_always_open)
) {
  $container_html_class .= ' wcpt-filter-open wcpt-open';
}

?>
<div class="<?php echo $container_html_class; ?>" data-wcpt-filter="sort_by" <?php
   if (
     $position == 'header' &&
     $display_type == 'dropdown'
   ) {
     ?> data-wcpt-heading_format__op_selected="only_selected" <?php } ?>>
  <?php
  ob_start();
  ?>

  <!-- options menu -->
  <div class="<?php echo $options_container_html_class; ?>">
    <?php
    $selected_label = '';
    foreach ($dropdown_options as $option_index => $option) {

      if ($selected_index == $option_index) {
        $checked = ' checked="checked" ';
        $selected_label = $option['label'];
        $active = ' wcpt-active ';
      } else {
        $checked = '';
        $active = '';
      }

      $value = $option['orderby'] == 'relevance' ? 'relevance' : 'option_' . $option_index;

      ?>
      <div class="<?php echo $single_option_container_html_class; ?>">
        <label class="<?php echo $active . (($default_index === $option_index) ? ' wcpt-default-option' : ''); ?>">
          <input type="radio" name="<?php echo $field_name_orderby; ?>" <?php echo $checked; ?>
            value="<?php echo $value; ?>" class="wcpt-filter-radio"><span>
            <?php echo $option['label']; ?>
          </span>
        </label>
      </div>
      <?php
    }

    ?>
  </div>

  <?php
  $dropdown_menu = ob_get_clean();
  ?>

  <div class="wcpt-filter-heading">
    <!-- icon -->
    <?php if (!empty($enable_icon) && $position == 'header'): ?>
      <?php wcpt_icon('sort-arrows'); ?>
    <?php endif; ?>
    <!-- label -->
    <span class="<?php echo $heading_html_class; ?>">
      <!-- icon -->
      <?php if (!empty($enable_icon) && $position !== 'header'): ?>
        <?php wcpt_icon('sort-arrows'); ?>
      <?php endif; ?>
      <span>
        <?php echo $display_type == 'dropdown' && $position == 'header' ? $selected_label : $heading; ?>
      </span>
    </span>
    <!-- icon -->
    <?php wcpt_icon('chevron-down'); ?>
  </div>

  <?php echo $dropdown_menu; ?>

</div>
<?php
// update table data based on current version
// also see: 'global settings for plugin'
add_filter('wcpt_data', 'wcpt_update_table_data', 10, 1);
function wcpt_update_table_data($data)
{
  // data up to date
  if (
    !empty($data['version']) &&
    $data['version'] === WCPT_VERSION
  ) {
    return $data;
  }

  // ensure version number
  if (empty($data['version'])) { // <= 1.9.0
    $data['version'] = '1.8.0';
  }

  // clear older table settings backup data
  wcpt_clear_older_table_settings_backup_data($data['id']);

  // backup current data
  if (!get_post_meta($data['id'], 'wcpt_data_' . $data['version'], true)) {
    update_post_meta($data['id'], 'wcpt_data_' . $data['version'], addslashes(json_encode($data)));
  } else {
    $count = 0;

    while (++$count) {
      $name = 'wcpt_data_' . $data['version'] . '_' . $count;
      if (!get_post_meta($data['id'], $name, true)) {
        update_post_meta($data['id'], $name, addslashes(json_encode($data)));
        break;
      }
    }
  }

  // update to 1.9.0
  if (version_compare($data['version'], '1.9.0', '<')) {

    // nav: search filter
    $searches = wcpt_get_nav_elms_ref('search', $data);

    foreach ($searches as &$search) {
      $search['attributes'] = array();
      if ($search['custom_fields'] && gettype($search['custom_fields']) === 'string') {
        $search['custom_fields'] = array_map('trim', preg_split('/\r\n|\r|\n/', $search['custom_fields']));
      }

      if (gettype($search['target']) === 'string') {
        $target = array();

        if (!empty($search['target'])) {
          foreach (array('title', 'content', 'custom_field') as $field) {
            if (FALSE !== strrpos($search['target'], $field)) {
              $target[] = $field;
            }
          }

        } else {
          $target = array('title', 'content');
        }

        $search['target'] = $target;
      }

    }

  }

  // update to 2.0.0
  if (version_compare($data['version'], '2.0.0', '<')) {
    $attr_elements = wcpt_get_col_elms_ref('attribute', $data);

    foreach ($attr_elements as &$element) {
      if (!empty($element['filter_link_term'])) {
        $element['click_action'] = 'trigger_filter';
        unset($element['filter_link_term']);
      }
    }

    unset($attr_elements);
    unset($element);

  }

  // update to 2.1.0
  if (version_compare($data['version'], '2.1.0', '<')) {

    // ensure date descending
    // -- query
    if (
      !empty($data['query']) &&
      !empty($data['query']['orderby']) &&
      $data['query']['orderby'] === 'date'
    ) {
      $data['query']['order'] = 'DESC';
    }

    // nav sort by element
    $sort_by_elements = wcpt_get_nav_elms_ref('sort_by', $data);
    foreach ($sort_by_elements as &$element) {
      foreach ($element['dropdown_options'] as &$option) {
        if ($option['orderby'] === 'date') {
          $option['order'] === 'DESC';
        }
      }
    }

    unset($sort_by_elements);
    unset($element);

  }

  // update to 2.2.0
  if (version_compare($data['version'], '2.2.0', '<')) {

    // Availability element, adding 'In stock, managed' message
    $av_elements = wcpt_get_col_elms_ref('availability', $data);

    foreach ($av_elements as &$element) {
      if (
        !empty($element['in_stock_message']) &&
        empty($element['in_stock_managed_message'])
      ) {
        $element['in_stock_managed_message'] = $element['in_stock_message'];
      }
    }

    unset($av_elements);
    unset($element);

    // ToolTip style fix (col & nav)
    $tooltip_elements__col = wcpt_get_col_elms_ref(array('tooltip'), $data);
    $tooltip_elements__nav = wcpt_get_nav_elms_ref('tooltip__nav', $data);
    $tooltip_elements = array_merge($tooltip_elements__col, $tooltip_elements__nav);

    foreach ($tooltip_elements as &$element) {
      if (
        !empty($element['style']) &&
        !empty($element['style']['[id] > .wcpt-tooltip-content'])
      ) {
        $element['style']['[id] > .wcpt-tooltip-content-wrapper > .wcpt-tooltip-content'] = $element['style']['[id] > .wcpt-tooltip-content'];
        unset($element['style']['[id] > .wcpt-tooltip-content']);
      }
    }

    unset($tooltip_elements);
    unset($element);

    // Dimension variable_switch property added
    $dimension_elements = wcpt_get_col_elms_ref(array('dimension'), $data);

    foreach ($dimension_elements as &$element) {
      if (!isset($element['variable_switch'])) {
        $element['variable_switch'] = true;
      }
    }

    unset($dimension_elements);
    unset($element);

    // Stock variable_switch property added
    $stock_elements = wcpt_get_col_elms_ref(array('stock'), $data);

    foreach ($stock_elements as &$element) {
      if (!isset($element['variable_switch'])) {
        $element['variable_switch'] = true;
      }
    }

    unset($stock_elements);
    unset($element);

    // Nav filter elms dropdown & row html class changes
    $nav_elements = wcpt_get_nav_elms_ref(false, $data);

    foreach ($nav_elements as &$element) {
      if (
        !in_array(
          $element['type'],
          array(
            'sort_by',
            'results_per_page',
            'category_filter',
            'price_filter',
            'attribute_filter',
            'custom_field_filter',
            'taxonomy_filter',
            'availability_filter',
            'on_sale_filter',
            'rating_filter'
          )
        ) ||
        empty($element['style'])
      ) {
        continue;
      }

      // dropdown heading
      if (!empty($element['style']['[id]'])) {
        $element['style']['.wcpt-navigation:not(.wcpt-left-sidebar) [id].wcpt-dropdown.wcpt-filter > .wcpt-filter-heading'] = $element['style']['[id]'];
        unset($element['style']['[id]']);
      }

      // dropdown menu
      if (!empty($element['style']['[id] > .wcpt-dropdown-menu'])) {
        $element['style']['.wcpt-navigation:not(.wcpt-left-sidebar) [id].wcpt-dropdown.wcpt-filter > .wcpt-dropdown-menu'] = $element['style']['[id] > .wcpt-dropdown-menu'];
        unset($element['style']['[id] > .wcpt-dropdown-menu']);
      }

    }

    unset($nav_elements);
    unset($element);

  }


  // update to 2.5.1
  if (version_compare($data['version'], '2.5.1', '<')) {

    // Title link modified
    $title_elements = wcpt_get_col_elms_ref(array('title'), $data);

    foreach ($title_elements as &$element) {
      if (isset($element['product_link_enabled'])) {
        $element['link'] = 'product_page';
      } else {
        $element['link'] = '';
      }
    }

    unset($title_elements);
    unset($element);

  }

  // update to 3.1.0
  if (version_compare($data['version'], '3.1.0', '<')) {

    // Short description gets 'generate' option
    $short_description_elements = wcpt_get_col_elms_ref(array('short_description'), $data);

    foreach ($short_description_elements as &$element) {
      if (!isset($element['generate'])) {
        $element['generate'] = true;
      }
    }

    unset($short_description_elements);
    unset($element);

    // Product image element 'include_gallery' needs to be 'true'
    $product_image_elements = wcpt_get_col_elms_ref(array('product_image'), $data);

    foreach ($product_image_elements as &$element) {
      if (!isset($element['include_gallery'])) {
        $element['include_gallery'] = true;
      }
    }

    unset($product_image_elements);
    unset($element);
  }

  // update to 4.3.0
  if (version_compare($data['version'], '4.3.0', '<')) {

    $transparent_color = 'rgba(255, 255, 255, 0)';

    $style_data = $data['style'];
    foreach (['laptop', 'tablet', 'phone'] as $device) {
      if (!empty($style_data[$device])) {
        foreach (['even', 'odd'] as $string) {
          $row_selector = '[container] tr.wcpt-' . $string . ' > .wcpt-cell';
          if (empty($style_data[$device][$row_selector])) {
            $style_data[$device][$row_selector] = array();
          }
          if (empty($style_data[$device][$row_selector]['background-color'])) {
            $style_data[$device][$row_selector]['background-color'] = $transparent_color;
          }
        }
      }
    }

    $data['style'] = $style_data;
  }

  // update to 4.3.7
  if (version_compare($data['version'], '4.3.7', '<')) {
    $category_filter_elements = wcpt_get_nav_elms_ref('category_filter', $data);
    foreach ($category_filter_elements as &$element) {
      if (isset($element['exclude_terms'])) {
        $element['exclude_children_also'] = true;
      }
    }

    unset($category_filter_elements);
    unset($element);
  }

  // update to 4.5.0
  if (version_compare($data['version'], '4.5.0', '<')) {

    // navigation settings
    // -- default autoScroll
    if (!isset($data['navigation_settings'])) {
      $data['navigation_settings'] = [
        'autoScroll' => ['laptop', 'tablet', 'phone']
      ];
    }

    // -- transfer query v2 options
    if (!isset($data['query_v2'])) {
      $data['query_v2'] = [];
    }

    $transfer_options = [
      'dynamicHideFilters',
      'dynamicRecount',
      'dynamicFiltersLazyLoad',
      'disableAjax',
      'disableUrlUpdate',
      'lazyLoad',
      'noResultsMessage'
    ];

    foreach ($transfer_options as $option) {
      if (isset($data['query_v2'][$option])) {
        $data['navigation_settings'][$option] = $data['query_v2'][$option];
      }

      unset($data['query_v2'][$option]);
    }
  }

  // update to 4.5.5
  if (version_compare($data['version'], '4.5.5', '<')) {

    // attributes set type:global/custom if not set
    $attribute_elements = wcpt_get_col_elms_ref2(array('attribute'), $data);
    foreach ($attribute_elements as &$element) {
      if (!isset($element['attribute_type'])) {

        $attribute_type = 'global';

        if (
          isset($element['attribute_name']) &&
          $element['attribute_name'] == "_custom"
        ) {
          $attribute_type = 'custom';
        }

        $element['attribute_type'] = $attribute_type;
      }
    }

    unset($attribute_elements);
    unset($element);
  }

  // update to 4.7.0
  if (version_compare($data['version'], '4.7.0', '<')) {

    // navigation settings
    $data['navigation_settings']['dynamicFilterTypes'] = ['category', 'attribute', 'favorite', 'onSale', 'availability', 'tag', 'taxonomy', 'brand'];
  }

  // update to 4.7.5
  if (version_compare($data['version'], '4.7.5', '<')) {
    // query v2
    if (!empty($data['query_v2'])) {
      if (!empty($data['query_v2']['attributeRequiredSlugs']) && is_string($data['query_v2']['attributeRequiredSlugs'])) {
        $data['query_v2']['attributeRequiredSlugs'] = array($data['query_v2']['attributeRequiredSlugs']);
      }
    }

    // list separator position
    if (!empty($data['style'])) {
      foreach (['laptop', 'tablet', 'phone'] as $device) {
        if (!empty($data['style'][$device])) {
          if (!empty($data['style'][$device]['[container].wcpt-list-view'])) {
            if (!empty($data['style'][$device]['[container].wcpt-list-view']['_column-separator-position'])) {
              $data['style'][$device]['[container].wcpt-list-view']['_column-separator-position-selection'] = 'custom';
            } else {
              $data['style'][$device]['[container].wcpt-list-view']['_column-separator-position-selection'] = '';
            }
          }
        }
      }

    }
  }

  // update to 4.8.0
  if (version_compare($data['version'], '4.8.0', '<')) {

    // property list layout
    $property_list_elements = wcpt_get_col_elms_ref2(array("multi_property_grid", "property_list"), $data);

    foreach ($property_list_elements as &$element) {

      $element['type'] = 'multi_property_grid';

      // table
      if (!empty($element['table_layout'])) {
        $element['layout'] = 'table';

        // table columns
        if (empty($element['table_columns'])) {
          $element['table_columns'] = 1;
        }

        // even odd row background color
        if (
          !empty($element['style']['[id].wcpt-property-list--table-layout .wcpt-pl-inner .wcpt-pl-row:nth-child(odd)']) &&
          !empty($element['style']['[id].wcpt-property-list--table-layout .wcpt-pl-inner .wcpt-pl-row:nth-child(odd)']['background-color'])
        ) {
          $element['style']['[id].wcpt-property-list--table-layout']['--wcpt-property-list-table-odd-row-background-color'] = $element['style']['[id].wcpt-property-list--table-layout .wcpt-pl-inner .wcpt-pl-row:nth-child(odd)']['background-color'];
          unset($element['style']['[id].wcpt-property-list--table-layout .wcpt-pl-inner .wcpt-pl-row:nth-child(odd)']);
        }
        if (
          !empty($element['style']['[id].wcpt-property-list--table-layout .wcpt-pl-inner .wcpt-pl-row:nth-child(even)']) &&
          !empty($element['style']['[id].wcpt-property-list--table-layout .wcpt-pl-inner .wcpt-pl-row:nth-child(even)']['background-color'])
        ) {
          $element['style']['[id].wcpt-property-list--table-layout']['--wcpt-property-list-table-even-row-background-color'] = $element['style']['[id].wcpt-property-list--table-layout .wcpt-pl-inner .wcpt-pl-row:nth-child(even)']['background-color'];
          unset($element['style']['[id].wcpt-property-list--table-layout .wcpt-pl-inner .wcpt-pl-row:nth-child(even)']);
        }

        // column layout
      } else if (empty($element['label_above_value_enabled'])) {
        $element['layout'] = 'column';

        if (!empty($element['columns'])) {
          $element['column_columns'] = $element['columns'];
        }

        // gap between name and value
        if (
          !empty($element['style'])
          && !empty($element['style']['[id] .wcpt-property-value'])
          && !empty($element['style']['[id] .wcpt-property-value']['margin-left'])
        ) {
          $element['style']['[id].wcpt-property-list--column-layout']['--wcpt-property-list-column-gap-between-name-and-value'] = $element['style']['[id] .wcpt-property-value']['margin-left'];
          unset($element['style']['[id] .wcpt-property-value']['margin-left']);
        }

        // default: grid layout
      } else {
        $element['layout'] = 'grid';
        if (!empty($element['columns'])) {
          $element['grid_columns'] = $element['columns'];
        }

        $element['full_width'] = true;

        // name font size and weight
        if (
          empty($element['style']['[id] .wcpt-property-name'])
        ) {
          $element['style']['[id] .wcpt-property-name'] = [];
        }
        if (empty($element['style']['[id] .wcpt-property-name']['font-size'])) {
          $element['style']['[id] .wcpt-property-name']['font-size'] = '1em';
        }
        if (empty($element['style']['[id] .wcpt-property-name']['font-weight'])) {
          $element['style']['[id] .wcpt-property-name']['font-weight'] = 'normal';
        }

        // gap between name and value
        if (
          !empty($element['style']['[id] .wcpt-property-value'])
          && !empty($element['style']['[id] .wcpt-property-value']['margin-top'])
        ) {
          $element['style']['[id].wcpt-property-list--grid-layout']['--wcpt-property-list-grid-gap-between-name-and-value'] = $element['style']['[id] .wcpt-property-value']['margin-top'];
          unset($element['style']['[id] .wcpt-property-value']['margin-top']);
        }
      }

      // border and padding
      if (
        $element['layout'] !== 'table' &&
        !empty($element['style']['[id]'])
      ) {
        if (!empty($element['style']['[id]']['border-width'])) {
          $element[$element['layout'] . '_border_and_padding'] = true;
        } else if (
          !empty($element['style']['[id]']['padding-top']) ||
          !empty($element['style']['[id]']['padding-right']) ||
          !empty($element['style']['[id]']['padding-bottom']) ||
          !empty($element['style']['[id]']['padding-left'])
        ) {
          $element[$element['layout'] . '_border_and_padding'] = true;
          $element['style']['[id]']['border-width'] = '0px';
        }
      }

      // container styles (matching _property_list_common.php layout container options)
      $arr = [
        'background-color' => '--wcpt-property-list-' . $element['layout'] . '-background-color',
        'border-width' => '--wcpt-property-list-' . $element['layout'] . '-border-width',
        'border-style' => '--wcpt-property-list-' . $element['layout'] . '-border-style',
        'border-color' => '--wcpt-property-list-' . $element['layout'] . '-border-color',
        'border-radius' => '--wcpt-property-list-' . $element['layout'] . '-border-radius',
        'padding-top' => '--wcpt-property-list-' . $element['layout'] . '-padding-top',
        'padding-right' => '--wcpt-property-list-' . $element['layout'] . '-padding-right',
        'padding-bottom' => '--wcpt-property-list-' . $element['layout'] . '-padding-bottom',
        'padding-left' => '--wcpt-property-list-' . $element['layout'] . '-padding-left',
        'margin-top' => '--wcpt-property-list-' . $element['layout'] . '-gap-above',
        'margin-bottom' => '--wcpt-property-list-' . $element['layout'] . '-gap-below',
        'width' => 'width',
      ];

      // container style transfer logic
      if (!empty($element['style']['[id]'])) {
        foreach ($arr as $oldKey => $cssVar) {
          if (!empty($element['style']['[id]'][$oldKey])) {
            $element['style']['[id].wcpt-property-list--' . $element['layout'] . '-layout'][$cssVar] = $element['style']['[id]'][$oldKey];
            unset($element['style']['[id]'][$oldKey]);
          }
        }
      }

      // full width
      $element['column_full_width'] = false;
      $element['grid_full_width'] = true;
      $element['table_full_width'] = false;

      // name styles
      if (!empty($element['style']['[id] .wcpt-property-name'])) {
        $name_transform = array(
          'font-size' => '--wcpt-property-list-' . $element['layout'] . '-property-name-font-size',
          'color' => '--wcpt-property-list-' . $element['layout'] . '-property-name-text-color',
          'font-weight' => '--wcpt-property-list-' . $element['layout'] . '-property-name-font-weight',
        );
        foreach ($name_transform as $prop_name => $css_var) {
          if (!empty($element['style']['[id] .wcpt-property-name'][$prop_name])) {
            $element['style']['[id].wcpt-property-list--' . $element['layout'] . '-layout'][$css_var] = $element['style']['[id] .wcpt-property-name'][$prop_name];
          }
        }
        unset($element['style']['[id] .wcpt-property-name']);
      }

      // name - icon styles
      if (!empty($element['style']['[id] .wcpt-property-name .wcpt-icon'])) {
        $layout = $element['layout'];
        $iconStyle = $element['style']['[id] .wcpt-property-name .wcpt-icon'];
        $icon_mapping = array(
          'font-size' => '--wcpt-property-list-' . $layout . '-property-name-icon-size',
          'stroke-width' => '--wcpt-property-list-' . $layout . '-property-name-icon-stroke-thickness',
          'color' => '--wcpt-property-list-' . $layout . '-property-name-icon-color',
          'fill' => '--wcpt-property-list-' . $layout . '-property-name-icon-fill-color',
        );
        foreach ($icon_mapping as $oldKey => $css_var) {
          if (!empty($iconStyle[$oldKey])) {
            $element['style']['[id].wcpt-property-list--' . $layout . '-layout'][$css_var] = $iconStyle[$oldKey];
          }
        }
        unset($element['style']['[id] .wcpt-property-name .wcpt-icon']);
      }

      // value styles
      if (!empty($element['style']['[id] .wcpt-property-value'])) {
        $value_transform = array(
          'font-size' => '--wcpt-property-list-' . $element['layout'] . '-property-value-font-size',
          'color' => '--wcpt-property-list-' . $element['layout'] . '-property-value-text-color',
          'font-weight' => '--wcpt-property-list-' . $element['layout'] . '-property-value-font-weight',
          'background-color' => '--wcpt-property-list-' . $element['layout'] . '-property-value-background-color',
        );

        foreach ($value_transform as $prop_name => $css_var) {
          if (!empty($element['style']['[id] .wcpt-property-value'][$prop_name])) {
            $element['style']['[id].wcpt-property-list--' . $element['layout'] . '-layout'][$css_var] = $element['style']['[id] .wcpt-property-value'][$prop_name];
          }
        }

        unset($element['style']['[id] .wcpt-property-value']);
      }

      // toggle button styles
      if (!empty($element['style']['[id] .wcpt-tg-trigger'])) {

        $value_transform = array(
          'font-size' => '--wcpt-property-list-toggle-button-font-size',
          'color' => '--wcpt-property-list-toggle-button-text-color',
          'font-weight' => '--wcpt-property-list-toggle-button-font-weight',
          'margin-top' => '--wcpt-property-list-toggle-button-gap-above',
        );

        foreach ($value_transform as $prop_name => $css_var) {
          if (!empty($element['style']['[id] .wcpt-tg-trigger'][$prop_name])) {
            $element['style']['[id] .wcpt-tg-trigger'][$css_var] = $element['style']['[id] .wcpt-tg-trigger'][$prop_name];
            unset($element['style']['[id] .wcpt-tg-trigger'][$prop_name]);
          }
        }

      }

      // toggle button icon styles
      if (!empty($element['style']['[id] .wcpt-tg-trigger .wcpt-icon'])) {
        $value_transform = array(
          'font-size' => '--wcpt-property-list-toggle-button-icon-size',
          'color' => '--wcpt-property-list-toggle-button-icon-color',
          'stroke-width' => '--wcpt-property-list-toggle-button-icon-stroke-thickness',
        );
        foreach ($value_transform as $prop_name => $css_var) {
          if (!empty($element['style']['[id] .wcpt-tg-trigger .wcpt-icon'][$prop_name])) {
            $element['style']['[id] .wcpt-tg-trigger .wcpt-icon'][$css_var] = $element['style']['[id] .wcpt-tg-trigger .wcpt-icon'][$prop_name];
            unset($element['style']['[id] .wcpt-tg-trigger .wcpt-icon'][$prop_name]);
          }
        }
      }

    }
    unset($property_list_elements);
    unset($element);

    // content element
    $content_elements = wcpt_get_col_elms_ref2(array("content", "excerpt", "short_description"), $data);
    foreach ($content_elements as &$element) {
      $element['limit_by'] = false;

      if (!empty($element['limit'])) {
        $element['limit_by'] = 'words';
      }
    }
    unset($content_elements);
    unset($element);
  }

  // update to 4.9.0
  if (version_compare($data['version'], '4.9.0', '<')) {

    // navigation - pagination settings
    if (!isset($data['navigation_settings']['paginationShowPrevNextIcons'])) {
      $data['navigation_settings']['paginationShowPrevNextIcons'] = true;
    }
    if (!isset($data['navigation_settings']['paginationShowFirstLastIcons'])) {
      $data['navigation_settings']['paginationShowFirstLastIcons'] = true;
    }
    if (!isset($data['navigation_settings']['paginationShowFirstLastNumbers'])) {
      $data['navigation_settings']['paginationShowFirstLastNumbers'] = true;
    }

    // navigation - clear filters - border and padding
    $clear_filters = wcpt_get_nav_elms_ref('clear_filters', $data);
    foreach ($clear_filters as &$clear_filter) {
      if (empty($clear_filter['border_and_padding'])) {
        $clear_filter['border_and_padding'] = false;
      }
    }
    unset($clear_filters);
    unset($clear_filter);

    // keep legacy word-break behaviour on phones
    if (empty($data['style']['phone'])) {
      $data['style']['phone'] = array();
    }

    if (empty($data['style']['phone']['[container]'])) {
      $data['style']['phone']['[container]'] = array();
    }

    if (!empty($data['style']['phone']['[container]']['word-break'])) {
      $data['style']['phone']['[container]']['word-break'] = "break-word";
    }
  }

  $data['version'] = WCPT_VERSION;
  $data['timestamp'] = time();

  // update meta
  update_post_meta($data['id'], 'wcpt_data', addslashes(json_encode($data)));

  return $data;
}

// returns references for specific nav filter type(s) - supports string or array, does not consider responsive navigation
function wcpt_get_nav_elms_ref($type = false, &$data = false)
{
  if (!$data) {
    $data = wcpt_get_table_data();
  }

  $elements = array();

  if (
    !isset($data['navigation']) ||
    !isset($data['navigation']['laptop'])
  ) {
    return $elements;
  }

  // Normalize $type to array if it's provided and not already an array
  $types = false;
  if ($type !== false) {
    $types = is_array($type) ? $type : array($type);
  }

  // laptop navigation
  $laptop_navigation =& $data['navigation']['laptop'];
  $rows = array(); // single block editor row

  if (
    !empty($laptop_navigation['left_sidebar']) &&
    !empty($laptop_navigation['left_sidebar'][0])
  ) {
    $rows[] = &$laptop_navigation['left_sidebar'][0]; // single block editor row
  }

  foreach ($laptop_navigation['header']['rows'] as &$header_row) {
    foreach ($header_row['columns'] as &$column) {
      if (is_array($column['template'])) {
        $rows[] =& $column['template'][0]; // append header block editor rows
      }
    }
  }

  // responsive navigation - include phone rows
  if (isset($data['navigation']['phone']) && is_array($data['navigation']['phone'])) {
    foreach ($data['navigation']['phone'] as &$phone_row) {
      $rows[] =& $phone_row;
    }
  }

  // iterate combined rows from sidebar and header
  foreach ($rows as &$row) {
    if (!empty($row['elements'])) {
      foreach ($row['elements'] as &$element) {
        if (
          $types !== false &&
          (!isset($element['type']) || !in_array($element['type'], $types))
        ) {
          continue;
        }

        $elements[] =& $element;
      }
    }
  }

  return $elements;
}

// returns references for column elements of a type 
function wcpt_get_col_elms_ref($types, &$data)
{
  if (!$types)
    return false;

  if (!is_array($types))
    $types = array($types);

  $elements = array();
  foreach ($data['columns'] as &$device) {
    if (empty($device)) {
      continue;
    }

    foreach ($device as &$column) {
      foreach ($column['cell']['template'] as &$template_row) {
        foreach ($template_row['elements'] as &$element) {
          if (!in_array($element['type'], $types)) {
            continue;
          }

          $elements[] =& $element;
        }
      }
    }
  }

  return $elements;
}

function wcpt_get_col_elms_ref2($types, &$data)
{
  if (!$types)
    return false;

  if (!is_array($types))
    $types = array($types);

  $elements = array();

  // Recursive function to search through nested elements
  $search_elements = function (&$data, &$elements) use (&$search_elements, $types) {
    if (!is_array($data)) {
      return;
    }

    foreach ($data as $key => &$value) {
      // If this is an element with a 'type' property, check if it matches our search
      if (is_array($value) && isset($value['type']) && in_array($value['type'], $types)) {
        $elements[] =& $value;
      }

      // Recursively search through arrays and objects
      if (is_array($value)) {
        $search_elements($value, $elements);
      }
    }
  };

  // Start the recursive search from the columns data
  if (!empty($data['columns'])) {
    foreach ($data['columns'] as &$device) {
      if (empty($device)) {
        continue;
      }
      $search_elements($device, $elements);
    }
  }

  // Also search through grid > cell_template if they exist
  if (!empty($data['grid']['cell_template']) && is_array($data['grid']['cell_template'])) {
    $search_elements($data['grid']['cell_template'], $elements);
  }

  return $elements;
}

// global settings for plugin
function wcpt_update_settings_data()
{
  $data = json_decode(stripslashes(get_option('wcpt_settings', '')), true);

  // ensure version number
  // version was not stored in settings before 1.9.0
  if (empty($data['version'])) {
    $data['version'] = '1.8.0';
  }

  // skip update
  if (
    !empty($data['version']) &&
    $data['version'] === WCPT_VERSION
  ) {
    return FALSE;
  }

  // clear older global settings backup data
  wcpt_clear_older_global_settings_backup_data();

  // backup current data
  if (!get_option('wcpt_settings_' . $data['version'])) {
    update_option('wcpt_settings_' . $data['version'], addslashes(json_encode($data)), false);
  } else {
    $count = 0;

    while (++$count) {
      $name = 'wcpt_settings_' . $data['version'] . '_' . $count;
      if (!get_option($name)) {
        update_option($name, addslashes(json_encode($data)), false);
        break;
      }
    }
  }

  // update to 1.9.0
  if (version_compare($data['version'], '1.9.0', '<')) {

    // provide search settings exists
    if (empty($data['search'])) {
      $data['search'] = $GLOBALS['WCPT_SEARCH_DATA'];
    }

  }

  // update to 2.0.0
  if (version_compare($data['version'], '2.0.0', '<')) {

    // provide search settings exists (repeated from 1.9.0)
    if (empty($data['search'])) {
      $data['search'] = $GLOBALS['WCPT_SEARCH_DATA'];
    }

    // search override settings
    if (empty($data['search']['override_settings'])) {
      $data['search']['override_settings'] = array(
        'target' => array('title', 'content')
      );
    }

    // checkbox trigger
    if (empty($data['checkbox_trigger'])) {
      $data['checkbox_trigger'] = $GLOBALS['WCPT_CHECKBOX_TRIGGER_DATA'];
    }

  }

  // update to 2.3.0
  if (version_compare($data['version'], '2.3.0', '<')) {

    // ensure sessions table is regularly trimmed 
    if (!wp_get_scheduled_event('wcpt_cleanup_sessions')) {
      wp_schedule_event(time() + (6 * HOUR_IN_SECONDS), 'twicedaily', 'wcpt_cleanup_sessions');
    }

  }

  // update to 3.8.0
  if (version_compare($data['version'], '3.8.0', '<')) {

    // new license key store
    if (!isset($data['pro_license'])) {
      $data['pro_license'] = array();
    }

    $modules = array(
      array(
        "name" => "Woocommerce Product Table PRO",
        "modelKey" => "",
        "itemId" => 10,
      ),
      array(
        "name" => "Addon: Add to cart from quantity",
        "modelKey" => "wcpt-addon-add-to-cart-from-quantity",
        "itemId" => 26568,
      ),
      array(
        "name" => "Addon: Global term relabel",
        "modelKey" => "wcpt-addon-global-term-relabel",
        "itemId" => 26566,
      ),
      array(
        "name" => "Addon: Favorite products",
        "modelKey" => "wcpt-addon-favorite-products",
        "itemId" => 32594,
      ),
      array(
        "name" => "Addon: Grid table view switcher",
        "modelKey" => "wcpt-addon-grid-table-view-switcher",
        "itemId" => 30572,
      )
    );

    $modules = array_map(function ($module) use ($data) {
      if (empty($module['modelKey'])) {
        $pro_license = !empty($data['pro_license']) ? $data['pro_license'] : array();
        $module['licenseKey'] = empty($pro_license['key']) ? "" : $pro_license['key'];
        $module['status'] = empty($pro_license['status']) ? "" : $pro_license['status'];
      } else {

        if (
          !empty($data['pro_license']['addon']) &&
          !empty($data['pro_license']['addon'][$module['modelKey']])
        ) {
          $addon_license = $data['pro_license']['addon'][$module['modelKey']];

          $module['licenseKey'] = empty($addon_license['key']) ? "" : $addon_license['key'];
          $module['status'] = empty($addon_license['status']) ? "" : $addon_license['status'];
        }

      }

      unset($module['modelKey']);

      return $module;

    }, $modules);

    $data['pro_license_v2'] = $modules;

  }

  // update to 4.8.0
  if (version_compare($data['version'], '4.8.0', '<')) {
    // Fix property list conversion logic
    $theme = get_option('wcpt_theme_customizer');
    if (!empty($theme) && is_array($theme)) {
      $transform = array(
        'wcpt_property_name_margin_bottom' => array('wcpt_property_list_grid_gap_between_name_and_value'),
        'wcpt_property_cell_margin_vertical' => array(
          'wcpt_property_list_grid_gap_between_rows',
          'wcpt_property_list_column_gap_between_rows'
        ),
        'wcpt_property_name_font_weight' => array(
          'wcpt_property_list_table_property_name_font_weight',
          'wcpt_property_list_grid_property_name_font_weight',
          'wcpt_property_list_column_property_name_font_weight'
        ),
        'wcpt_property_name_font_size' => array(
          'wcpt_property_list_table_property_name_font_size',
          'wcpt_property_list_grid_property_name_font_size',
          'wcpt_property_list_column_property_name_font_size'
        ),
        'wcpt_property_name_text_color' => array(
          'wcpt_property_list_table_property_name_text_color',
          'wcpt_property_list_grid_property_name_text_color',
          'wcpt_property_list_column_property_name_text_color'
        ),
        'wcpt_property_value_font_weight' => array(
          'wcpt_property_list_table_property_value_font_weight',
          'wcpt_property_list_grid_property_value_font_weight',
          'wcpt_property_list_column_property_value_font_weight'
        ),
        'wcpt_property_value_font_size' => array(
          'wcpt_property_list_table_property_value_font_size',
          'wcpt_property_list_grid_property_value_font_size',
          'wcpt_property_list_column_property_value_font_size'
        ),
        'wcpt_property_value_text_color' => array(
          'wcpt_property_list_table_property_value_text_color',
          'wcpt_property_list_grid_property_value_text_color',
          'wcpt_property_list_column_property_value_text_color'
        ),
      );

      foreach ($transform as $oldKey => $newKeys) {
        if (!empty($theme[$oldKey])) {
          if (!is_array($newKeys)) {
            $newKeys = array($newKeys);
          }
          foreach ($newKeys as $newKey) {
            $theme[$newKey] = $theme[$oldKey];
          }
          unset($theme[$oldKey]);
        }
      }
      update_option('wcpt_theme_customizer', $theme, false);
    }
  }

  $data['version'] = WCPT_VERSION;
  $data['timestamp'] = time();

  // update meta
  update_option('wcpt_settings', addslashes(json_encode($data)), false);

  return $data;
}

/**
 * Clear older global settings backup data, keeping only the two most recent backups.
 */
function wcpt_clear_older_global_settings_backup_data()
{
  global $wpdb;
  $sql = $wpdb->prepare(
    "SELECT option_name FROM {$wpdb->options} WHERE option_name LIKE %s",
    'wcpt_settings_%'
  );
  $existing_options = $wpdb->get_col($sql);

  // Keep the last two options, delete the rest
  $count = count($existing_options);
  if ($count > 2) {
    $to_delete = array_slice($existing_options, 0, $count - 2);
    foreach ($to_delete as $option_name) {
      delete_option($option_name);
    }
  }
}

/**
 * Clear older table backup data, keeping only the two most recent backups.
 *
 * @param int $post_id The post ID for which to clean up meta backups.
 */
function wcpt_clear_older_table_settings_backup_data($post_id)
{
  global $wpdb;

  // Get all backup meta keys for this table, ordered by creation (oldest first)
  $meta_keys = $wpdb->get_col(
    $wpdb->prepare(
      "SELECT meta_key FROM {$wpdb->postmeta} WHERE post_id = %d AND meta_key LIKE %s ORDER BY meta_id ASC",
      $post_id,
      $wpdb->esc_like('wcpt_data_') . '%'
    )
  );

  // If more than two backups exist, remove the oldest, keep the last two most recent
  if (count($meta_keys) > 2) {
    $keys_to_delete = array_slice($meta_keys, 0, count($meta_keys) - 2);
    foreach ($keys_to_delete as $meta_key) {
      delete_post_meta($post_id, $meta_key);
    }
  }
}
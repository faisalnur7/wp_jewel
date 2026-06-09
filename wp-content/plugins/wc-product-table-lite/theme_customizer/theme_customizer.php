<?php
/**
 * Generate CSS mapping for customizer.
 * 
 * This function creates a mapping between customizer setting IDs and their corresponding CSS selectors.
 * The mapping is cached in a static variable to avoid recalculating on every call.
 * 
 * The mapping is used to:
 * 1. Link customizer controls to CSS properties
 * 2. Generate live preview CSS when customizer values change
 * 3. Apply saved customizer values on the frontend
 *
 * @return array Associative array of setting IDs => CSS selectors
 */
function wcpt_customizer_mapping()
{
  // Use static variable to cache the mapping
  static $cached_mapping = null;

  // Return cached mapping if available
  if ($cached_mapping !== null) {
    return $cached_mapping;
  }

  $mapping = array();

  // Get all sections and their variables
  $sections = wcpt_get_css_variables();

  foreach ($sections as $section) {
    // Add main section variables
    foreach ($section['variables'] as $var_name => $variable) {
      $setting_id = str_replace('-', '_', $var_name);
      $mapping[$setting_id] = wcpt_get_css_selector($var_name);
    }

    // Add subsection variables 
    foreach ($section['sections'] as $subsection) {
      foreach ($subsection['variables'] as $var_name => $variable) {
        $setting_id = str_replace('-', '_', $var_name);
        $mapping[$setting_id] = wcpt_get_css_selector($var_name);
      }
    }
  }

  // Cache the mapping
  $cached_mapping = $mapping;

  return $mapping;
}

// Add custom CSS for customizer when viewing product table
add_action('customize_controls_print_styles', 'wcpt_customizer_styles');
function wcpt_customizer_styles()
{
  ?>
  <style>
    .wp-customizer>.preview-desktop .wp-full-overlay-main {
      min-width: 1300px !important;
    }

    .wp-customizer>.preview-desktop {
      overflow-y: scroll !important;
    }

    .wp-customizer>.preview-tablet .wp-full-overlay-main {
      min-width: 850px !important;
      min-height: 1100px !important;
      margin: 0;
      transform: translateX(-50%);
    }

    .wp-customizer>.preview-mobile .wp-full-overlay-main {
      min-width: 350px !important;
      min-height: 650px !important;
    }
  </style>
  <?php
}

/**
 * Helper function to determine setting type
 */
function wcpt_get_setting_type($var_name)
{

  if (preg_match('/(font-weight|fontweight|text-transform|border-style|vertical-align|text-align)/i', $var_name)) {
    return 'select';
  }

  if (preg_match('/(background|colour|color)/i', $var_name)) {
    return 'spectrum-color';
  }

  if (preg_match('/(size|width|height|padding|margin|gap|spacing|radius|border|thickness|shadow|offset|top|left|right|bottom)/i', $var_name)) {
    return 'number-text';
  }

  return 'text';
}

/**
 * Helper function to get select choices
 */
function wcpt_get_select_choices($var_name)
{
  $choices = array();

  // Check if this is a font-weight property
  if (preg_match('/(font-weight|fontweight)/i', $var_name)) {
    $choices = array(
      '' => __('Auto', 'wc-product-table-pro'),
      'normal' => __('Normal', 'wc-product-table-pro'),
      'bold' => __('Bold', 'wc-product-table-pro'),
      'lighter' => __('Lighter', 'wc-product-table-pro')
    );
    return $choices;
  }
  // Check if this is a border-style property
  if (preg_match('/(border-style)/i', $var_name)) {
    $choices = array(
      '' => __('Auto', 'wc-product-table-pro'),
      'solid' => __('Solid', 'wc-product-table-pro'),
      'dashed' => __('Dashed', 'wc-product-table-pro'),
      'dotted' => __('Dotted', 'wc-product-table-pro'),
    );
    return $choices;
  }
  // Check if this is a text-transform property
  if (preg_match('/(text-transform)/i', $var_name)) {
    $choices = array(
      '' => __('Auto', 'wc-product-table-pro'),
      'none' => __('None', 'wc-product-table-pro'),
      'uppercase' => __('Uppercase', 'wc-product-table-pro'),
      'capitalize' => __('Capitalize', 'wc-product-table-pro'),
      'lowercase' => __('Lowercase', 'wc-product-table-pro')
    );
    return $choices;
  }

  // Check if this is a vertical alignment property
  if (preg_match('/(vertical-align)/i', $var_name)) {
    $choices = array(
      '' => __('Auto', 'wc-product-table-pro'),
      'top' => __('Top', 'wc-product-table-pro'),
      'middle' => __('Middle', 'wc-product-table-pro'),
      'bottom' => __('Bottom', 'wc-product-table-pro')
    );
    return $choices;
  }

  // Check if this is a text alignment property
  if (preg_match('/(text-align)/i', $var_name)) {
    $choices = array(
      '' => __('Auto', 'wc-product-table-pro'),
      'left' => __('Left', 'wc-product-table-pro'),
      'center' => __('Center', 'wc-product-table-pro'),
      'right' => __('Right', 'wc-product-table-pro')
    );
    return $choices;
  }

  // Handle other select types
  switch ($var_name) {
    case 'normal':
    case 'bold':
    case 'italic':
    case 'underline':
    case 'none':
      $choices = array(
        'normal' => __('Normal', 'wc-product-table-pro'),
        'bold' => __('Bold', 'wc-product-table-pro'),
        'italic' => __('Italic', 'wc-product-table-pro'),
        'underline' => __('Underline', 'wc-product-table-pro'),
        'none' => __('None', 'wc-product-table-pro')
      );
      break;
  }
  return $choices;
}

/**
 * Helper function to generate CSS selector
 */
function wcpt_get_css_selector($var_name)
{
  $selector = ':root {';
  $selector .= '--' . $var_name . ': %val%;';
  $selector .= '}';
  return $selector;
}

/**
 * Gets CSS variables and their sections from the CSS file
 * 
 * @return array Array containing sections and their variables
 */
function wcpt_get_css_variables()
{
  $css_file = WCPT_PLUGIN_PATH . 'assets/css.css';

  // Check if file exists
  if (!file_exists($css_file)) {
    error_log('WCPT: CSS file not found at ' . $css_file);
    return array();
  }

  // Try to read file contents
  $css_content = @file_get_contents($css_file);
  if ($css_content === false) {
    error_log('WCPT: Failed to read CSS file at ' . $css_file);
    return array();
  }

  // Extract the :root block
  preg_match('/:root\s*{([^}]*)}/s', $css_content, $matches);
  if (empty($matches[1])) {
    error_log('WCPT: No :root block found in CSS file');
    return array();
  }

  // Remove phone comments, @parseignore comments, and undefined selector comments
  $root_content = preg_replace('/\/\*\s*for phone\s*\*\//', '', $matches[1]);
  $root_content = preg_replace('/\/\*\s*@parseignore\s+.*?\*\//', '', $root_content);
  $root_content = preg_replace('/\/\*\s*undefined selector\s*\*\//', '', $root_content);

  // First, find and remove any variables that have /* skip */ at the end
  $root_content = preg_replace('/--([^:]+):\s*([^;]*?)\s*\/\*\s*skip\s*\*\/\s*;/s', '', $root_content);

  // Parse sections and their variables
  $sections = array();
  $current_section = '';
  $current_subsection = '';

  // Split content by comments
  $parts = preg_split('/(\/\*.*?\*\/)/s', $root_content, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

  foreach ($parts as $part) {
    // Check if this is a section comment (no hyphen at start)
    if (preg_match('/\/\*\s*([^-][^*]+)\s*\*\//', $part, $section_matches)) {
      $current_section = trim($section_matches[1]);
      $sections[$current_section] = array(
        'title' => wcpt_customizer_get_label($current_section),
        'sections' => array(),
        'variables' => array()
      );
      $current_subsection = '';
    }
    // Check if this is a subsection comment (has hyphen at start)
    elseif (preg_match('/\/\*\s*-\s*([^*]+)\s*\*\//', $part, $subsection_matches)) {
      $current_subsection = trim($subsection_matches[1]);
      if ($current_section) {
        $sections[$current_section]['sections'][$current_subsection] = array(
          'title' => wcpt_customizer_get_label($current_subsection),
          'variables' => array()
        );
      }
    }
    // Parse variables
    elseif (preg_match_all('/--([^:]+):\s*([^;]+);/', $part, $var_matches, PREG_SET_ORDER)) {
      foreach ($var_matches as $var_match) {
        $var_name = trim($var_match[1]);
        $var_value = trim($var_match[2]);

        // Check if the value references another CSS variable
        if (preg_match('/var\s*\(\s*--[^)]+\s*\)/', $var_value)) {
          $var_value = ''; // Set to empty if it references another variable
        }

        $variable = array(
          'name' => $var_name,
          'value' => $var_value
        );

        // If we're in a subsection, add to subsection variables
        if ($current_subsection && isset($sections[$current_section]['sections'][$current_subsection])) {
          $sections[$current_section]['sections'][$current_subsection]['variables'][$var_name] = $variable;
        }
        // If we're in a section but not in a subsection, add to section variables
        elseif ($current_section) {
          $sections[$current_section]['variables'][$var_name] = $variable;
        }
      }
    }
  }

  return $sections;
}

/**
 * Converts a CSS variable name or section name into a readable label
 * 
 * @param string $string The variable or section name to convert
 * @return string The formatted label with spaces and proper capitalization
 */
function wcpt_customizer_get_label($string, $title = false)
{
  // Remove wcpt prefix if it exists
  $string = preg_replace('/^wcpt[-_]/', '', $string);

  // Remove any leading hyphens
  $string = ltrim($string, '-');

  // Replace hyphens with spaces and capitalize first letter of each word
  $string = ucwords(str_replace('-', ' ', $string));

  if ($title) {

    if ($title === 'Property Row Layout') {
      $title = 'Property List Row';
    }
    if ($title === 'Property Grid Layout') {
      $title = 'Property List Grid';
    }
    if ($title === 'Property Column Layout') {
      $title = 'Property List Column';
    }
    if ($title === 'Property Justified Layout') {
      $title = 'Property List Justified';
    }
    if ($title === 'Property Table Layout') {
      $title = 'Property List Table';
    }
    if ($title === 'Property Bar Layout') {
      $title = 'Property List Bar';
    }

    // Remove the value of $title from $string only if it's at the start
    if (stripos($string, $title) === 0) {
      $string = ltrim(substr($string, strlen($title)));
    }
  }

  return $string;
}

/**
 * Transforms CSS variables into WordPress customizer settings format
 * 
 * @param array $sections Array of sections with their variables
 * @return array WordPress customizer settings format
 */
function wcpt_transform_css_to_customizer_settings($sections)
{
  $settings = array(
    'panels' => array(
      array(
        'id' => 'wcpt_panel',
        'title' => 'Product Table Theme',
        'description' => 'Customize the base style theme used by product tables. Changes will apply globally across all product tables except where a table has its own style settings.',
        'priority' => 160
      )
    ),
    'sections' => array(),
    'settings' => array()
  );

  $section_priority = 161;

  // Process each section
  foreach ($sections as $section_id => $section) {
    $section_id = sanitize_title($section_id);

    // Add main section
    $settings['sections'][] = array(
      'id' => $section_id,
      'title' => wcpt_customizer_get_label($section['title']),
      'panel' => 'wcpt_panel',
      'priority' => $section_priority++
    );

    // Process main section variables
    foreach ($section['variables'] as $var_name => $variable) {
      $setting_type = wcpt_get_setting_type($var_name);

      $setting = array(
        'id' => str_replace('-', '_', $var_name),
        'default' => in_array($variable['value'], array('*', 'inherit')) ? "" : $variable['value'],
        'transport' => 'postMessage',
        'css' => wcpt_get_css_selector($var_name),
        'section' => $section_id,
        'label' => wcpt_customizer_get_label($var_name, wcpt_customizer_get_label($section['title'])),
        'type' => $setting_type
      );

      if ($setting_type === 'select') {
        $setting['choices'] = wcpt_get_select_choices($var_name);
      }

      if (in_array($var_name, array('font-size', 'padding', 'margin', 'width', 'height'))) {
        $setting['phone_variant'] = true;
      }

      $settings['settings'][] = $setting;
    }
  }

  return $settings;
}

/**
 * Registers the customizer settings
 * 
 * @param WP_Customize_Manager $wp_customize WordPress customizer manager instance
 */
// Register customizer settings
add_action('customize_register', 'wcpt_register_customizer_settings');
function wcpt_register_customizer_settings($wp_customize)
{
  // Get CSS variables and sections from your CSS file
  $sections = wcpt_get_css_variables();

  // Transform CSS variables to customizer settings
  $customizer_settings = wcpt_transform_css_to_customizer_settings($sections);

  // Register panels
  foreach ($customizer_settings['panels'] as $panel) {
    $wp_customize->add_panel($panel['id'], $panel);
  }

  // Register sections
  foreach ($customizer_settings['sections'] as $section) {
    $wp_customize->add_section($section['id'], $section);
  }

  // Register settings and controls
  foreach ($customizer_settings['settings'] as $setting) {
    // Remove duplicate wcpt_ prefix from the setting ID
    $setting_id = $setting['id'];

    // Add the setting with option type
    $wp_customize->add_setting('wcpt_theme_customizer[' . $setting_id . ']', array(
      'type' => 'option',
      'capability' => 'manage_options',
      'default' => $setting['default'],
      'transport' => 'postMessage',
      'sanitize_callback' => $setting['type'] === 'spectrum-color' ? 'wcpt_sanitize_color' : 'sanitize_text_field'
    ));

    // Add the control based on type
    if ($setting['type'] === 'spectrum-color') {
      $wp_customize->add_control($setting_id, array(
        'type' => 'spectrum-color',
        'label' => $setting['label'],
        'section' => $setting['section'],
        'settings' => 'wcpt_theme_customizer[' . $setting_id . ']',
        'input_attrs' => array(
          'class' => 'wcpt-customizer-color-picker xxx '
        )
      ));
    } elseif ($setting['type'] === 'number-text') {
      $wp_customize->add_control($setting_id, array(
        'type' => 'text',
        'label' => $setting['label'],
        'section' => $setting['section'],
        'settings' => 'wcpt_theme_customizer[' . $setting_id . ']',
        'input_attrs' => array(
          'class' => 'wcpt-number-text',
          'pattern' => '^-?\d*\.?\d+(px|em|rem|%|vh|vw)?$',
          'step' => '1',
          'min' => '0'
        )
      ));
    } elseif ($setting['type'] === 'select') {
      $wp_customize->add_control($setting_id, array(
        'type' => 'select',
        'label' => $setting['label'],
        'section' => $setting['section'],
        'settings' => 'wcpt_theme_customizer[' . $setting_id . ']',
        'choices' => $setting['choices']
      ));
    } else {
      $wp_customize->add_control($setting_id, array(
        'type' => 'text',
        'label' => $setting['label'],
        'section' => $setting['section'],
        'settings' => 'wcpt_theme_customizer[' . $setting_id . ']'
      ));
    }
  }
}

// Enqueue customizer scripts and styles
add_action('customize_controls_enqueue_scripts', 'wcpt_customizer_enqueue');
function wcpt_customizer_enqueue()
{
  // Enqueue customizer styles
  wp_enqueue_style(
    'wcpt-customizer',
    WCPT_PLUGIN_URL . 'theme_customizer/css/customizer.css',
    array(),
    WCPT_VERSION
  );

  // Enqueue color picker style
  wp_enqueue_style(
    'wcpt-spectrum',
    WCPT_PLUGIN_URL . 'editor/assets/css/spectrum.min.css',
    array(),
    '1.8.1'
  );

  // Enqueue color picker scripts
  wp_enqueue_script(
    'wcpt-spectrum',
    WCPT_PLUGIN_URL . 'editor/assets/js/spectrum.min.js',
    array('jquery'),
    '1.8.1',
    true
  );

  // Enqueue customizer control modifications
  wp_enqueue_script(
    'wcpt-customizer-control-mod',
    WCPT_PLUGIN_URL . 'theme_customizer/js/customizer-control-mod.js',
    array('jquery', 'wcpt-spectrum', 'customize-controls'),
    WCPT_VERSION,
    true
  );
}

/**
 * Vars with no initial selectors for theme customizer.
 * 
 * These CSS custom properties (variables) are not allowed to affect anything unless they are explicitly set by the user.
 * Their corresponding selectors are added in on the page only if the css vars have been assigned values.
 * This is done to keep inheritance of default values from the theme.
 * Mostyle it's just for links which should inherit the stylesheet text color unless user has set a custom color.
 * 
 * @see wcpt_customizer_mapping() For the complete mapping of variables to selectors
 */
$wcpt_customizer_selector_relations = array(
  // title color (could be link)
  '--wcpt-title-color' => 'body table.wcpt-table .wcpt-title { color: var(--wcpt-title-color); }',
  // term text color (could be link)
  '--wcpt-term-text-color' => '.wcpt-category, .wcpt-attribute, .wcpt-brand, .wcpt-tag, .wcpt-taxonomy, .wcpt-term-separator, .wcpt-category:hover, .wcpt-attribute:hover, .wcpt-brand:hover, .wcpt-tag:hover, .wcpt-taxonomy:hover { color: var(--wcpt-term-text-color); }',
  '--wcpt-category-term-text-color' => '.wcpt .wcpt-category, .wcpt .wcpt-category + .wcpt-term-separator, .wcpt .wcpt-category:hover { color: var(--wcpt-category-term-text-color); }',
  '--wcpt-attribute-term-text-color' => '.wcpt .wcpt-attribute, .wcpt .wcpt-attribute + .wcpt-term-separator, .wcpt .wcpt-attribute:hover { color: var(--wcpt-attribute-term-text-color); }',
  '--wcpt-brand-term-text-color' => '.wcpt .wcpt-brand, .wcpt .wcpt-brand + .wcpt-term-separator, .wcpt .wcpt-brand:hover { color: var(--wcpt-brand-term-text-color); }',
  '--wcpt-tag-term-text-color' => '.wcpt .wcpt-tag, .wcpt .wcpt-tag + .wcpt-term-separator, .wcpt .wcpt-tag:hover { color: var(--wcpt-tag-term-text-color); }',
  // controls
  '--wcpt-input-background-color' => '.wcpt :is(input:not([type="radio"]):not([type="checkbox"]), select, textarea), .wcpt-modal :is(input:not([type="radio"]):not([type="checkbox"]), select, textarea) { background-color: var(--wcpt-input-background-color); }',
  '--wcpt-input-text-color' => '.wcpt :is(input:not([type="radio"]):not([type="checkbox"]), select, textarea), .wcpt-modal :is(input:not([type="radio"]):not([type="checkbox"]), select, textarea) { color: var(--wcpt-input-text-color); }',
  '--wcpt-input-border-color' => '.wcpt :is(input:not([type="radio"]):not([type="checkbox"]), select, textarea), .wcpt-modal :is(input:not([type="radio"]):not([type="checkbox"]), select, textarea) { border-color: var(--wcpt-input-border-color); }',
  '--wcpt-input-focus-background-color' => '.wcpt :is(input:not([type="radio"]):not([type="checkbox"])):is(:focus, :focus-visible), .wcpt-modal :is(input:not([type="radio"]):not([type="checkbox"])):is(:focus, :focus-visible) { background-color: var(--wcpt-input-focus-background-color); }',
  '--wcpt-input-focus-border-color' => '.wcpt :is(input:not([type="radio"]):not([type="checkbox"])):is(:focus, :focus-visible), .wcpt-modal :is(input:not([type="radio"]):not([type="checkbox"])):is(:focus, :focus-visible) { border-color: var(--wcpt-input-focus-border-color); }',
  '--wcpt-input-border-width' => '.wcpt :is(input:not([type="radio"]):not([type="checkbox"]), select, textarea), .wcpt-modal :is(input:not([type="radio"]):not([type="checkbox"]), select, textarea) { border-width: var(--wcpt-input-border-width); }',
  '--wcpt-input-border-style' => '.wcpt :is(input:not([type="radio"]):not([type="checkbox"]), select, textarea), .wcpt-modal :is(input:not([type="radio"]):not([type="checkbox"]), select, textarea) { border-style: var(--wcpt-input-border-style); }',
  '--wcpt-input-border-radius' => '.wcpt :is(input:not([type="radio"]):not([type="checkbox"]), select, textarea), .wcpt-modal :is(input:not([type="radio"]):not([type="checkbox"]), select, textarea) { border-radius: var(--wcpt-input-border-radius); }',
  '--wcpt-radio-button-color' => '.wcpt input[type="radio"], .wcpt-modal input[type="radio"] { accent-color: var(--wcpt-radio-button-color); }',
  '--wcpt-checkbox-color' => '.wcpt input[type="checkbox"], .wcpt-modal input[type="checkbox"] { accent-color: var(--wcpt-checkbox-color); }',
  '--wcpt-radio-button-size' => '.wcpt input[type="radio"], .wcpt-modal input[type="radio"] { width: var(--wcpt-radio-button-size); height: var(--wcpt-radio-button-size); }',
  '--wcpt-checkbox-size' => '.wcpt input[type="checkbox"], .wcpt-modal input[type="checkbox"] { width: var(--wcpt-checkbox-size); height: var(--wcpt-checkbox-size); }',
  // table cell vertical alignment
  '--wcpt-table-cell-vertical-alignment' => 'th.wcpt-heading, td.wcpt-cell { vertical-align: var(--wcpt-table-cell-vertical-alignment); }',
  '--wcpt-phone-table-cell-vertical-alignment' => '@media (max-width: 749px) { th.wcpt-heading, td.wcpt-cell { vertical-align: var(--wcpt-phone-table-cell-vertical-alignment); } }',
);

// Enqueue preview scripts
add_action('customize_preview_init', 'wcpt_customize_preview_js');
function wcpt_customize_preview_js()
{
  // Enqueue preview script
  wp_enqueue_script(
    'wcpt-customizer-preview',
    WCPT_PLUGIN_URL . 'theme_customizer/js/customizer-preview.js',
    array('jquery', 'customize-preview', 'customize-selective-refresh'),
    WCPT_VERSION
  );

  // Enqueue default blank values data
  global $wcpt_customizer_selector_relations;
  wp_localize_script('wcpt-customizer-preview', 'wcpt_selector_relations', $wcpt_customizer_selector_relations);

  // Get the mapping
  $mapping = wcpt_customizer_mapping();
  if (empty($mapping)) {
    error_log('WCPT: No customizer mapping generated');
    return;
  }

  // Localize the script with customizer settings
  wp_localize_script(
    'wcpt-customizer-preview',
    'wcpt_customizer_mapping',
    $mapping
  );
}

// apply saved custom values to the theme
add_action('wp_enqueue_scripts', 'wcpt_apply_custom_values_to_theme');
function wcpt_apply_custom_values_to_theme()
{
  $custom_values = get_option('wcpt_theme_customizer');
  if (!empty($custom_values)) {
    foreach ($custom_values as $key => $value) {
      if ($value) {
        // Get the CSS selector from mapping
        $mapping = wcpt_customizer_mapping();
        $selector = isset($mapping[$key]) ? $mapping[$key] : null;

        if ($selector) {
          // Replace %val% with the actual value
          $css = str_replace('%val%', $value, $selector);

          // Add the style with consistent ID format
          $style_id = 'wcpt-customizer-' . $key;
          wcpt_add_inline_customer_style($css, $style_id);

          // Handle default blank vars
          global $wcpt_customizer_selector_relations;
          foreach ($wcpt_customizer_selector_relations as $blank_key => $blank_css) {
            if (strpos($selector, $blank_key) !== false) {
              wcpt_add_inline_customer_style($blank_css, $style_id . '-blank');
            }
          }
        }
      }
    }
  }
}

/**
 * Add inline style to the page. If customizer is on then we need to give the style tag a unique id so it can be removed on the frontend by the customizer script if user resets value to default.
 * 
 * @param string $css The CSS to add.
 * @param string $style_id The ID of the style.
 */
function wcpt_add_inline_customer_style($css, $style_id)
{
  if (is_customize_preview()) {
    add_action('wp_footer', function () use ($css, $style_id) {
      echo '<style id="' . esc_attr($style_id) . '">' . $css . '</style>';
    });
  } else {
    wp_add_inline_style('wcpt', $css);
  }
}

// Add theme reset functionality
function wcpt_reset_theme_settings()
{
  if (!current_user_can('edit_theme_options')) {
    wp_send_json_error('Insufficient permissions');
  }

  // Verify nonce
  if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'wcpt_theme_reset_nonce')) {
    wp_send_json_error('Invalid nonce');
  }

  // Remove all mods for theme customizer settings
  update_option('wcpt_theme_customizer', array());

  wp_send_json_success('Theme settings cleared successfully');
}
add_action('wp_ajax_wcpt_reset_theme_settings', 'wcpt_reset_theme_settings');

// Add AJAX localization
function wcpt_localize_theme_reset_script()
{
  wp_localize_script('wcpt-controller', 'wcpt_theme_reset', array(
    'ajax_url' => admin_url('admin-ajax.php'),
    'nonce' => wp_create_nonce('wcpt_theme_reset_nonce')
  ));
}
add_action('admin_enqueue_scripts', 'wcpt_localize_theme_reset_script');

/**
 * Sanitize color values (hex and rgba)
 */
function wcpt_sanitize_color($color)
{
  // Remove any whitespace
  $color = trim($color);

  // Check if it's an rgba color
  if (strpos($color, 'rgba') === 0) {
    // Extract rgba values
    preg_match('/rgba\(\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*,\s*([\d.]+)\s*\)/', $color, $matches);
    if (count($matches) === 5) {
      $r = min(255, max(0, intval($matches[1])));
      $g = min(255, max(0, intval($matches[2])));
      $b = min(255, max(0, intval($matches[3])));
      $a = min(1, max(0, floatval($matches[4])));
      return "rgba($r, $g, $b, $a)";
    }
  }

  // Check if it's an rgb color
  if (strpos($color, 'rgb') === 0) {
    // Extract rgb values
    preg_match('/rgb\(\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*\)/', $color, $matches);
    if (count($matches) === 4) {
      $r = min(255, max(0, intval($matches[1])));
      $g = min(255, max(0, intval($matches[2])));
      $b = min(255, max(0, intval($matches[3])));
      return "rgb($r, $g, $b)";
    }
  }

  // Check if it's a hex color
  if (preg_match('/^#([A-Fa-f0-9]{3}){1,2}$/', $color)) {
    return $color;
  }

  // Check if it's a named color
  $named_colors = array(
    'black',
    'white',
    'red',
    'green',
    'blue',
    'yellow',
    'purple',
    'orange',
    'gray',
    'grey',
    'pink',
    'brown',
    'cyan',
    'magenta',
    'transparent'
  );
  if (in_array(strtolower($color), $named_colors)) {
    return $color;
  }

  // If not valid, return empty
  error_log('WCPT: Invalid color value: ' . $color);
  return '';
}

// Display theme customizer notice
add_filter('the_content', 'wcpt_display_theme_customizer_notice');
function wcpt_display_theme_customizer_notice($content)
{
  // Only run on product table post type
  if (
    get_post_type() === 'wc_product_table' &&
    is_customize_preview()
  ) {
    ?>
    <div class="wcpt-theme-customizer-notice" style="border: 1px solid rgb(238 238 238);
    padding: 10px 15px;
    border-radius: 6px;
    margin-bottom: 40px;
    background: #f7f7f7;">
      <p style="padding: 0; margin: 0;">
        <strong>Note:</strong> The theme customizer applies global styles to all product tables, making it easy to ensure a
        consistent look across your site. However, if you've already set styles for specific elements within the table
        editor, those local styles will override the theme customizer settings.

        <span class="wcpt-read-more-toggle" style="cursor: pointer">+ Show more</span>
        <span class="wcpt-extra-text" style="display: none;">
          Theme customizer settings are intended to provide default styles. If a particular style option in the theme
          customizer does not seem to have an effect, it’s likely because that element already has a custom style set
          through the table editor. To allow the theme customizer to style that element, simply remove its corresponding
          style from the table editor.

          <br><br>
          <strong>Example:</strong> If you've changed the product title color in the table editor, the color you set there
          will be used, and the theme customizer’s product title color will have no effect. To use the theme customizer
          color instead, remove the color from the table editor for the product title. The theme customizer will then take
          over.
          <span class="wcpt-hide-text" style="cursor: pointer;">- Show less</span>
        </span>
      </p>
    </div>
    <script>
      jQuery(document).ready(function ($) {
        $('.wcpt-read-more-toggle').click(function () {
          $(this).hide();
          $('.wcpt-extra-text').show();
        });

        $('.wcpt-hide-text').click(function () {
          $('.wcpt-extra-text').hide();
          $('.wcpt-read-more-toggle').show();
        });
      });
    </script>
    <?php
  }
  return $content;
}
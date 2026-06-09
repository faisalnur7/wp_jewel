<?php
// Process form submission early
add_action('template_redirect', 'wcpt_process_preview_form');
function wcpt_process_preview_form()
{
  global $post;

  if (
    !isset($_POST['wcpt_preview_template_submit']) ||
    !$post ||
    $post->post_type !== 'wc_product_table'
  ) {
    return;
  }

  if (
    !isset($_POST['wcpt_preview_form_nonce']) ||
    !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['wcpt_preview_form_nonce'])), 'wcpt_preview_form')
  ) {
    return;
  }

  if (!current_user_can('edit_post', $post->ID)) {
    return;
  }

  // Save shortcode if provided, otherwise use default
  $shortcode = !empty($_POST['wcpt_preview_template_shortcode']) ?
    sanitize_text_field($_POST['wcpt_preview_template_shortcode']) :
    '[product_table id="' . $post->ID . '"]';
  update_post_meta($post->ID, 'wcpt_preview_template_shortcode', $shortcode);

  // Save template option
  if (isset($_POST['wcpt_preview_template'])) {
    update_option('wcpt_preview_template', sanitize_text_field($_POST['wcpt_preview_template']));
  }

  // Save max width if template is product table preview
  if (isset($_POST['wcpt_preview_template_max_width']) && $_POST['wcpt_preview_template'] === 'product_table_preview') {
    update_option('wcpt_preview_template_max_width', sanitize_text_field($_POST['wcpt_preview_template_max_width']));
  }


  // Redirect to prevent form resubmission
  wp_redirect(get_permalink($post->ID));
  exit;
}

// Register template for product table post type
add_filter('single_template', 'wcpt_load_product_table_preview_template');
function wcpt_load_product_table_preview_template($template)
{
  global $post;

  if ($post->post_type === 'wc_product_table') {
    // Only load our custom template if preview template is selected
    $template_option = get_option('wcpt_preview_template', 'product_table_preview');

    if ($template_option === 'product_table_preview') {
      $custom_template = plugin_dir_path(__FILE__) . 'templates/single-wc_product_table.php';
      if (file_exists($custom_template)) {
        return $custom_template;
      }
    } else {
      // If default template is selected, return the default template
      return $template;
    }
  }

  return $template;
}

// Add template redirect hook
add_action('template_redirect', 'wcpt_handle_template_redirect');
function wcpt_handle_template_redirect()
{
  if (is_singular('wc_product_table')) {
    $template_option = get_option('wcpt_preview_template', 'product_table_preview');
    if ($template_option === 'product_table_preview') {
      $custom_template = plugin_dir_path(__FILE__) . 'templates/single-wc_product_table.php';
      if (file_exists($custom_template)) {
        include($custom_template);
        exit;
      }
    }
  }
}

// Enqueue scripts and styles
add_action('wp_enqueue_scripts', 'wcpt_enqueue_preview_form_assets');
function wcpt_enqueue_preview_form_assets()
{
  // Only enqueue on product table post type (preview UI is for editors only)
  if (
    get_post_type() === 'wc_product_table' &&
    current_user_can('edit_post', get_the_ID())
  ) {
    // Enqueue CSS
    wp_enqueue_style(
      'wcpt-preview-form',
      plugins_url('css/preview-form.css', __FILE__),
      array(),
      filemtime(plugin_dir_path(__FILE__) . 'css/preview-form.css')
    );

    // Enqueue JavaScript
    wp_enqueue_script(
      'wcpt-preview-form',
      plugins_url('js/preview-form.js', __FILE__),
      array('jquery'),
      filemtime(plugin_dir_path(__FILE__) . 'js/preview-form.js'),
      true
    );

    // Add max width CSS variable
    $max_width = get_option('wcpt_preview_template_max_width', '1400px');
    add_action('wp_head', function () use ($max_width) {
      echo '<style>
        :root {
          --wcpt-preview-template-max-width: ' . esc_attr($max_width) . ';
        }
      </style>';
    });
  }
}

// Display product table shortcode in content
add_filter('the_content', 'wcpt_display_table_shortcode');

function wcpt_display_table_shortcode($content)
{
  // Only run on product table post type and not in admin
  if (get_post_type() === 'wc_product_table' && !is_admin()) {
    $post_id = get_the_ID();

    // Display the preview form
    wcpt_display_preview_table_form();

    // Get saved shortcode or use default with current post ID
    $shortcode = get_post_meta($post_id, 'wcpt_preview_template_shortcode', true);
    if (empty($shortcode)) {
      $shortcode = '[product_table id="' . $post_id . '"]';
    }

    // Display the table using shortcode
    return do_shortcode($shortcode);
  }
  return $content;
}

/**
 * Display the preview form for product tables
 * Includes:
 * - Shortcode input with reset button
 * - Page template selector
 * - Max width selector (when product table preview is selected)
 * - Submit button
 */
function wcpt_display_preview_table_form()
{

  // Don't display form if customizer is active
  if (is_customize_preview()) {
    return;
  }

  $post_id = get_the_ID();
  if (!current_user_can('edit_post', $post_id)) {
    return;
  }

  $saved_shortcode = get_post_meta($post_id, 'wcpt_preview_template_shortcode', true);
  $template_option = get_option('wcpt_preview_template', 'product_table_preview');
  $max_width = get_option('wcpt_preview_template_max_width', '1400px');

  // Default shortcode with current post ID
  $default_shortcode = '[product_table id="' . $post_id . '"]';
  ?>
  <form class="wcpt-preview-form" action="" method="post">
    <?php wp_nonce_field('wcpt_preview_form', 'wcpt_preview_form_nonce'); ?>
    <fieldset>
      <legend>Product Table Preview Form</legend>
      <div class="wcpt-preview-form-container">
        <div class="wcpt-preview-form-row wcpt-preview-form-shortcode-container">
          <label>Table Shortcode:</label>
          <div class="wcpt-preview-form-shortcode-wrapper">
            <input type="text" name="wcpt_preview_template_shortcode" placeholder='<?php echo $default_shortcode; ?>'
              value="<?php echo $saved_shortcode ? esc_attr($saved_shortcode) : esc_attr($default_shortcode); ?>"
              data-default="<?php echo esc_attr($default_shortcode); ?>">
            <button type="button" class="wcpt-preview-form-reset-icon" title="Reset product table shortcode">↻</button>
          </div>
        </div>

        <div class="wcpt-preview-form-row wcpt-preview-form-template-dropdown-wrapper">
          <label>Page Template:</label>
          <select name="wcpt_preview_template" id="wcpt_preview_template">
            <option value="product_table_preview" <?php selected($template_option, 'product_table_preview'); ?>>Product
              table preview</option>
            <option value="default" <?php selected($template_option, 'default'); ?>>Default theme template</option>
          </select>
        </div>

        <div class="wcpt-preview-form-row wcpt-preview-form-max-width-wrapper"
          id="wcpt_preview_template_max_width_container"
          style="display: <?php echo $template_option === 'product_table_preview' ? 'block' : 'none'; ?>">
          <label>Max Width:</label>
          <select name="wcpt_preview_template_max_width">
            <option value="100%" <?php selected($max_width, '100%'); ?>>100%</option>
            <option value="1600px" <?php selected($max_width, '1600px'); ?>>1600px</option>
            <option value="1500px" <?php selected($max_width, '1500px'); ?>>1500px</option>
            <option value="1400px" <?php selected($max_width, '1400px'); ?>>1400px</option>
            <option value="1300px" <?php selected($max_width, '1300px'); ?>>1300px</option>
            <option value="1200px" <?php selected($max_width, '1200px'); ?>>1200px</option>
            <option value="1100px" <?php selected($max_width, '1100px'); ?>>1100px</option>
            <option value="1000px" <?php selected($max_width, '1000px'); ?>>1000px</option>
            <option value="900px" <?php selected($max_width, '900px'); ?>>900px</option>
          </select>
        </div>

        <input type="hidden" name="wcpt_preview_template_submit" value="1">
        <input type="submit" value="Submit">
      </div>
    </fieldset>
    <?php
    $shortcode = $saved_shortcode ? $saved_shortcode : $default_shortcode;
    $post_name = get_post_field('post_name', $post_id);

    if (
      !preg_match('/id=["\']' . $post_id . '["\']/', $shortcode) &&
      !preg_match('/name=["\']' . $post_name . '["\']/', $shortcode)
    ) {
      echo '<div class="wcpt-preview-form-shortcode-notice">The original shortcode for the table belonging to this page <code>[product_table id="' . $post_id . '"]</code> is missing from the table shortcode field above. Reset the shortcode to see the correct product table for this page.</div>';
    }

    // Build customizer URL
    $current_url = get_permalink($post_id);
    $customizer_url = admin_url('customize.php');
    $customizer_url .= '?url=' . urlencode($current_url . '?customize_theme=1');
    $customizer_url .= '&autofocus[panel]=wcpt_panel';
    ?>
    <a href="<?php echo esc_url($customizer_url); ?>" target="_blank" class="wcpt-preview-form-theme-customizer-link">Open
      theme customizer <?php echo wcpt_icon('external-link'); ?></a>
  </form>

  <script>
    jQuery(document).ready(function ($) {
      $('#wcpt_preview_template').on('change', function () {
        if ($(this).val() === 'product_table_preview') {
          $('#wcpt_preview_template_max_width_container').show();
        } else {
          $('#wcpt_preview_template_max_width_container').hide();
        }
      });
    });
  </script>
  <?php
}

// Add custom body class for product table preview template
add_filter('body_class', 'wcpt_add_preview_template_body_class');
function wcpt_add_preview_template_body_class($classes)
{
  if (is_singular('wc_product_table')) {
    $template_option = get_option('wcpt_preview_template', 'product_table_preview');
    if ($template_option === 'product_table_preview') {
      $classes[] = 'wcpt-preview-template';
    }
  }
  return $classes;
}
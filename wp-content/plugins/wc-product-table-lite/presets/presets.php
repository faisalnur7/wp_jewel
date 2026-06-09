<?php
add_action('admin_enqueue_scripts', 'wcpt_presets_enqueue_scripts');
function wcpt_presets_enqueue_scripts()
{
  if (defined('WCPT_DEV')) {
    $min = '';
  } else {
    $min = '.min';
  }

  if (
    empty($_GET['post_type']) ||
    $_GET['post_type'] !== 'wc_product_table' ||
    empty($_GET['page']) ||
    $_GET['page'] !== 'wcpt-edit'
  ) {
    return false;
  }

  wp_enqueue_script(
    'wcpt-presets',
    WCPT_PLUGIN_URL . 'presets/js' . $min . '.js',
    array('jquery'),
    WCPT_VERSION,
    true
  );

  wp_localize_script(
    'wcpt-presets',
    'wcptPresets',
    array(
      'ajaxUrl' => admin_url('admin-ajax.php'),
      'dismissNonce' => wp_create_nonce('wcpt_dismiss_preset_applied_message'),
    )
  );

  wp_enqueue_style(
    'wcpt-presets',
    WCPT_PLUGIN_URL . 'presets/presets' . $min . '.css',
    false,
    WCPT_VERSION
  );

  if (empty($_GET['post_id'])) {
    wp_enqueue_style(
      'wcpt-demos',
      WCPT_PLUGIN_URL . 'presets/demos/demos.css',
      false,
      WCPT_VERSION
    );
  }
}

/**
 * Whether WCPT Pro is active (presets.php may load before main.php defines wcpt_pro_enabled()).
 *
 * @return bool
 */
function wcpt_presets__is_pro_active()
{
  return function_exists('wcpt_pro_enabled') && wcpt_pro_enabled();
}

/**
 * Front-end demo URL for a preset (admin grid preview for every preset slug).
 *
 * @param string $slug Preset slug (filename without .json).
 * @return string
 */
function wcpt_presets__get_preset_preview_url($slug)
{
  return 'https://demo.wcproducttable.com/preset-demo-' . $slug;
}

/**
 * Single registry of presets: keys 'table' and 'list' are admin columns; each value is a list of name + slug.
 *
 * @return array
 */
function wcpt_presets__get_preset_definitions()
{
  return array(
    'table' => array(
      array(
        'name' => 'Table with description',
        'slug' => 'table-with-description',
      ),
      array(
        'name' => 'Table with attributes',
        'slug' => 'table-with-attributes',
      ),
      array(
        'name' => 'Table with checkbox',
        'slug' => 'table-with-checkbox',
        'pro' => true,
      ),
      array(
        'name' => 'Table with child row',
        'slug' => 'table-with-child-row',
        'pro' => true,
      ),
    ),
    'list' => array(
      array(
        'name' => 'List with description',
        'slug' => 'list-with-description',
      ),
      array(
        'name' => 'List with attribute bar',
        'slug' => 'list-with-attribute-bar',
        'pro' => true,
      ),
      array(
        'name' => 'List with justified attributes',
        'slug' => 'list-with-justified-attributes',
        'pro' => true,
      ),
      array(
        'name' => 'List with attribute table',
        'slug' => 'list-with-attribute-table',
        'pro' => true,
      ),
    ),
  );
}

/**
 * Slugs allowed for ?wcpt_preset= (includes blank).
 *
 * @return string[]
 */
function wcpt_presets__get_allowed_preset_slugs()
{
  $defs = wcpt_presets__get_preset_definitions();
  $is_pro = wcpt_presets__is_pro_active();
  $slugs = array('blank');
  foreach ($defs['table'] as $preset) {
    if (!empty($preset['pro']) && !$is_pro) {
      continue;
    }
    $slugs[] = $preset['slug'];
  }
  foreach ($defs['list'] as $preset) {
    if (!empty($preset['pro']) && !$is_pro) {
      continue;
    }
    $slugs[] = $preset['slug'];
  }
  return $slugs;
}

/**
 * One selectable preset card (table or list column).
 *
 * @param array  $preset  Preset row from wcpt_presets__get_preset_definitions().
 * @param bool   $is_pro  Whether the site has Pro (unlocks Use on Pro presets; Preview link only when Pro preset + Lite).
 */
function wcpt_presets__render_preset_grid_item($preset, $is_pro)
{
  $preset_locked = !empty($preset['pro']) && !$is_pro;
  $item_classes = 'wcpt-presets__item wcpt-presets__item--' . $preset['slug'];
  if ($preset_locked) {
    $item_classes .= ' wcpt-presets__item--locked';
  }
  $slug = $preset['slug'];
  ?>
  <div class="<?php echo esc_attr($item_classes); ?>" data-wcpt-preset-slug="<?php echo esc_attr($slug); ?>" <?php if ($preset_locked): ?>aria-disabled="true" <?php endif; ?>>
    <div class="wcpt-presets__item__header">
      <span class="wcpt-presets__item__name">
        <?php echo esc_html($preset['name']); ?>
        <?php if (!empty($preset['pro']) && !$is_pro): ?>
          <span class="wcpt-presets__item__name__pro">PRO</span>
        <?php endif; ?>
      </span>
      <div class="wcpt-presets__item__actions">
        <?php if ($preset_locked): ?>
          <a class="wcpt-presets__item__preview" href="<?php echo esc_url(wcpt_presets__get_preset_preview_url($slug)); ?>"
            target="_blank" rel="noopener noreferrer"><?php esc_html_e('Preview', 'wc-product-table'); ?>
            <?php wcpt_icon('external-link', 'wcpt-presets__item__preview-icon'); ?>
          </a>
        <?php else: ?>
          <span class="wcpt-presets__item__use"><?php esc_html_e('Use', 'wc-product-table'); ?>
            <?php wcpt_icon('arrow-right', 'wcpt-presets__item__use-icon'); ?></span>
        <?php endif; ?>
      </div>
    </div>
    <img class="wcpt-presets__item__image" alt=""
      src="<?php echo esc_url(WCPT_PLUGIN_URL . 'presets/thumb/' . $slug . '.webp'); ?>">
  </div>
  <?php
}

// presets grid markup
function wcpt_presets__get_grid_markup()
{
  ob_start();
  $definitions = wcpt_presets__get_preset_definitions();
  $table_presets = $definitions['table'];
  $list_presets = $definitions['list'];
  $is_pro = wcpt_presets__is_pro_active();

  ?>
  <div class="wcpt-preset-outer">
    <h2 class="wcpt-preset-heading">Select Preset</h2>
    <div class="wcpt-presets">
      <div class="wcpt-presets__item wcpt-presets__item--blank" data-wcpt-preset-slug="blank">
        <img class="wcpt-presets__item__image" src="<?php echo WCPT_PLUGIN_URL . 'presets/thumb/blank.png'; ?>">
        <div class="wcpt-presets__item__content">
          <div class="wcpt-presets__item__header wcpt-presets__item__header--blank">
            <span class="wcpt-presets__item__name"><?php esc_html_e('Blank', 'wc-product-table'); ?></span>
            <div class="wcpt-presets__item__actions">
              <span class="wcpt-presets__item__use"><?php esc_html_e('Use', 'wc-product-table'); ?>
                <?php wcpt_icon('arrow-right', 'wcpt-presets__item__use-icon'); ?></span>
            </div>
          </div>
          <span
            class="wcpt-presets__item__byline"><?php esc_html_e('No preset. Start with an empty editor.', 'wc-product-table'); ?></span>
        </div>
      </div>
      <div class="wcpt-presets__columns">
        <div class="wcpt-presets__column wcpt-presets__column--table">
          <h3 class="wcpt-presets__column-heading">Table presets</h3>
          <?php foreach ($table_presets as $preset): ?>
            <?php wcpt_presets__render_preset_grid_item($preset, $is_pro); ?>
          <?php endforeach; ?>
        </div>
        <div class="wcpt-presets__column wcpt-presets__column--list">
          <h3 class="wcpt-presets__column-heading">List presets</h3>
          <?php foreach ($list_presets as $preset): ?>
            <?php wcpt_presets__render_preset_grid_item($preset, $is_pro); ?>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
  <?php echo wcpt_presets__get_demos_grid_markup(); ?>
  <?php
  return ob_get_clean();
}

// set preset required meta flag
add_action('admin_init', 'wcpt_presets__set_preset_required_meta_flag');
function wcpt_presets__set_preset_required_meta_flag()
{
  // Check if we're on the table editor page
  if (!wcpt_preset__is_table_editor_page()) {
    return;
  }

  // Check if user has proper capabilities
  if (!current_user_can('create_wc_product_tables')) {
    return;
  }

  // Validate and sanitize post_id
  if (empty($_GET['post_id']) || !is_numeric($_GET['post_id'])) {
    return;
  }

  $post_id = intval($_GET['post_id']);

  // Verify post exists and is the correct type
  $post = get_post($post_id);
  if (!$post || $post->post_type !== 'wc_product_table') {
    return;
  }

  // If table is new (no data) then set preset required meta flag
  $table_data = get_post_meta($post_id, 'wcpt_data', true);
  if (!$table_data) {
    update_post_meta($post_id, 'wcpt_preset_required', true);
  }
}

// duplicate a preset to table
add_action('admin_init', 'wcpt_presets__duplicate_preset_to_table');
function wcpt_presets__duplicate_preset_to_table()
{
  // Check if we're on the table editor page
  if (!wcpt_preset__is_table_editor_page()) {
    return;
  }

  // Check for proper authorization
  if (!current_user_can('create_wc_product_tables')) {
    wp_die('Unauthorized action.');
  }

  // No preset selected yet
  if (empty($_GET['wcpt_preset'])) {
    return;
  }

  // Validate and sanitize post_id
  if (empty($_GET['post_id']) || !is_numeric($_GET['post_id'])) {
    return;
  }

  $post_id = intval($_GET['post_id']);

  // Verify post exists and is the correct type
  $post = get_post($post_id);
  if (!$post || $post->post_type !== 'wc_product_table') {
    return;
  }

  $slug = sanitize_file_name(wp_unslash($_GET['wcpt_preset']));

  if (!in_array($slug, wcpt_presets__get_allowed_preset_slugs(), true)) {
    wp_die('Invalid preset selected.');
  }

  // Preset already applied on this table
  if (!wcpt_preset__required($post_id)) {
    return;
  }

  // Apply the preset
  update_post_meta($post_id, 'wcpt_preset_required', false); // Turn off 'preset required' flag

  wp_update_post(array(
    'ID' => $post_id,
    'post_title' => $slug == 'blank' ? 'New table' : ucfirst(str_replace('-', ' ', $slug)),
    'post_status' => 'publish',
  ));

  if ($slug !== 'blank') {
    // Get data from json preset file
    $preset_path = WCPT_PLUGIN_PATH . 'presets/table/' . $slug . '.json';

    // More robust path validation to prevent directory traversal
    $real_preset_path = realpath($preset_path);
    $real_presets_dir = realpath(WCPT_PLUGIN_PATH . 'presets/table/');

    if ($real_preset_path && strpos($real_preset_path, $real_presets_dir) === 0 && file_exists($real_preset_path)) {
      $preset_json = file_get_contents($real_preset_path);
      $table_data = json_decode($preset_json, true);

      if ($table_data) {
        wcpt_new_ids($table_data);
        $table_data['id'] = $post_id;
        update_post_meta($post_id, 'wcpt_data', addslashes(json_encode($table_data)));
        update_post_meta($post_id, 'wcpt_preset_applied__message_required', true);
        update_post_meta($post_id, 'wcpt_preset_applied__slug', $slug);
      }
    }
  }
}

function wcpt_preset__maybe_display_message($post_id = false)
{
  if (!$post_id) {
    if (empty($_GET['post_id']) || !is_numeric($_GET['post_id'])) {
      return false;
    }
    $post_id = absint($_GET['post_id']);
  } else {
    $post_id = absint($post_id);
  }

  if ($post_id < 1) {
    return false;
  }

  $post = get_post($post_id);
  if (!$post || $post->post_type !== 'wc_product_table') {
    return false;
  }

  if (!get_post_meta($post_id, 'wcpt_preset_applied__message_required', true)) {
    return false;
  }

  $preset_slug = get_post_meta($post_id, 'wcpt_preset_applied__slug', true);
  $preset_name = $preset_slug ? ucfirst(str_replace('-', ' ', $preset_slug)) : '';

  $layout_type = strpos($preset_slug, 'list') !== false ? 'list' : 'table';

  ob_start();
  ?>
  <div class="wcpt-preset-applied-message" data-post-id="<?php echo esc_attr((string) $post_id); ?>">
    <button type="button" class="wcpt-preset-applied-message__dismiss"
      aria-label="<?php esc_attr_e('Dismiss', 'wc-product-table'); ?>"><?php wcpt_icon('x') ?></button>
    <h2 class="wcpt-preset-message-heading">Your product <?php echo $layout_type; ?> layout is ready! 🎉</h2>
    <ul class="wcpt-preset-applied-message__list">
      <li>You selected the '<?php echo $preset_name; ?>' preset to create this layout.</li>
      <li>You can preview your new product <?php echo $layout_type; ?> layout via <a
          href="<?php echo esc_url(get_permalink($post_id)); ?>" target="_blank">this private
          link<?php wcpt_icon('external-link', 'wcpt-preset-applied-message__new-page-icon'); ?></a>.
      </li>
      <?php if (stripos($preset_name, 'child row') !== false): ?>
        <li>
          See the <a href="https://wcproducttable.com/documentation/child-row-facility" target="_blank">child row
            documentation</a>
          to
          know more about the featre.
        </li>
      <?php endif; ?>
      <li>
        You can fully customize it. Please see resources:
        <a href="https://wcproducttable.com/tutorials/" target="_blank">Tuts</a>,
        <a href="https://wcproducttable.com/documentation/" target="_blank">Docs</a>,
        <a href="https://www.notion.so/FAQs-f624e13d0d274a08ba176a98d6d79e1f" target="_blank">FAQs</a>,
        <a href="https://www.youtube.com/@woocommerceproducttablepro7033/videos" target="_blank">YouTube</a>
      </li>

    </ul>
  </div>
  <?php
  $html = ob_get_clean();

  echo $html;

  return true;
}

add_action('wp_ajax_wcpt_dismiss_preset_applied_message', 'wcpt_dismiss_preset_applied_message');
function wcpt_dismiss_preset_applied_message()
{
  if (
    empty($_POST['nonce']) ||
    !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'wcpt_dismiss_preset_applied_message')
  ) {
    wp_send_json_error(array('message' => 'bad_nonce'), 403);
  }

  $post_id = isset($_POST['post_id']) ? absint($_POST['post_id']) : 0;
  if (
    $post_id < 1 ||
    get_post_type($post_id) !== 'wc_product_table' ||
    !current_user_can('edit_wc_product_table', $post_id)
  ) {
    wp_send_json_error(array('message' => 'forbidden'), 403);
  }

  update_post_meta($post_id, 'wcpt_preset_applied__message_required', false);

  wp_send_json_success();
}

// check if presets required
function wcpt_preset__required($post_id = false)
{
  if (!$post_id) {
    if (empty($_GET['post_id'])) {
      return false;
    }
    $post_id = $_GET['post_id'];
  }

  return get_post_meta($post_id, 'wcpt_preset_required', true);
}

function wcpt_preset__is_table_editor_page()
{
  return !empty($_GET['post_type']) &&
    $_GET['post_type'] === 'wc_product_table' &&
    !empty($_GET['page']) &&
    $_GET['page'] === 'wcpt-edit';
}

function wcpt_presets__get_demos_grid_markup()
{
  ob_start();
  include WCPT_PLUGIN_PATH . 'presets/demos/demos.html';
  $markup = ob_get_clean();

  $markup = str_replace('./src/', WCPT_PLUGIN_URL . 'presets/demos/src/', $markup);

  return $markup;
}
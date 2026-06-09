<?php
if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly
}
?>

<div class="wcpt-editor-clear"></div>

<h1 class="wcpt-page-title dashicons-before dashicons-editor-justify">
  <?php _e("Product Table Settings", "wc-product-table"); ?>
</h1>

<div class="wcpt-title-resources">
  <a href="https://wcproducttable.com/tutorials/" target="_blank">How to use</a>
  <a href="https://wcproducttable.com/documentation/" target="_blank">Documentation</a>
  <a href="https://www.notion.so/FAQs-f624e13d0d274a08ba176a98d6d79e1f" target="_blank">FAQs</a>
  <a href="https://pro.wcproducttable.com/#addons" target="_blank">Addons</a>
  <a href="https://wcproducttable.com/support/" target="_blank">Support</a>
</div>

<div class="wcpt-editor-clear"></div>

<?php
// if post_id is not set, show preset options
if (empty($_GET['post_id'])) {
  echo wcpt_presets__get_grid_markup();
  return;
}
?>

<div class="wcpt-editor-clear"></div>

<div class="wcpt-editor-top-fields-container">
  <!-- table name -->
  <div class="wcpt-editor-top-fields-row">
    <div class="wcpt-editor-top-fields-cell wcpt-editor-top-fields-label">Table name</div>
    <div class="wcpt-editor-top-fields-cell wcpt-editor-top-fields-content">
      <!-- table name input -->
      <input type="text" class="wcpt-editor-top-fields-input wcpt-table-title" placeholder="Enter name here..."
        value="<?php echo (isset($_GET['post_id']) ? get_the_title((int) $_GET['post_id']) : ''); ?>">
      <!-- button group -->
      <div class="wcpt-editor-top-table-actions wcpt-toggle wcpt-toggle-off">
        <span class="wcpt-toggle-trigger">
          Table actions
          <?php echo wcpt_icon('more-vertical'); ?>
        </span>
        <div class="wcpt-toggle-tray">
          <!-- duplicate -->
          <span <?php if (defined('WCPT_PRO'))
            echo 'data-wcpt-action="duplicate"'; ?>
            data-wcpt-url="<?php echo wp_nonce_url(admin_url('admin.php?action=wcpt_duplicate_post_as_draft&post=' . $_GET['post_id']), WCPT_PLUGIN_PATH, 'duplicate_nonce'); ?>"
            title="Duplicate table">
            <?php echo wcpt_icon('copy'); ?>
            Duplicate <?php wcpt_pro_badge(); ?>
          </span>
          <!-- export -->
          <span <?php if (defined('WCPT_PRO'))
            echo 'data-wcpt-action="export"'; ?>
            data-wcpt-nonce="<?php echo wp_create_nonce('wcpt_import_export'); ?>" title="Export table">
            <?php echo wcpt_icon('download'); ?>
            Export <?php wcpt_pro_badge(); ?>
          </span>
          <!-- trash -->
          <span data-wcpt-action="trash"
            data-wcpt-url="<?php echo wp_nonce_url(admin_url('post.php?post=' . $_GET['post_id'] . '&action=trash&_wp_http_referer=' . urlencode(admin_url('edit.php?post_type=wc_product_table'))), 'trash-post_' . $_GET['post_id']); ?>"
            title="Trash table">
            <?php echo wcpt_icon('trash-2'); ?>
            Trash
          </span>
        </div>
      </div>
    </div>
  </div>

  <!-- shortcode -->
  <div class="wcpt-editor-top-fields-row">
    <div class="wcpt-editor-top-fields-cell wcpt-editor-top-fields-label">Shortcode</div>
    <div class="wcpt-editor-top-fields-cell wcpt-editor-top-fields-content">
      <!-- shortcode display -->
      <div class="wcpt-sc-display-wrapper">
        <!-- shortcode input -->
        <input class="wcpt-sc-display wcpt-editor-top-fields-input"
          value="<?php echo esc_html('[product_table id="' . $post_id . '"]'); ?>" readonly="">
        <!-- copy button -->
        <span class="wcpt-sc-display-copy-button" title="Copy shortcode">
          <span class="wcpt-sc-display-copy-button-icon" style="">
            <span class="wcpt-icon wcpt-icon-copy  "><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round" class="feather feather-copy">
                <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
              </svg></span> </span>
          <!-- copy success -->
          <span class="wcpt-sc-display-copy-button-success">
            <span class="wcpt-icon wcpt-icon-check wcpt-sc-display-copy-button-success-icon "><svg
                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="feather feather-check">
                <polyline points="20 6 9 17 4 12"></polyline>
              </svg></span> Copied!
          </span>
        </span>
      </div>
      <!-- shortcode options -->
      <span class="wcpt-shortcode-info wcpt-toggle wcpt-toggle-off">
        <span class="wcpt-toggle-trigger">
          Shortcode ops
          <?php echo wcpt_icon('more-vertical'); ?>
        </span>
        <!-- shortcode options tray -->
        <span class="wcpt-toggle-tray" style="margin-right: -20px;">
          <!-- close button -->
          <?php echo wcpt_icon('x', 'wcpt-toggle-x'); ?>
          <?php require_once('shortcode-info.php'); ?>

        </span>
      </span>

    </div>
  </div>
</div>

<div class="wcpt-editor-clear"></div>


<!-- table creation checklist / preset applied message -->
<?php
$preset_message_shown = false;
if (function_exists('wcpt_preset__maybe_display_message')) {
  $preset_message_shown = wcpt_preset__maybe_display_message(isset($post_id) ? $post_id : 0);
}
if (!$preset_message_shown) {
  require_once('partials/checklist.php');
}
?>

<!-- editor begins -->
<div class="wcpt-editor" wcpt-model-key="data">
  <!-- tab triggers -->
  <div class="wcpt-tab-triggers">
    <div class="wcpt-tab-label wcpt-products-tab active" data-wcpt-tab="products">
      <span class="wcpt-tab-label-text">
        <?php wcpt_icon('box', 'wcpt-tab-label-icon'); ?>
        <?php _e("Query", "wc-product-table"); ?>
      </span>
    </div>
    <div class="wcpt-tab-label wcpt-columns-tab" data-wcpt-tab="columns">
      <span class="wcpt-tab-label-text">
        <?php wcpt_icon('menu', 'wcpt-tab-label-icon wcpt-rotate-90'); ?>
        <?php _e("Columns", "wc-product-table"); ?>
      </span>
    </div>
    <div class="wcpt-tab-label wcpt-navigation-tab" data-wcpt-tab="navigation">
      <span class="wcpt-tab-label-text">
        <?php wcpt_icon('filter', 'wcpt-tab-label-icon'); ?>
        <?php _e("Navigation", "wc-product-table"); ?>
      </span>
    </div>
    <div class="wcpt-tab-label wcpt-style-tab " data-wcpt-tab="style">
      <span class="wcpt-tab-label-text">
        <?php wcpt_icon('paint-brush', 'wcpt-tab-label-icon'); ?>
        <?php _e("Style", "wc-product-table"); ?>
      </span>
    </div>
    <?php do_action('wcpt_main_tabs_end'); ?>
  </div>

  <!-- query tab -->
  <div class="wcpt-editor-tab-products  wcpt-tab-content active" data-wcpt-tab="products" wcpt-model-key="query">
    <?php if (!empty($_GET['qv2']) && $_GET['qv2'] === "false"): ?>
      <?php require_once('partials/query.php'); ?>
    <?php else: ?>
      <div id="wcpt-query-editor-v2">
        <?php require_once WCPT_PLUGIN_PATH . 'query_editor_v2/query_editor_v2.html'; ?>
      </div>
    <?php endif; ?>
  </div>

  <!-- columns tab -->
  <div class="wcpt-editor-tab-columns wcpt-tab-content" data-wcpt-tab="columns" wcpt-controller="columns"
    wcpt-model-key="columns">

    <!-- column devices tab -->
    <div class="wcpt-editor-tab-columns__device-tabs">
      <!-- triggers -->
      <div class="wcpt-editor-tab-columns__device-tabs__triggers">
        <span
          class="wcpt-editor-tab-columns__device-tabs__triggers__item wcpt-editor-tab-columns__device-tabs__triggers__item--selected"
          data-wcpt-device="laptop">
          <img class="wcpt-column-device-icon wcpt-column-device-icon--laptop"
            src="<?php echo WCPT_PLUGIN_URL . 'assets/feather/square.svg'; ?>">
          <span>
            Laptop columns
          </span>
        </span>
        <span class="wcpt-editor-tab-columns__device-tabs__triggers__item" data-wcpt-device="tablet">
          <img class="wcpt-column-device-icon wcpt-column-device-icon--tablet"
            src="<?php echo WCPT_PLUGIN_URL . 'assets/feather/tablet.svg'; ?>">
          <span>
            Tablet columns
          </span>
        </span>
        <span class="wcpt-editor-tab-columns__device-tabs__triggers__item" data-wcpt-device="phone">
          <img class="wcpt-column-device-icon wcpt-column-device-icon--phone"
            src="<?php echo WCPT_PLUGIN_URL . 'assets/feather/smartphone.svg'; ?>">
          <span>
            Phone columns
          </span>
        </span>
      </div>
      <!-- panels -->
      <div class="wcpt-editor-tab-columns__device-tabs__panels">
        <div
          class="wcpt-editor-tab-columns__device-tabs__panels__item wcpt-editor-tab-columns__device-tabs__panels__item--selected"
          data-wcpt-device="laptop"></div>
        <div class="wcpt-editor-tab-columns__device-tabs__panels__item" data-wcpt-device="tablet"></div>
        <div class="wcpt-editor-tab-columns__device-tabs__panels__item" data-wcpt-device="phone"></div>
      </div>
      <!-- show all -->
      <label class="wcpt-editor-tab-columns__device-tabs__show-all-columns">
        <input type="checkbox" name="wcpt-show-all-columns"> <span>View all columns</span>
      </label>

      <!-- scroll to top -->
      <a href="#" class="wcpt-editor-tab-columns__device-tabs__scroll-to-top">
        <span>Scroll to top</span>
        <?php wcpt_icon('corner-right-up') ?>
      </a>

      <!-- more options for columns -->
      <span class="wcpt-editor-tab-columns__device-tabs__more-options ">
        <span id="wcpt-more-column-options"></span>
        <!--
        <span class="wcpt-tooltip" data-wcpt-direction="bottom" data-wcpt-allow-hover="true">
          <span class="wcpt-tooltip-text">Other options</span>
          <span class="wcpt-tooltip-icon"><?php wcpt_icon('help-circle'); ?></span>
          <span class="wcpt-tooltip-content" style="max-width: 350px; width: 350px; padding: 15px;">
            <div>
              <a href="https://wcproducttable.com/documentation/freeze-table-columns-and-heading-row" target="_blank">
                <?php wcpt_icon('layout'); ?>
                Sticky table heading and columns
                <?php wcpt_icon('external-link'); ?>
              </a>
              <a href="https://wcproducttable.com/documentation/child-row-facility" target="_blank">
                <?php wcpt_icon('chevron-down-circle'); ?>
                Create child rows with more info
                <?php wcpt_icon('external-link'); ?>
              </a>
              <a href="https://wcproducttable.com/documentation/auto-hide-empty-product-table-columns" target="_blank">
                <?php wcpt_icon('eye-off'); ?>
                Auto hide empty table columns
                <?php wcpt_icon('external-link'); ?>
              </a>
            </div>
          </span>
        </span>
        -->
      </span>

    </div>

    <?php
    // create the 3 device columns ui
    $devices = array('laptop', 'tablet', 'phone');
    foreach ($devices as $index => $device) {
      ?>
      <!-- <?php echo $device ?> -->
      <div class="wcpt-editor-columns-container wcpt-sortable" data-wcpt-device="<?php echo $device; ?>"
        wcpt-model-key="<?php echo $device; ?>" wcpt-connect-with="[wcpt-controller='device_columns']"
        wcpt-controller="device_columns">
        <?php
        $device_icon = array('laptop' => 'square', 'tablet' => 'tablet', 'phone' => 'smartphone');
        $src = WCPT_PLUGIN_URL . '/assets/feather/' . $device_icon[$device] . '.svg';
        ?>


        <h2 class="wcpt-editor-light-heading">
          <img class="wcpt-column-device-icon wcpt-column-device-icon--<?php echo $device; ?>"
            src="<?php echo $src; ?>" />
          <span>
            <?php echo ucfirst($device); ?> Columns
            <?php wcpt_icon('corner-right-down', 'wcpt-column-device-down-arrow'); ?>
          </span>

          <!-- <div class="wcpt-column-links"></div>
            <div class="wcpt-device-columns-toggle">
              <a href="#" class="wcpt-device-columns-toggle__expand">Expand</a> /
              <a href="#" class="wcpt-device-columns-toggle__contract">Contract</a>
              all
            </div>             -->
        </h2>


        <!-- no device columns message -->
        <div class="wcpt-no-device-columns-message">
          There are no columns created for
          <? echo ucwords($device); ?> devices in this table.<br>
          Use the '+ Add column' button to create new columns for
          <? echo ucwords($device); ?>s.<br>
          <?php if (in_array($device, array('tablet', 'phone'))): ?>
            Or leave it empty and
            <? echo ucwords($devices[$index - 1]); ?> columns will be used for
            <? echo ucwords($device); ?>s.
          <?php endif; ?>
        </div>

        <?php require('partials/columns.php'); ?>
      </div>
      <hr class="wcpt-editor-columns-device-divider">
      <?php
    }
    ?>

  </div><!-- /columns tab -->

  <!-- style tab -->
  <div class="wcpt-editor-tab-style wcpt-tab-content" data-wcpt-tab="style" wcpt-model-key="style">
    <?php require_once('partials/style.php') ?>
  </div>

  <!-- navigation tab -->
  <div class="wcpt-editor-tab-navigation wcpt-tab-content" data-wcpt-tab="navigation" wcpt-model-key="navigation"
    wcpt-initial-data="navigation">
    <?php require_once('partials/navigation.php') ?>
  </div>

  <?php do_action('wcpt_main_tab_panels_end'); ?>

</div><!-- /.wcpt-editor -->

<!-- save data -->

<div class="wcpt-editor-save-table-wrapper">
  <div class="wcpt-editor-save-table">
    <?php
    $wcpt_action = 'wcpt_save_table_settings';
    require_once('partials/save.php');
    ?>
  </div>
</div>

<div class="wcpt-footer">
  <div class="wcpt-support wcpt-footer-note">
    <?php wcpt_icon('alert-circle'); ?>
    <span>
      <?php _e("Found a bug / Got questions? Please reach out for support here: ", "wc-product-table"); ?><a
        href="mailto:support@wcproducttable.com" target="_blank">support@wcproducttable.com</a> | <a
        href="https://wcproducttable.com/tutorials/" target="_blank">Tutorials</a>
    </span>
  </div>

  <div class="wcpt-support wcpt-footer-note">
    <?php wcpt_icon('heart'); ?>
    <span>
      Do you like our plugin? Please support our work with your <span class="wcpt-footer-note-stars">
        <?php wcpt_icon('star'); ?> <?php wcpt_icon('star'); ?>
        <?php wcpt_icon('star'); ?> <?php wcpt_icon('star'); ?> <?php wcpt_icon('star'); ?>
      </span> <a href="https://wordpress.org/support/plugin/wc-product-table-lite/reviews/" target="_blank">5 star
        rating here</a>. Thanks!
    </span>
  </div>
</div>

<!-- icon templates -->
<?php
$icons = array('trash', 'trash-2', 'settings', 'copy', 'x', 'check');
foreach ($icons as $icon_name) {
  ?>
  <script type="text/template" id="wcpt-icon-<?php echo $icon_name; ?>"><?php echo wcpt_icon($icon_name); ?></script>
  <?php
}
?>

<!-- element partials -->
<?php require_once('partials/element-editor/element-partials.php'); ?>

<!-- required js vars -->
<?php
$attributes = wc_get_attribute_taxonomies();
?>
<script>wcpt_attributes = <?php echo json_encode($attributes) ?>;</script>
<script>var wcpt_icons_url = "<?php echo WCPT_PLUGIN_URL . '/assets/feather'; ?>";</script>

<!-- embedded style -->
<?php
$svg_cross_path = plugin_dir_url(__FILE__) . 'assets/css/cross.svg';
?>
<style media="screen">
  .wcpt-block-editor-lightbox-screen {
    cursor: url('<?php echo $svg_cross_path; ?>'), auto;
  }
</style>
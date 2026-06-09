<?php
if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly
}
?>

<div class="wcpt-editor-clear"></div>
<h1 class="wcpt-page-title dashicons-before dashicons-editor-justify">
  <?php _e("Global Product Table Settings", "wc-product-table"); ?>
</h1>

<div class="wcpt-editor-clear"></div>

<!-- settings begin -->
<div class="wcpt-settings" wcpt-model-key="data">

  <?php
  if (defined('WCPT_PRO')) {
    require_once('settings-partials/pro-license-v2.php');
  }
  ?>
  <?php require_once('settings-partials/archive-override.php'); ?>
  <?php require_once('settings-partials/theme-customize.php'); ?>
  <?php require_once('settings-partials/variation-table-override.php'); ?>
  <?php require_once('settings-partials/cart-widget.php'); ?>
  <?php require_once('settings-partials/checkbox-trigger.php'); ?>
  <?php require_once('settings-partials/modals.php'); ?>
  <?php require_once('settings-partials/no-results.php'); ?>
  <?php require_once('settings-partials/search.php'); ?>
  <?php do_action('wcpt_settings_panel_end'); ?>

  <!-- save data -->
  <div class="wcpt-editor-save-reset-container">
    <?php
    $wcpt_action = 'wcpt_save_global_settings';
    require_once('partials/save.php');
    ?>
    <div class="wcpt-reset-global-settings-container">
      <a class="wcpt-reset-global-settings"
        href="<?php echo admin_url('edit.php?post_type=wc_product_table&page=wcpt-settings&wcpt_reset_global_settings=true&_wp_nonce=' . wp_create_nonce('wcpt_reset_global_settings')); ?>">
        ↻ Reset settings
        <i class="wcpt-resetting-icon">
          <?php wcpt_icon('loader', 'wcpt-rotate'); ?>
        </i>
      </a>
    </div>
  </div>


</div>
<!-- /settings end -->

<!-- import export -->
<?php require_once('settings-partials/import-export.php'); ?>

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
$icons = array('trash', 'sliders', 'copy', 'x', 'check');
foreach ($icons as $icon_name) {
  ?>
  <script type="text/template" id="wcpt-icon-<?php echo $icon_name; ?>">
                    <?php echo wcpt_icon($icon_name); ?>
                    </script>
  <?php
}
?>

<!-- element partials -->
<?php require_once('partials/element-editor/element-partials.php'); ?>

<!-- required js vars -->
<script>var wcpt_icons_url = "<?php echo WCPT_PLUGIN_URL . 'assets/feather'; ?>";</script>
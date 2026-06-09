<?php
if (!defined('WOOSC_VERSION')) {
  ?>
  <div class="wcpt-notice">
    Note: To use this element you need to have the plugin <a target="_blank"
      href="https://wordpress.org/plugins/woo-smart-compare/">'WPC Smart Compare for WooCommerce'</a> installed and
    activated on your site. This is a compatible 3rd party woocommerce product compare plugin.
  </div>
  <?php
}
?>

<!-- label -->
<div class="wcpt-editor-row-option">
  <label>
    Compare label
  </label>
  <input type="text" wcpt-model-key="compare_label" placeholder="Compare" />
</div>

<!-- style for compare button -->
<?php require('style/common.php'); ?>

<!-- condition -->
<?php include('condition/outer.php'); ?>
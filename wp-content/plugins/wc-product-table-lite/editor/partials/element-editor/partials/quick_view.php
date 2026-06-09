<?php
if (!defined('WOOSQ_VERSION')) {
  ?>
  <div class="wcpt-notice">
    Note: To use this element you need to have the plugin <a target="_blank"
      href="https://wordpress.org/plugins/woo-smart-quick-view/">'WPC Smart Quick View for WooCommerce'</a> installed and
    activated on your site. This is a compatible 3rd party woocommerce product quick view plugin.
  </div>
  <?php
}
?>

<!-- label -->
<div class="wcpt-editor-row-option">
  <label>
    Quick view label
  </label>
  <input type="text" wcpt-model-key="quick_view_label" placeholder="Quick view" />
</div>

<!-- style for quick view button -->
<?php require('style/common.php'); ?>

<!-- condition -->
<?php include('condition/outer.php'); ?>
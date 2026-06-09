<?php
switch ($wcpt_action) {
  case 'wcpt_save_table_settings':
    ob_start();
    ?>
    <input name="post_id" type="hidden" value="<?php echo $post_id; ?>" />
    <input name="nonce" type="hidden" value="<?php echo wp_create_nonce("wcpt"); ?>">
    <input name="title" type="hidden"
      value="<?php echo (isset($post_id) ? get_the_title($post_id) : __("Untitled table", "wc-product-table")); ?>" />
    <?php
    $wcpt_input_fields = ob_get_clean();
    break;
  case 'wcpt_save_global_settings':
    ob_start();
    ?>
    <input name="nonce" type="hidden" value="<?php echo wp_create_nonce("wcpt"); ?>">
    <?php
    $wcpt_input_fields = ob_get_clean();
    break;
}
?>
<div class="wcpt-editor-save-table">
  <form class="wcpt-save-data" action="<?php echo $wcpt_action; ?>" method="post">
    <!-- hidden fields -->
    <?php echo $wcpt_input_fields; ?>
    <button type="submit" class="wcpt-editor-save-button button button-primary button-large">
      <?php _e("Save settings", "wcpt"); ?>
      <i class="wcpt-saving-icon">
        <?php wcpt_icon('loader', 'wcpt-rotate'); ?>
      </i>
      <i class="wcpt-saved-icon">
        <?php wcpt_icon('check'); ?>
      </i>
    </button>
    <br />
    <div class="wcpt-save-keys">
      Mac: ⌘ + s | Win: ctrl + s
    </div>
  </form>
</div>
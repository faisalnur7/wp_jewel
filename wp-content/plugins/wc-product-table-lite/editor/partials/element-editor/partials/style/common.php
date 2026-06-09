<div class="wcpt-editor-row-option">
  <label>HTML Class</label>
  <input type="text" wcpt-model-key="html_class" />
</div>

<div class="wcpt-editor-row-option" wcpt-model-key="style">

  <div class="wcpt-editor-row-option wcpt-toggle-options wcpt-row-accordion" wcpt-model-key="[id]">

    <span class="wcpt-toggle-label">
      <?php echo wcpt_icon('paint-brush'); ?>
      Style for <?php echo !empty($element_name) ? $element_name : 'Element'; ?>
      <?php echo wcpt_icon('chevron-down'); ?>
    </span>

    <?php require('common-props.php'); ?>

  </div>

</div>
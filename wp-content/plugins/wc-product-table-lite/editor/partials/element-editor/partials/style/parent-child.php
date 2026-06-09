<div class="wcpt-editor-row-option">
  <label>HTML Class</label>
  <input type="text" wcpt-model-key="html_class" />
</div>

<div class="wcpt-editor-row-option">

  <!-- Terms -->
  <div class="wcpt-editor-row-option" wcpt-model-key="style">
    <div class="wcpt-toggle-options wcpt-row-accordion" wcpt-model-key="[id] > div:not(.wcpt-term-separator)">

      <span class="wcpt-toggle-label">
        <?php echo wcpt_icon('paint-brush'); ?>
        Style for Terms
        <?php echo wcpt_icon('chevron-down'); ?>
      </span>

      <?php require('common-props.php'); ?>

    </div>
  </div>

  <!-- Selected terms -->
  <div class="wcpt-editor-row-option" wcpt-model-key="style" wcpt-panel-condition="prop"
    wcpt-condition-prop="click_action" wcpt-condition-val="trigger_filter">
    <div class="wcpt-toggle-options wcpt-row-accordion"
      wcpt-model-key="[id] > div[data-wcpt-filtering=true]:not(.wcpt-term-separator)">

      <span class="wcpt-toggle-label">
        <?php echo wcpt_icon('paint-brush'); ?>
        Style for Selected Terms
        <?php echo wcpt_icon('chevron-down'); ?>
      </span>

      <?php require('selected-term-props.php'); ?>

    </div>
  </div>

  <!-- Container -->
  <div class="wcpt-editor-row-option" wcpt-model-key="style">
    <div class="wcpt-toggle-options wcpt-row-accordion" wcpt-model-key="[id]">

      <span class="wcpt-toggle-label">
        <?php echo wcpt_icon('paint-brush'); ?>
        Style for Container
        <?php echo wcpt_icon('chevron-down'); ?>
      </span>

      <?php require('container-props.php'); ?>

    </div>
  </div>

</div>
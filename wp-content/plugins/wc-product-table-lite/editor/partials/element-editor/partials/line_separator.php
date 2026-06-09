<!-- hide last -->
<div class="wcpt-editor-row-option">
  <label>
    <input type="checkbox" wcpt-model-key="hide_last" />
    Hide if last item in the row
  </label>
</div>

<!-- hide first -->
<div class="wcpt-editor-row-option">
  <label>
    <input type="checkbox" wcpt-model-key="hide_first" />
    Hide if first item in the row
  </label>
</div>

<div class="wcpt-editor-row-option">
  <label>
    HTML class
    <small>Optional class for the element</small>
  </label>
  <input type="text" wcpt-model-key="html_class" />
</div>

<div class="wcpt-editor-row-option" wcpt-model-key="style">
  <div class="wcpt-toggle-options wcpt-row-accordion" wcpt-model-key="[id]">

    <span class="wcpt-toggle-label">
      <?php echo wcpt_icon('paint-brush'); ?>
      Style for Line Separator
      <?php echo wcpt_icon('chevron-down'); ?>
    </span>

    <!-- margin-top -->
    <div class="wcpt-editor-row-option">
      <label>Gap above</label>
      <input type="text" wcpt-model-key="margin-top" class="wcpt-margin-input-force-full-width">
    </div>

    <!-- margin-bottom -->
    <div class="wcpt-editor-row-option">
      <label>Gap below</label>
      <input type="text" wcpt-model-key="margin-bottom" class="wcpt-margin-input-force-full-width">
    </div>

    <!-- height -->
    <div class="wcpt-editor-row-option">
      <label>Line thickness</label>
      <input type="text" wcpt-model-key="height" placeholder="1px">
    </div>

    <!-- background color -->
    <div class="wcpt-editor-row-option">
      <label>Line color</label>
      <input type="text" wcpt-model-key="background-color" class="wcpt-color-picker">
    </div>

    <!-- border-radius -->
    <div class="wcpt-editor-row-option">
      <label>Border radius</label>
      <input type="text" wcpt-model-key="border-radius">
    </div>

  </div>
</div>
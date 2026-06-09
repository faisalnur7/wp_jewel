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
<!-- style for stick separator -->
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
      Style for Stick Separator
      <?php echo wcpt_icon('chevron-down'); ?>
    </span>

    <!-- font-size -->
    <div class="wcpt-editor-row-option">
      <label>Height</label>
      <input type="text" wcpt-model-key="--wcpt-stick-separator-height" placeholder="1em">
    </div>

    <!-- font-color -->
    <div class="wcpt-editor-row-option">
      <label>Color</label>
      <input type="text" wcpt-model-key="--wcpt-stick-separator-background-color" placeholder="#000"
        class="wcpt-color-picker">
    </div>

    <!-- width -->
    <div class="wcpt-editor-row-option">
      <label>Width</label>
      <input type="text" wcpt-model-key="--wcpt-stick-separator-width" placeholder="2px">
    </div>

    <!-- gap -->
    <div class="wcpt-editor-row-option">
      <label>Gap</label>
      <input type="text" wcpt-model-key="--wcpt-stick-separator-gap" placeholder="8px">
      </input>
    </div>

    <!-- position offset -->
    <div class="wcpt-editor-row-option">
      <label>Position offset</label>
      <div class="wcpt-flex-option-container">
        <input type="text" wcpt-model-key="top" placeholder="top">
        <input type="text" wcpt-model-key="right" placeholder="right">
        <input type="text" wcpt-model-key="bottom" placeholder="bottom">
        <input type="text" wcpt-model-key="left" placeholder="left">
      </div>
    </div>

  </div>


</div>

<!-- condition -->
<?php include('condition/outer.php'); ?>
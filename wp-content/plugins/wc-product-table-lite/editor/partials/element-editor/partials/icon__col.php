<div class="wcpt-editor-row-option">
  <label>Icon source</label>
  <select wcpt-model-key="icon_source">
    <option value="included">Included icons</option>
    <option value="custom">Custom SVG icon</option>
  </select>
</div>

<div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="icon_source"
  wcpt-condition-val="included">
  <label>Select icon</label>
  <?php wcpt_print_icon_dopdown('name'); ?>
</div>

<div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="icon_source"
  wcpt-condition-val="custom">
  <label>
    Custom SVG icon
    <small>Copy SVG code from <a href="https://tablericons.com/" target="_blank">Tabler Icon</a> and paste it here
    </small>
  </label>
  <textarea wcpt-model-key="custom_icon"></textarea>
</div>

<div class="wcpt-editor-row-option">
  <label>
    HTML title attribute
    <small>Optional text that shows up on mouse hover</small>
  </label>
  <input type="text" wcpt-model-key="title" />
</div>

<div class="wcpt-editor-row-option">
  <label>
    HTML class
    <small>Optional class for the icon</small>
  </label>
  <input type="text" wcpt-model-key="html_class" />
</div>

<div class="wcpt-editor-row-option" wcpt-model-key="style">

  <div class="wcpt-toggle-options wcpt-row-accordion" wcpt-model-key="[id]">

    <span class="wcpt-toggle-label">
      <?php echo wcpt_icon('paint-brush'); ?>
      Style for Icon
      <?php echo wcpt_icon('chevron-down'); ?>
    </span>

    <!-- font-size -->
    <div class="wcpt-editor-row-option">
      <label>Size</label>
      <input type="text" wcpt-model-key="font-size">
    </div>

    <!-- font-color -->
    <div class="wcpt-editor-row-option">
      <label>Stroke color</label>
      <input type="text" wcpt-model-key="color" placeholder="#000" class="wcpt-color-picker">
    </div>

    <!-- fill -->
    <div class="wcpt-editor-row-option">
      <label>Fill color</label>
      <input type="text" wcpt-model-key="fill" class="wcpt-color-picker">
    </div>

    <!-- stroke-width -->
    <div class="wcpt-editor-row-option">
      <label>Stroke thickness</label>
      <input type="text" wcpt-model-key="stroke-width">
    </div>

    <!-- opacity -->
    <div class="wcpt-editor-row-option">
      <label>Opacity</label>
      <input type="number" min="0" max="1" step="0.1" wcpt-model-key="opacity">
    </div>

    <!-- margin -->
    <div class="wcpt-editor-row-option">
      <label>Margin</label>
      <div class="wcpt-flex-option-container">
        <input type="text" wcpt-model-key="margin-top" placeholder="top">
        <input type="text" wcpt-model-key="margin-right" placeholder="right">
        <input type="text" wcpt-model-key="margin-bottom" placeholder="bottom">
        <input type="text" wcpt-model-key="margin-left" placeholder="left">
      </div>
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
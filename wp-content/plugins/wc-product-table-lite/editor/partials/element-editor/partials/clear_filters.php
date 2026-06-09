<!-- clear all label -->
<div class="wcpt-editor-row-option">
  <label>
    <input type="checkbox" wcpt-model-key="hide_clear_all" />
    Hide "Clear All" button
  </label>

</div>

<!-- clear all label -->
<div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="hide_clear_all"
  wcpt-condition-val="false">
  <label>"Clear All" button label</label>
  <input type="text" wcpt-model-key="reset_label" />
</div>

<!-- clear all label -->
<div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="position"
  wcpt-condition-val="header||left_sidebar">
  <label>
    <input type="checkbox" wcpt-model-key="border_and_padding" />
    Add border and padding on container
  </label>

</div>

<div class="wcpt-editor-row-option">
  <label>HTML Class</label>
  <input type="text" wcpt-model-key="html_class" />
</div>

<div class="wcpt-editor-row-option" wcpt-model-key="style">

  <!-- clear filter buttons -->
  <div class="wcpt-editor-row-option">
    <div class="wcpt-toggle-options wcpt-row-accordion" wcpt-model-key="[id] > .wcpt-clear-filter">

      <span class="wcpt-toggle-label">
        <?php echo wcpt_icon('paint-brush'); ?>
        Style for Buttons
        <?php echo wcpt_icon('chevron-down'); ?>
      </span>

      <!-- font-size -->
      <div class="wcpt-editor-row-option">
        <label>Font size</label>
        <input type="text" wcpt-model-key="font-size" placeholder="16px">
      </div>

      <!-- font-color -->
      <div class="wcpt-editor-row-option">
        <label>Font color</label>
        <input type="text" wcpt-model-key="color" placeholder="#000" class="wcpt-color-picker">
      </div>

      <!-- font-color on hover -->
      <div class="wcpt-editor-row-option">
        <label>Font color on hover</label>
        <input type="text" wcpt-model-key="color:hover" placeholder="#000" class="wcpt-color-picker">
      </div>

      <!-- background-color -->
      <div class="wcpt-editor-row-option">
        <label>Background color</label>
        <input type="text" wcpt-model-key="background-color" class="wcpt-color-picker">
      </div>

      <!-- background-color on hover -->
      <div class="wcpt-editor-row-option">
        <label>Background color on hover</label>
        <input type="text" wcpt-model-key="background-color:hover" class="wcpt-color-picker">
      </div>

      <!-- border -->
      <div class="wcpt-editor-row-option">
        <label>Border</label>
        <div class="wcpt-flex-option-container">
          <input type="text" wcpt-model-key="border-width" placeholder="1px">
          <select wcpt-model-key="border-style">
            <option value="">Auto</option>
            <option value="solid">Solid</option>
            <option value="dashed">Dashed</option>
            <option value="dotted">Dotted</option>
          </select>
          <input type="text" wcpt-model-key="border-color" placeholder="#000" class="wcpt-color-picker">
        </div>
      </div>

      <!-- border color on hover -->
      <div class="wcpt-editor-row-option">
        <label>Border color on hover</label>
        <input type="text" wcpt-model-key="border-color:hover" placeholder="#000" class="wcpt-color-picker">
      </div>

      <!-- border-radius -->
      <div class="wcpt-editor-row-option">
        <label>Border radius</label>
        <input type="text" wcpt-model-key="border-radius" placeholder="4px">
      </div>

      <!-- padding -->
      <div class="wcpt-editor-row-option">
        <label>Padding</label>
        <div class="wcpt-flex-option-container">
          <input type="text" wcpt-model-key="padding-top" placeholder="5px">
          <input type="text" wcpt-model-key="padding-right" placeholder="10px">
          <input type="text" wcpt-model-key="padding-bottom" placeholder="5px">
          <input type="text" wcpt-model-key="padding-left" placeholder="8px">
        </div>
      </div>

    </div>
  </div>

  <!-- clear all filters button -->
  <div class="wcpt-editor-row-option">
    <div class="wcpt-toggle-options wcpt-row-accordion" wcpt-model-key="[id] > a.wcpt-clear-all-filters">

      <span class="wcpt-toggle-label">
        <?php echo wcpt_icon('paint-brush'); ?>
        Style for 'Clear All'
        <?php echo wcpt_icon('chevron-down'); ?>
      </span>

      <!-- font-size -->
      <div class="wcpt-editor-row-option">
        <label>Font size</label>
        <input type="text" wcpt-model-key="font-size" placeholder="16px">
      </div>

      <!-- font-color -->
      <div class="wcpt-editor-row-option">
        <label>Font color</label>
        <input type="text" wcpt-model-key="color" placeholder="#000" class="wcpt-color-picker">
      </div>

      <!-- hover font-color -->
      <div class="wcpt-editor-row-option">
        <label>Font color on hover</label>
        <input type="text" wcpt-model-key="color:hover" placeholder="#000" class="wcpt-color-picker">
      </div>

    </div>
  </div>

  <!-- container -->
  <div class="wcpt-editor-row-option">
    <div class="wcpt-toggle-options wcpt-row-accordion" wcpt-model-key="[id]">

      <span class="wcpt-toggle-label">
        <?php echo wcpt_icon('paint-brush'); ?>
        Style for Container
        <?php echo wcpt_icon('chevron-down'); ?>
      </span>

      <div class="wcpt-editor-row-option">
        <label>Width</label>
        <input type="text" wcpt-model-key="width" placeholder="Auto">
      </div>

      <div class="wcpt-editor-row-option">
        <label>Gap between buttons</label>
        <input type="text" wcpt-model-key="gap" placeholder="4px">
      </div>

      <!-- background-color -->
      <div class="wcpt-editor-row-option">
        <label>Background color</label>
        <input type="text" wcpt-model-key="background-color" class="wcpt-color-picker">
      </div>

      <!-- padding -->
      <div class="wcpt-editor-row-option">
        <label>Padding</label>
        <div class="wcpt-flex-option-container">
          <input type="text" wcpt-model-key="padding-top" placeholder="top (0px)">
          <input type="text" wcpt-model-key="padding-right" placeholder="right (0px)">
          <input type="text" wcpt-model-key="padding-bottom" placeholder="bottom (0px)">
          <input type="text" wcpt-model-key="padding-left" placeholder="left (0px)">
        </div>
      </div>

      <!-- margin -->
      <div class="wcpt-editor-row-option">
        <label>Margin</label>
        <div class="wcpt-flex-option-container">
          <input type="text" wcpt-model-key="margin-top" placeholder="top (0px)">
          <input type="text" wcpt-model-key="margin-right" placeholder="right (0px)">
          <input type="text" wcpt-model-key="margin-bottom" placeholder="bottom (0px)">
          <input type="text" wcpt-model-key="margin-left" placeholder="left (0px)">
        </div>
      </div>

      <!-- border -->
      <div class="wcpt-editor-row-option">
        <label>Border</label>
        <div class="wcpt-flex-option-container">
          <input type="text" wcpt-model-key="border-width" placeholder="1px">
          <select wcpt-model-key="border-style">
            <option value="">Auto</option>
            <option value="solid">Solid</option>
            <option value="dashed">Dashed</option>
            <option value="dotted">Dotted</option>
          </select>
          <input type="text" wcpt-model-key="border-color" placeholder="#000" class="wcpt-color-picker">
        </div>
      </div>

      <!-- border-radius -->
      <div class="wcpt-editor-row-option">
        <label>Border radius</label>
        <input type="text" wcpt-model-key="border-radius" placeholder="4px">
      </div>

    </div>
  </div>

</div>
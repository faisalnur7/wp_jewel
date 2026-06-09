<div wcpt-model-key="[container].wcpt-list-view">

  <!-- enable list layout -->
  <div class="wcpt-editor-option-row">
    <label>Enable list layout</label>
    <select wcpt-model-key="list_layout_enabled">
      <option value="">No, disable list layout</option>
      <option value="true">Yes, enable list layout</option>
    </select>
  </div>

  <!-- list row border -->
  <div class="wcpt-editor-option-row">
    <label>Row border</label>
    <div class="wcpt-flex-option-container">
      <input type="text" wcpt-model-key="--wcpt-list-row-border-width" placeholder="thickness (px)">
      <select wcpt-model-key="--wcpt-list-row-border-style">
        <option value="">Auto</option>
        <option value="solid">Solid</option>
        <option value="dashed">Dashed</option>
        <option value="dotted">Dotted</option>
        <option value="none">None</option>
      </select>
      <input type="text" wcpt-model-key="--wcpt-list-row-border-color" class="wcpt-color-picker" placeholder="color">
    </div>
  </div>

  <!-- list row border hover color -->
  <div class="wcpt-editor-option-row">
    <label>Row border hover color</label>
    <input type="text" wcpt-model-key="--wcpt-list-row-hover-border-color" class="wcpt-color-picker"
      placeholder="color">
  </div>

  <!-- list row border radius -->
  <div class="wcpt-editor-option-row">
    <label>Row border radius</label>
    <input type="text" wcpt-model-key="--wcpt-list-row-border-radius" placeholder="6px">
  </div>

  <!-- list column separator lines -->
  <div class="wcpt-editor-option-row">
    <label>Column separator lines</label>
    <div class="wcpt-flex-option-container">
      <input type="number" min="0" max="20" wcpt-model-key="--wcpt-list-row-column-separator-width"
        placeholder="thickness (px)">
      <input type="text" wcpt-model-key="--wcpt-list-row-column-separator-height" placeholder="height (default:90%)">
      <input type="text" wcpt-model-key="--wcpt-list-row-column-separator-color" class="wcpt-color-picker"
        placeholder="color">
    </div>
  </div>

  <!-- list column separator position -->
  <div class="wcpt-editor-option-row">
    <label>↳ Separator position</label>
    <div style="display: grid;
    gap: 10px;">
      <label style="padding: 0; margin: 0;">
        <input type="radio" wcpt-model-key="_column-separator-position-selection" value=""> None
      </label>
      <label style="padding: 0; margin: 0;">
        <input type="radio" wcpt-model-key="_column-separator-position-selection" value="after_first"> After first
        column
      </label>
      <label>
        <input type="radio" wcpt-model-key="_column-separator-position-selection" value="between_all"> Between all
        columns
      </label>
      <label>
        <input type="radio" wcpt-model-key="_column-separator-position-selection" value="before_last"> Before last
        column
      </label>
      <label>
        <input type="radio" wcpt-model-key="_column-separator-position-selection" value="custom"> Custom column index
      </label>
    </div>
  </div>

  <!-- list column separator position -->
  <div class="wcpt-editor-option-row" wcpt-panel-condition="prop"
    wcpt-condition-prop="_column-separator-position-selection" wcpt-condition-val="custom" wcpt-disabled="true">
    <label>↳ Column index</label>
    <input type="text" wcpt-model-key="_column-separator-position" placeholder="1, 2, 3, -1, -2">
  </div>

  <!-- list row shadow -->
  <div class="wcpt-editor-option-row">
    <label>Row shadow</label>
    <div class="wcpt-flex-option-container">
      <input type="text" wcpt-model-key="--wcpt-list-row-box-shadow-x-offset" placeholder="x-offset(2px)">
      <input type="text" wcpt-model-key="--wcpt-list-row-box-shadow-y-offset" placeholder="y-offset(2px)">
      <input type="text" wcpt-model-key="--wcpt-list-row-box-shadow-blur-radius" placeholder="blur(4px)">
      <input type="text" wcpt-model-key="--wcpt-list-row-box-shadow-spread-radius" placeholder="spread(0px)">
      <input type="text" wcpt-model-key="--wcpt-list-row-box-shadow-color" class="wcpt-color-picker"
        placeholder="color(rgba(0, 0, 0, 0.1))">
    </div>
  </div>

  <!-- list row gap -->
  <div class="wcpt-editor-option-row">
    <label>Row gap</label>
    <input type="text" wcpt-model-key="--wcpt-list-row-gap" placeholder="15px">
  </div>

</div>
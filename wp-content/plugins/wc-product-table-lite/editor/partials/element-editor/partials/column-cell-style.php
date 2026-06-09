<div class="wcpt-editor-row-style-options" wcpt-model-key="style">

  <div class="wcpt-wrapper" wcpt-model-key="[id]">

    <!-- text-align -->
    <div class="wcpt-editor-row-option">
      <label>Text align</label>
      <select wcpt-model-key="text-align">
        <option value="">Auto</option>
        <option value="center">Center</option>
        <option value="left">Left</option>
        <option value="right">Right</option>
      </select>
    </div>

    <!-- vertical-align -->
    <div class="wcpt-editor-row-option">
      <label>Vertical align</label>
      <select wcpt-model-key="vertical-align">
        <option value="">Auto</option>
        <option value="top">Top</option>
        <option value="middle">Middle</option>
        <option value="bottom">Bottom</option>
      </select>
    </div>

    <!-- elements-wrap -->
    <div class="wcpt-editor-row-option">
      <label>
        Allow elements to wrap to new lines
      </label>
      <select wcpt-model-key="--wcpt-column-cell-inner-rows-wrap">
        <option value="">Yes, allow wrapping</option>
        <option value="nowrap">No, don't allow wrapping</option>
      </select>
    </div>

    <!-- width -->
    <div class="wcpt-editor-row-option">
      <label>Fixed width</label>
      <input type="text" wcpt-model-key="width" min="0" />
    </div>

    <!-- gap between inner rows -->
    <div class="wcpt-editor-row-option">
      <label>Gap between inner rows</label>
      <input type="text" wcpt-model-key="--wcpt-column-cell-inner-rows-gap" min="0" />
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
        <input type="text" wcpt-model-key="padding-top" placeholder="top">
        <input type="text" wcpt-model-key="padding-right" placeholder="right">
        <input type="text" wcpt-model-key="padding-bottom" placeholder="bottom">
        <input type="text" wcpt-model-key="padding-left" placeholder="left">
      </div>
    </div>


  </div>

</div>
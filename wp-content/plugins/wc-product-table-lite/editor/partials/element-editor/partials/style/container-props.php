<!-- background color -->
<div class="wcpt-editor-row-option">
  <label>Background color</label>
  <input type="text" wcpt-model-key="background-color" class="wcpt-color-picker">
</div>

<!-- border -->
<div class="wcpt-editor-row-option wcpt-borders-style">
  <label>Border</label>
  <input type="text" wcpt-model-key="border-width" placeholder="width">
  <select wcpt-model-key="border-style">
    <option value="" default>Auto</option>
    <option value="solid">Solid</option>
    <option value="dashed">Dashed</option>
    <option value="dotted">Dotted</option>
    <option value="none">None</option>
  </select>
  <input type="text" wcpt-model-key="border-color" class="wcpt-color-picker" placeholder="color">
</div>

<!-- border-radius -->
<div class="wcpt-editor-row-option">
  <label>Border radius</label>
  <input type="text" wcpt-model-key="border-radius">
</div>

<!-- width -->
<div class="wcpt-editor-row-option">
  <label>Width</label>
  <input type="text" wcpt-model-key="width" />
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

<!-- display -->
<div class="wcpt-editor-row-option">
  <label>Display</label>
  <select wcpt-model-key="display">
    <option value="">Auto</option>
    <option value="block">Block</option>
    <option value="inline">Inline</option>
    <option value="inline-block">Inline-block</option>
    <option value="flex">Flex</option>
    <option value="inline-flex">Inline-flex</option>
    <option value="none">None</option>
  </select>
</div>

<!-- vertial align -->
<div class="wcpt-editor-row-option">
  <label>Vertical align</label>
  <select wcpt-model-key="vertical-align">
    <option value=""></option>
    <option value="middle">Middle</option>
    <option value="top">Top</option>
    <option value="bottom">Bottom</option>
    <option value="baseline">Baseline</option>
  </select>
</div>
<small style="
  font-style: italic;
  margin-bottom: 25px;
  display: block;
  ">To remove a border set the width to 0px</small>

<div wcpt-model-key="[container] .wcpt-table">

  <!-- table border radius -->
  <div class="wcpt-editor-option-row">
    <label>Table border radius</label>
    <div class="wcpt-flex-option-container">
      <input type="text" wcpt-model-key="border-radius" placeholder="0px">
    </div>
  </div>

  <!-- table top -->
  <div class="wcpt-editor-option-row">
    <label>Table top</label>
    <div class="wcpt-flex-option-container">
      <input type="text" wcpt-model-key="border-top-width" placeholder="thickness (px)">
      <select wcpt-model-key="border-top-style">
        <option value="">Auto</option>
        <option value="solid">Solid</option>
        <option value="dashed">Dashed</option>
        <option value="dotted">Dotted</option>
        <option value="none">None</option>
      </select>
      <input type="text" wcpt-model-key="border-top-color" class="wcpt-color-picker" placeholder="color">
    </div>
  </div>

  <!-- table right -->
  <div class="wcpt-editor-option-row">
    <label>Table right</label>
    <div class="wcpt-flex-option-container">
      <input type="text" wcpt-model-key="border-right-width" placeholder="thickness (px)">
      <select wcpt-model-key="border-right-style">
        <option value="">Auto</option>
        <option value="solid">Solid</option>
        <option value="dashed">Dashed</option>
        <option value="dotted">Dotted</option>
        <option value="none">None</option>
      </select>
      <input type="text" wcpt-model-key="border-right-color" class="wcpt-color-picker" placeholder="color">
    </div>
  </div>

  <!-- table bottom -->
  <div class="wcpt-editor-option-row">
    <label>Table bottom</label>
    <div class="wcpt-flex-option-container">
      <input type="text" wcpt-model-key="border-bottom-width" placeholder="thickness (px)">
      <select wcpt-model-key="border-bottom-style">
        <option value="">Auto</option>
        <option value="solid">Solid</option>
        <option value="dashed">Dashed</option>
        <option value="dotted">Dotted</option>
        <option value="none">None</option>
      </select>
      <input type="text" wcpt-model-key="border-bottom-color" class="wcpt-color-picker" placeholder="color">
    </div>
  </div>

  <!-- table left -->
  <div class="wcpt-editor-option-row">
    <label>Table left</label>
    <div class="wcpt-flex-option-container">
      <input type="text" wcpt-model-key="border-left-width" placeholder="thickness (px)">
      <select wcpt-model-key="border-left-style">
        <option value="">Auto</option>
        <option value="solid">Solid</option>
        <option value="dashed">Dashed</option>
        <option value="dotted">Dotted</option>
        <option value="none">None</option>
      </select>
      <input type="text" wcpt-model-key="border-left-color" class="wcpt-color-picker" placeholder="color">
    </div>
  </div>

</div>

<!-- heading row border bottom -->
<div class="wcpt-editor-option-row" wcpt-model-key="[container] .wcpt-heading-row">
  <label>Heading row bottom</label>
  <div class="wcpt-flex-option-container">
    <input type="text" wcpt-model-key="border-bottom-width" placeholder="thickness (px)">
    <select wcpt-model-key="border-bottom-style">
      <option value="">Auto</option>
      <option value="solid">Solid</option>
      <option value="dashed">Dashed</option>
      <option value="dotted">Dotted</option>
      <option value="none">None</option>
    </select>
    <input type="text" wcpt-model-key="border-bottom-color" class="wcpt-color-picker" placeholder="color">
  </div>
</div>

<!-- between headings -->
<div class="wcpt-editor-option-row" wcpt-model-key="[container] thead th.wcpt-heading">
  <label>Between headings</label>
  <div class="wcpt-flex-option-container">
    <input type="text" wcpt-model-key="border-right-width" placeholder="thickness (px)">
    <select wcpt-model-key="border-right-style">
      <option value="">Auto</option>
      <option value="solid">Solid</option>
      <option value="dashed">Dashed</option>
      <option value="dotted">Dotted</option>
      <option value="none">None</option>
    </select>
    <input type="text" wcpt-model-key="border-right-color" class="wcpt-color-picker" placeholder="color">
  </div>
</div>

<!-- border between columns -->
<div class="wcpt-editor-option-row" wcpt-model-key="[container] .wcpt-cell">
  <label>Between columns</label>
  <div class="wcpt-flex-option-container">
    <input type="text" wcpt-model-key="border-right-width" placeholder="thickness (px)">
    <select wcpt-model-key="border-right-style">
      <option value="">Auto</option>
      <option value="solid">Solid</option>
      <option value="dashed">Dashed</option>
      <option value="dotted">Dotted</option>
      <option value="none">None</option>
    </select>
    <input type="text" wcpt-model-key="border-right-color" class="wcpt-color-picker" placeholder="color">
  </div>
</div>

<!-- border between rows -->
<div class="wcpt-editor-option-row" wcpt-model-key="[container] .wcpt-row">
  <label>Between rows</label>
  <div class="wcpt-flex-option-container">
    <input type="text" wcpt-model-key="border-bottom-width" placeholder="thickness (px)">
    <select wcpt-model-key="border-bottom-style">
      <option value="">Auto</option>
      <option value="solid">Solid</option>
      <option value="dashed">Dashed</option>
      <option value="dotted">Dotted</option>
      <option value="none">None</option>
    </select>
    <input type="text" wcpt-model-key="border-bottom-color" class="wcpt-color-picker" placeholder="color">
  </div>
</div>
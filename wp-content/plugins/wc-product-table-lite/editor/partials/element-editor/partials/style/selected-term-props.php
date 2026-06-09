<!-- font color -->
<div class="wcpt-editor-row-option">
  <label>Font color</label>
  <input type="text" wcpt-model-key="color" placeholder="#000" class="wcpt-color-picker">
</div>

<!-- font-weight -->
<div class="wcpt-editor-row-option">
  <label>Font weight</label>
  <select wcpt-model-key="font-weight">
    <option value=""></option>
    <option value="normal">Normal</option>
    <option value="bold">Bold</option>
    <option value="lighter">Lighter</option>
    <option value="100">100</option>
    <option value="200">200</option>
    <option value="300">300</option>
    <option value="400">400</option>
    <option value="500">500</option>
    <option value="600">600</option>
    <option value="700">700</option>
    <option value="800">800</option>
    <option value="900">900</option>
  </select>
</div>

<!-- text-transform -->
<div class="wcpt-editor-row-option">
  <label>Text transform</label>
  <select wcpt-model-key="text-transform">
    <option value="">Auto</option>
    <option value="none">None</option>
    <option value="uppercase">Upper case</option>
    <option value="capitalize">Capitalize</option>
    <option value="lowercase">Lower case</option>
  </select>
</div>

<!-- background color -->
<div class="wcpt-editor-row-option">
  <label>Background color</label>
  <input type="text" wcpt-model-key="background-color" class="wcpt-color-picker">
</div>

<!-- background color on hover -->
<div class="wcpt-editor-row-option">
  <label>↳ color on hover</label>
  <input type="text" wcpt-model-key="background-color:hover" class="wcpt-color-picker">
</div>

<!-- border -->
<div class="wcpt-editor-row-option wcpt-borders-style">
  <label>Border</label>
  <input type="text" wcpt-model-key="border-width" placeholder="width">
  <select wcpt-model-key="border-style">
    <option value="">Auto</option>
    <option value="solid">Solid</option>
    <option value="dashed">Dashed</option>
    <option value="dotted">Dotted</option>
    <option value="none">None</option>
  </select>
  <input type="text" wcpt-model-key="border-color" class="wcpt-color-picker" placeholder="color">
</div>

<!-- border-color on hover -->
<div class="wcpt-editor-row-option">
  <label>↳ color on hover</label>
  <input type="text" wcpt-model-key="border-color:hover" class="wcpt-color-picker" placeholder="color">
</div>

<!-- border-radius -->
<div class="wcpt-editor-row-option">
  <label>Border radius</label>
  <input type="text" wcpt-model-key="border-radius">
</div>
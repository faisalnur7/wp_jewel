<!-- font-size -->
<div class="wcpt-editor-row-option">
  <label>Font size</label>
  <input type="text" wcpt-model-key="font-size" />
</div>

<!-- font color -->
<div class="wcpt-editor-row-option">
  <label>Font color</label>
  <input type="text" wcpt-model-key="color" placeholder="#000" class="wcpt-color-picker">
</div>

<!-- font color on hover -->
<div class="wcpt-editor-row-option">
  <label>↳ color on hover</label>
  <input type="text" wcpt-model-key="color:hover" placeholder="#000" class="wcpt-color-picker">
</div>

<!-- font-weight -->
<div class="wcpt-editor-row-option">
  <label>Font weight</label>
  <select wcpt-model-key="font-weight">
    <option value="">Auto</option>
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
    <option value="" default>Auto</option>
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

<?php wcpt_editor_more_options_container_start(); ?>

<!-- line-height -->
<div class="wcpt-editor-row-option">
  <label>Line height</label>
  <input type="text" wcpt-model-key="line-height" placeholder="1.2em">
</div>

<!-- font-family -->
<div class="wcpt-editor-row-option">
  <label>Font family</label>
  <input type="text" wcpt-model-key="font-family" />
</div>

<!-- text-decoration -->
<div class="wcpt-editor-row-option">
  <label>Text decoration</label>
  <select wcpt-model-key="text-decoration">
    <option value="">None</option>
    <option value="underline">Underline</option>
    <option value="overline">Overline</option>
    <option value="line-through">Line through</option>
    <option value="underline overline">Underline overline</option>
    <option value="underline line-through">Underline line-through</option>
    <option value="overline line-through">Overline line-through</option>
    <option value="underline overline line-through">Underline overline line-through</option>
  </select>
</div>

<!-- text-decoration on hover -->
<div class="wcpt-editor-row-option">
  <label>↳ on hover</label>
  <select wcpt-model-key="text-decoration:hover">
    <option value="">None</option>
    <option value="underline">Underline</option>
    <option value="overline">Overline</option>
    <option value="line-through">Line through</option>
    <option value="underline overline">Underline overline</option>
    <option value="underline line-through">Underline line-through</option>
    <option value="overline line-through">Overline line-through</option>
    <option value="underline overline line-through">Underline overline line-through</option>
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

<!-- letter-spacing -->
<div class="wcpt-editor-row-option">
  <label>Letter spacing</label>
  <input type="text" wcpt-model-key="letter-spacing" placeholder="0px">
</div>

<!-- width -->
<div class="wcpt-editor-row-option">
  <label>Width</label>
  <input type="text" wcpt-model-key="width" />
</div>

<!-- height -->
<div class="wcpt-editor-row-option">
  <label>Height</label>
  <input type="text" wcpt-model-key="height" />
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
    <option value="flex">Flex</option>
    <option value="inline-flex">Inline-flex</option>
    <option value="inline-block">Inline-block</option>
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

<?php wcpt_editor_more_options_container_end(); ?>
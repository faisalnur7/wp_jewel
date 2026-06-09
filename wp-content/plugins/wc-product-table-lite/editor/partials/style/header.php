<div wcpt-model-key="[container] .wcpt-header.wcpt-navigation">

  <!-- margin-bottom -->
  <div class="wcpt-editor-option-row">
    <label>Gap from table</label>
    <input type="text" wcpt-model-key="margin-bottom" style="width: 100% !important;" />
  </div>

  <!-- row_gap -->
  <div class="wcpt-editor-option-row">
    <label>Gap between rows</label>
    <input type="text" wcpt-model-key="row_gap" />
  </div>

  <!-- font-size -->
  <div class="wcpt-editor-option-row">
    <label>Font size</label>
    <input type="text" wcpt-model-key="font-size" />
  </div>

  <!-- line-height -->
  <div class="wcpt-editor-option-row">
    <label>Line height</label>
    <input type="text" wcpt-model-key="line-height" placeholder="1.2em">
  </div>

  <!-- font color -->
  <div class="wcpt-editor-option-row">
    <label>Font color</label>
    <input type="text" wcpt-model-key="color" placeholder="#000" class="wcpt-color-picker">
  </div>

  <!-- font-weight -->
  <div class="wcpt-editor-option-row">
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

  <!-- font-family -->
  <div class="wcpt-editor-option-row">
    <label>Font family</label>
    <input type="text" wcpt-model-key="font-family" />
  </div>

  <!-- background color -->
  <div class="wcpt-editor-option-row">
    <label>Background color</label>
    <input type="text" wcpt-model-key="background-color" class="wcpt-color-picker">
  </div>

  <!-- border -->
  <div class="wcpt-editor-option-row wcpt-borders-style">
    <label>Border</label>
    <input type="text" wcpt-model-key="border-width" placeholder="width">
    <select wcpt-model-key="border-style">
      <option value="solid">Solid</option>
      <option value="dashed">Dashed</option>
      <option value="dotted">Dotted</option>
      <option value="none">None</option>
    </select>
    <input type="text" wcpt-model-key="border-color" class="wcpt-color-picker" placeholder="color">
  </div>

  <!-- border-radius -->
  <div class="wcpt-editor-option-row">
    <label>Border radius</label>
    <input type="text" wcpt-model-key="border-radius">
  </div>

  <!-- padding -->
  <div class="wcpt-editor-option-row">
    <label>Padding</label>
    <div class="wcpt-flex-option-container">
      <input type="text" wcpt-model-key="padding-top" placeholder="top">
      <input type="text" wcpt-model-key="padding-right" placeholder="right">
      <input type="text" wcpt-model-key="padding-bottom" placeholder="bottom">
      <input type="text" wcpt-model-key="padding-left" placeholder="left">
    </div>
  </div>

  <!-- alignment -->
  <div class="wcpt-editor-option-row">
    <label>Responsive alignment</label>
    <select wcpt-model-key="--wcpt-nav-header-responsive-alignment">
      <option value="">Auto</option>
      <option value="right">Right</option>
      <option value="left">Left</option>
      <option value="center">Center</option>
    </select>
  </div>

</div>

<!-- Dropdown Heading -->
<?php wcpt_general_style_accordion_open('Dropdown heading', '[container] .wcpt-header.wcpt-navigation .wcpt-dropdown.wcpt-filter > .wcpt-filter-heading'); ?>

<!-- font-size -->
<div class="wcpt-editor-option-row">
  <label>Font size</label>
  <input type="text" wcpt-model-key="font-size" />
</div>

<!-- font-weight -->
<div class="wcpt-editor-option-row">
  <label>Font weight</label>
  <select wcpt-model-key="font-weight">
    <option value="normal">Normal</option>
    <option value="bold">Bold</option>
    <option value="200">Light</option>
  </select>
</div>

<!-- font color -->
<div class="wcpt-editor-option-row">
  <label>Font color</label>
  <input type="text" wcpt-model-key="color" placeholder="#000" class="wcpt-color-picker">
</div>

<!-- font color on hover -->
<div class="wcpt-editor-option-row">
  <label>↳ on hover</label>
  <input type="text" wcpt-model-key="color:hover" placeholder="#000" class="wcpt-color-picker">
</div>

<!-- font color when active -->
<div class="wcpt-editor-option-row">
  <label>↳ on active</label>
  <input type="text" wcpt-model-key="color:active" placeholder="#000" class="wcpt-color-picker">
</div>

<!-- background color -->
<div class="wcpt-editor-option-row">
  <label>Background color</label>
  <input type="text" wcpt-model-key="background-color" class="wcpt-color-picker">
</div>

<!-- background color on hover -->
<div class="wcpt-editor-option-row">
  <label>↳ on hover</label>
  <input type="text" wcpt-model-key="background-color:hover" class="wcpt-color-picker">
</div>

<!-- background color when active -->
<div class="wcpt-editor-option-row">
  <label>↳ on active</label>
  <input type="text" wcpt-model-key="background-color:active" class="wcpt-color-picker">
</div>

<!-- border color -->
<div class="wcpt-editor-option-row">
  <label>Border color</label>
  <input type="text" wcpt-model-key="border-color" class="wcpt-color-picker">
</div>

<!-- border color on hover -->
<div class="wcpt-editor-option-row">
  <label>↳ on hover</label>
  <input type="text" wcpt-model-key="border-color:hover" class="wcpt-color-picker">
</div>

<!-- border color when active -->
<div class="wcpt-editor-option-row">
  <label>↳ on active</label>
  <input type="text" wcpt-model-key="border-color:active" class="wcpt-color-picker">
</div>

<!-- border radius -->
<div class="wcpt-editor-option-row">
  <label>Border radius</label>
  <input type="text" wcpt-model-key="border-radius" />
</div>

<!-- padding -->
<div class="wcpt-editor-option-row">
  <label>Padding</label>
  <div class="wcpt-flex-option-container">
    <input type="text" wcpt-model-key="padding-top" placeholder="top">
    <input type="text" wcpt-model-key="padding-right" placeholder="right">
    <input type="text" wcpt-model-key="padding-bottom" placeholder="bottom">
    <input type="text" wcpt-model-key="padding-left" placeholder="left">
  </div>
</div>

<!-- margin -->
<div class="wcpt-editor-option-row">
  <label>Margin</label>
  <div class="wcpt-flex-option-container">
    <input type="text" wcpt-model-key="margin-top" placeholder="top">
    <input type="text" wcpt-model-key="margin-right" placeholder="right">
    <input type="text" wcpt-model-key="margin-bottom" placeholder="bottom">
    <input type="text" wcpt-model-key="margin-left" placeholder="left">
  </div>
</div>

<?php wcpt_general_style_accordion_close(); ?>

<!-- Dropdown menu -->
<?php wcpt_general_style_accordion_open('Dropdown menu', '[container] .wcpt-header.wcpt-navigation .wcpt-dropdown.wcpt-filter > .wcpt-dropdown-menu'); ?>

<!-- font-size -->
<div class="wcpt-editor-option-row">
  <label>Font size</label>
  <input type="text" wcpt-model-key="font-size" />
</div>

<!-- font-weight -->
<div class="wcpt-editor-option-row">
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

<!-- font color -->
<div class="wcpt-editor-option-row">
  <label>Font color</label>
  <input type="text" wcpt-model-key="color" placeholder="#000" class="wcpt-color-picker">
</div>

<!-- background color -->
<div class="wcpt-editor-option-row">
  <label>Background color</label>
  <input type="text" wcpt-model-key="background-color" class="wcpt-color-picker">
</div>

<!-- border color -->
<div class="wcpt-editor-option-row">
  <label>Border color</label>
  <input type="text" wcpt-model-key="border-color" class="wcpt-color-picker">
</div>

<!-- border radius -->
<div class="wcpt-editor-option-row">
  <label>Border radius</label>
  <input type="text" wcpt-model-key="border-radius" />
</div>

<!-- width -->
<div class="wcpt-editor-option-row">
  <label>Width</label>
  <input type="text" wcpt-model-key="width" placeholder="auto" />
</div>

<!-- max-width -->
<div class="wcpt-editor-option-row">
  <label>Max width</label>
  <input type="text" wcpt-model-key="max-width" placeholder="400px" />
</div>

<!-- max-height -->
<div class="wcpt-editor-option-row">
  <label>Max height</label>
  <input type="text" wcpt-model-key="max-height" />
</div>

<!-- padding -->
<div class="wcpt-editor-option-row">
  <label>Padding</label>
  <div class="wcpt-flex-option-container">
    <input type="text" wcpt-model-key="padding-top" placeholder="top">
    <input type="text" wcpt-model-key="padding-right" placeholder="right">
    <input type="text" wcpt-model-key="padding-bottom" placeholder="bottom">
    <input type="text" wcpt-model-key="padding-left" placeholder="left">
  </div>
</div>

<!-- margin -->
<div class="wcpt-editor-option-row">
  <label>Margin</label>
  <div class="wcpt-flex-option-container">
    <input type="text" wcpt-model-key="margin-top" placeholder="top">
    <input type="text" wcpt-model-key="margin-right" placeholder="right">
    <input type="text" wcpt-model-key="margin-bottom" placeholder="bottom">
    <input type="text" wcpt-model-key="margin-left" placeholder="left">
  </div>
</div>

<?php wcpt_general_style_accordion_close(); ?>

<!-- Dropdown menu item -->
<?php wcpt_general_style_accordion_open('Dropdown menu item', '[container] .wcpt-header.wcpt-navigation .wcpt-dropdown.wcpt-filter > .wcpt-dropdown-menu .wcpt-dropdown-option'); ?>

<!-- background color -->
<div class="wcpt-editor-option-row">
  <label>Background color</label>
  <input type="text" wcpt-model-key="background-color" class="wcpt-color-picker">
</div>

<!-- background color:hover -->
<div class="wcpt-editor-option-row">
  <label>↳ on hover</label>
  <input type="text" wcpt-model-key="background-color:hover" class="wcpt-color-picker">
</div>

<!-- padding -->
<div class="wcpt-editor-option-row">
  <label>Padding</label>
  <div class="wcpt-flex-option-container">
    <input type="text" wcpt-model-key="padding-top" placeholder="top">
    <input type="text" wcpt-model-key="padding-right" placeholder="right">
    <input type="text" wcpt-model-key="padding-bottom" placeholder="bottom">
    <input type="text" wcpt-model-key="padding-left" placeholder="left">
  </div>
</div>

<?php wcpt_general_style_accordion_close(); ?>

<!-- Dropdown Search -->
<?php wcpt_general_style_accordion_open('Dropdown menu search', '[container] .wcpt-header.wcpt-navigation .wcpt-dropdown.wcpt-filter > .wcpt-dropdown-menu .wcpt-search-filter-options'); ?>

<!-- font-size -->
<div class="wcpt-editor-option-row">
  <label>Font size</label>
  <input type="text" wcpt-model-key="font-size" />
</div>

<!-- background color -->
<div class="wcpt-editor-option-row">
  <label>Background color</label>
  <input type="text" wcpt-model-key="background-color" class="wcpt-color-picker">
</div>

<!-- height -->
<div class="wcpt-editor-option-row">
  <label>Height</label>
  <input type="text" wcpt-model-key="height" />
</div>

<!-- border -->
<div class="wcpt-editor-option-row wcpt-borders-style">
  <label>Border</label>
  <input type="text" wcpt-model-key="border-width" placeholder="width">
  <select wcpt-model-key="border-style">
    <option value="solid">Solid</option>
    <option value="dashed">Dashed</option>
    <option value="dotted">Dotted</option>
    <option value="none">None</option>
  </select>
  <input type="text" wcpt-model-key="border-color" class="wcpt-color-picker" placeholder="color">
</div>

<!-- border radius -->
<div class="wcpt-editor-option-row">
  <label>Border radius</label>
  <input type="text" wcpt-model-key="border-radius" />
</div>

<!-- padding -->
<div class="wcpt-editor-option-row">
  <label>Padding</label>
  <div class="wcpt-flex-option-container">
    <input type="text" wcpt-model-key="padding-top" placeholder="top">
    <input type="text" wcpt-model-key="padding-right" placeholder="right">
    <input type="text" wcpt-model-key="padding-bottom" placeholder="bottom">
    <input type="text" wcpt-model-key="padding-left" placeholder="left">
  </div>
</div>

<!-- margin -->
<div class="wcpt-editor-option-row">
  <label>Margin</label>
  <div class="wcpt-flex-option-container">
    <input type="text" wcpt-model-key="margin-top" placeholder="top">
    <input type="text" wcpt-model-key="margin-bottom" placeholder="bottom">
  </div>
</div>

<?php wcpt_general_style_accordion_close(); ?>

<!-- Row heading -->
<?php wcpt_general_style_accordion_open('Row heading', '[container] .wcpt-header.wcpt-navigation .wcpt-options-row.wcpt-filter > .wcpt-filter-heading'); ?>

<div class="wcpt-editor-option-row wcpt-separation-heading">
  Row heading
</div>

<!-- font-size -->
<div class="wcpt-editor-option-row">
  <label>Font size</label>
  <input type="text" wcpt-model-key="font-size" />
</div>

<!-- font-weight -->
<div class="wcpt-editor-option-row">
  <label>Font weight</label>
  <select wcpt-model-key="font-weight">
    <option value="normal">Normal</option>
    <option value="bold">Bold</option>
    <option value="200">Light</option>
  </select>
</div>

<!-- font color -->
<div class="wcpt-editor-option-row">
  <label>Font color</label>
  <input type="text" wcpt-model-key="color" placeholder="#000" class="wcpt-color-picker">
</div>

<!-- padding -->
<div class="wcpt-editor-option-row">
  <label>Padding</label>
  <div class="wcpt-flex-option-container">
    <input type="text" wcpt-model-key="padding-top" placeholder="top">
    <input type="text" wcpt-model-key="padding-right" placeholder="right">
    <input type="text" wcpt-model-key="padding-bottom" placeholder="bottom">
    <input type="text" wcpt-model-key="padding-left" placeholder="left">
  </div>
</div>

<!-- margin -->
<div class="wcpt-editor-option-row">
  <label>Margin</label>
  <div class="wcpt-flex-option-container">
    <input type="text" wcpt-model-key="margin-top" placeholder="top">
    <input type="text" wcpt-model-key="margin-right" placeholder="right">
    <input type="text" wcpt-model-key="margin-bottom" placeholder="bottom">
    <input type="text" wcpt-model-key="margin-left" placeholder="left">
  </div>
</div>

<?php wcpt_general_style_accordion_close(); ?>

<!-- Row options -->
<?php wcpt_general_style_accordion_open('Row button options', '[container] .wcpt-header.wcpt-navigation .wcpt-options-row.wcpt-filter > .wcpt-options > .wcpt-option'); ?>

<div class="wcpt-editor-option-row wcpt-separation-heading">
  Row options
</div>

<!-- font-size -->
<div class="wcpt-editor-option-row">
  <label>Font size</label>
  <input type="text" wcpt-model-key="font-size" />
</div>

<!-- font-weight -->
<div class="wcpt-editor-option-row">
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

<!-- font color -->
<div class="wcpt-editor-option-row">
  <label>Font color</label>
  <input type="text" wcpt-model-key="color" placeholder="#000" class="wcpt-color-picker">
</div>

<!-- font color:hover -->
<div class="wcpt-editor-option-row">
  <label>↳ hover </label>
  <input type="text" wcpt-model-key="color:hover" placeholder="#000" class="wcpt-color-picker">
</div>

<!-- font color:selected -->
<div class="wcpt-editor-option-row">
  <label>↳ selected </label>
  <input type="text" wcpt-model-key="color:selected" placeholder="#000" class="wcpt-color-picker">
</div>

<!-- background color -->
<div class="wcpt-editor-option-row">
  <label>Background color</label>
  <input type="text" wcpt-model-key="background-color" class="wcpt-color-picker">
</div>

<!-- background color:hover -->
<div class="wcpt-editor-option-row">
  <label>↳ on hover</label>
  <input type="text" wcpt-model-key="background-color:hover" class="wcpt-color-picker">
</div>

<!-- background color:selected -->
<div class="wcpt-editor-option-row">
  <label>↳ on select</label>
  <input type="text" wcpt-model-key="background-color:selected" class="wcpt-color-picker">
</div>

<!-- border color -->
<div class="wcpt-editor-option-row">
  <label>Border color</label>
  <input type="text" wcpt-model-key="border-color" class="wcpt-color-picker">
</div>

<!-- border color:hover -->
<div class="wcpt-editor-option-row">
  <label>↳ color on hover</label>
  <input type="text" wcpt-model-key="border-color:hover" class="wcpt-color-picker">
</div>

<!-- border color:selected -->
<div class="wcpt-editor-option-row">
  <label>↳ color on selected</label>
  <input type="text" wcpt-model-key="border-color:selected" class="wcpt-color-picker">
</div>

<!-- border radius -->
<div class="wcpt-editor-option-row">
  <label>Border radius</label>
  <input type="text" wcpt-model-key="border-radius" />
</div>

<!-- width -->
<div class="wcpt-editor-option-row">
  <label>Width</label>
  <input type="text" wcpt-model-key="width" />
</div>

<!-- padding -->
<div class="wcpt-editor-option-row">
  <label>Padding</label>
  <div class="wcpt-flex-option-container">
    <input type="text" wcpt-model-key="padding-top" placeholder="top">
    <input type="text" wcpt-model-key="padding-right" placeholder="right">
    <input type="text" wcpt-model-key="padding-bottom" placeholder="bottom">
    <input type="text" wcpt-model-key="padding-left" placeholder="left">
  </div>
</div>

<!-- margin -->
<div class="wcpt-editor-option-row">
  <label>Margin</label>
  <div class="wcpt-flex-option-container">
    <input type="text" wcpt-model-key="margin-top" placeholder="top">
    <input type="text" wcpt-model-key="margin-right" placeholder="right">
    <input type="text" wcpt-model-key="margin-bottom" placeholder="bottom">
    <input type="text" wcpt-model-key="margin-left" placeholder="left">
  </div>
</div>

<?php wcpt_general_style_accordion_close(); ?>
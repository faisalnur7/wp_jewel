<div wcpt-model-key="[container] .wcpt-left-sidebar.wcpt-navigation">

  <!-- width -->
  <div class="wcpt-editor-option-row">
    <label>Width</label>
    <input type="text" wcpt-model-key="width" placeholder="250px" />
  </div>

  <!-- gap -->
  <div class="wcpt-editor-option-row">
    <label>Gap from table</label>
    <input type="text" wcpt-model-key="gap" placeholder="30px" />
  </div>

  <!-- sticky -->
  <div class="wcpt-editor-option-row">
    <label>Enable sticky</label>
    <select wcpt-model-key="_sticky_enabled">
      <option value="">No</option>
      <option value="yes">Yes</option>
    </select>
  </div>

  <!-- float -->
  <div class="wcpt-editor-option-row">
    <label>Position</label>
    <select wcpt-model-key="float">
      <option value="left">Left</option>
      <option value="right">Right</option>
    </select>
  </div>

  <!-- background-color -->
  <div class="wcpt-editor-option-row">
    <label>Background color</label>
    <input type="text" wcpt-model-key="background-color" class="wcpt-color-picker">
  </div>

  <!-- font-size -->
  <div class="wcpt-editor-option-row">
    <label>Font size</label>
    <input type="text" wcpt-model-key="font-size" placeholder="16px">
  </div>

  <!-- font-color -->
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
    <input type="text" wcpt-model-key="border-radius" placeholder="4px">
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

</div>

<!-- Sections -->
<?php wcpt_general_style_accordion_open('Section', '[container] .wcpt-left-sidebar.wcpt-navigation > div.wcpt-item-row > div'); ?>
<!-- divider -->
<div class="wcpt-editor-option-row wcpt-borders-style">
  <label>Divider</label>
  <input type="text" wcpt-model-key="border-width" placeholder="width">
  <select wcpt-model-key="border-style">
    <option value="solid">Solid</option>
    <option value="dashed">Dashed</option>
    <option value="dotted">Dotted</option>
    <option value="none">None</option>
  </select>
  <input type="text" wcpt-model-key="border-color" class="wcpt-color-picker" placeholder="color">
</div>

<!-- padding -->
<div class="wcpt-editor-option-row wcpt-padding-props">
  <label>Padding</label>
  <div class="wcpt-flex-option-container">
    <input type="text" wcpt-model-key="padding-top" placeholder="top">
    <input type="text" wcpt-model-key="padding-right" placeholder="right">
    <input type="text" wcpt-model-key="padding-bottom" placeholder="bottom">
    <input type="text" wcpt-model-key="padding-left" placeholder="left">
  </div>
</div>
<?php wcpt_general_style_accordion_close(); ?>

<!-- Dropdown Heading -->
<?php wcpt_general_style_accordion_open('Dropdown heading', '[container] .wcpt-left-sidebar.wcpt-navigation .wcpt-dropdown.wcpt-filter > .wcpt-filter-heading'); ?>

<!-- font-size -->
<div class="wcpt-editor-option-row">
  <label>Font size</label>
  <input type="text" wcpt-model-key="font-size" />
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

<!-- padding -->
<div class="wcpt-editor-option-row wcpt-padding-props">
  <label>Padding</label>
  <div class="wcpt-flex-option-container">
    <input type="text" wcpt-model-key="padding-top" placeholder="top">
    <input type="text" wcpt-model-key="padding-right" placeholder="right">
    <input type="text" wcpt-model-key="padding-bottom" placeholder="bottom">
    <input type="text" wcpt-model-key="padding-left" placeholder="left">
  </div>
</div>
<?php wcpt_general_style_accordion_close(); ?>

<!-- Dropdown Menu -->
<?php wcpt_general_style_accordion_open('Dropdown menu', '[container] .wcpt-left-sidebar.wcpt-navigation .wcpt-dropdown.wcpt-filter > .wcpt-dropdown-menu, [container] .wcpt-left-sidebar.wcpt-navigation .wcpt-dropdown.wcpt-filter > .wcpt-options'); ?>
<!-- font-size -->
<div class="wcpt-editor-option-row">
  <label>Font size</label>
  <input type="text" wcpt-model-key="font-size" />
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

<!-- max-height -->
<div class="wcpt-editor-option-row">
  <label>Max height</label>
  <input type="text" wcpt-model-key="max-height" />
</div>
</div>

<div class="wcpt-wrapper"
  wcpt-model-key="[container] .wcpt-left-sidebar.wcpt-navigation .wcpt-dropdown.wcpt-filter > .wcpt-dropdown-menu > .wcpt-dropdown-option:hover, [container] .wcpt-left-sidebar.wcpt-navigation .wcpt-dropdown.wcpt-filter > .wcpt-options > .wcpt-option:hover">

  <!-- font color hover -->
  <div class="wcpt-editor-option-row">
    <label>Font color hover</label>
    <input type="text" wcpt-model-key="color" class="wcpt-color-picker" />
  </div>


  <!-- background hover -->
  <div class="wcpt-editor-option-row">
    <label>Background hover</label>
    <input type="text" wcpt-model-key="background" class="wcpt-color-picker" />
  </div>

  <?php wcpt_general_style_accordion_close(); ?>

  <!-- Dropdown Search -->
  <?php wcpt_general_style_accordion_open('Dropdown menu search', '[container] .wcpt-left-sidebar.wcpt-navigation .wcpt-dropdown.wcpt-filter > .wcpt-dropdown-menu .wcpt-search-filter-options, [container] .wcpt-left-sidebar.wcpt-navigation .wcpt-dropdown.wcpt-filter > .wcpt-options .wcpt-search-filter-options'); ?>

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